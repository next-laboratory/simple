<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

use Amp\Http\Server\HttpServer;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Socket\Server;
use App\Bootstrap;
use App\Http\Kernel;
use App\Http\ServerRequest;
use App\Logger;
use Max\Di\Context;
use Max\Http\Server\ResponseEmitter\AmpResponseEmitter;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'base.php';

(function () {
    require_once './vendor/autoload.php';
    if (! class_exists('Amp\Http\Server\HttpServer')) {
        throw new Exception('You should install the amphp/http-server package before starting.');
    }
    Bootstrap::boot(true);

    $container = Context::getContainer();
    $kernel    = $container->make(Kernel::class);
    $logger    = $container->make(Logger::class)->get();

    Amp\Loop::run(function () use ($kernel, $logger) {
        $port    = 8989;
        $sockets = [
            Server::listen("0.0.0.0:{$port}"),
            Server::listen("[::]:{$port}"),
        ];

        $server = new HttpServer($sockets, new CallableRequestHandler(
            fn (Request $request) => (new AmpResponseEmitter())->emit($kernel->through(ServerRequest::createFromAmp($request)))
        ), $logger);
        echo <<<'EOT'
,--.   ,--.                  ,------. ,--.  ,--.,------.  
|   `.'   | ,--,--.,--.  ,--.|  .--. '|  '--'  ||  .--. ' 
|  |'.'|  |' ,-.  | \  `'  / |  '--' ||  .--.  ||  '--' | 
|  |   |  |\ '-'  | /  /.  \ |  | --' |  |  |  ||  | --'  
`--'   `--' `--`--''--'  '--'`--'     `--'  `--'`--' 

EOT;
        printf("System       Name:       %s\n", strtolower(PHP_OS));
        printf("Container    Name:       AmpPHP\n");
        printf("PHP          Version:    %s\n", PHP_VERSION);
        printf("Listen       Addr:       :%d\n", $port);
        yield $server->start();

        // Stop the server gracefully when SIGINT is received.
        // This is technically optional, but it is best to call Server::stop().
        Amp\Loop::onSignal(SIGINT, function (string $watcherId) use ($server) {
            Amp\Loop::cancel($watcherId);
            yield $server->stop();
        });
    });
})();
