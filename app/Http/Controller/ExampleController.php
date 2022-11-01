<?php

namespace App\Http\Controller;

use App\Http\Response;
use App\Logger;
use Max\Di\Attribute\Inject;
use Max\Http\Server\Event\OnRequest;
use Max\Http\Server\Middleware\SessionMiddleware;
use Max\Routing\Attribute\Controller;
use Max\Routing\Attribute\GetMapping;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use stdClass;

#[Controller(prefix: 'example')]
class ExampleController
{
    #[Inject]
    protected Logger $logger;
    #[Inject]
    protected EventDispatcherInterface $eventDispatcher;

    /**
     * 事件使用示例
     */
    #[GetMapping(path: '/event')]
    public function eventExample(ServerRequestInterface $request)
    {
        $response = Response::text('Hello, ' . $request->query('name', 'MaxPHP'));
        $this->eventDispatcher->dispatch(new OnRequest($request, $response));
        $this->logger->get('app')->info('[eventDispatched] OnRequest', [
            'request' => $request->all(),
            'headers' => $request->getHeaders(),
        ]);
        return $response;
    }

    /**
     * Session 操作示例
     */
    #[GetMapping(path: '/session', middlewares: [SessionMiddleware::class])]
    public function sessionExample(ServerRequestInterface $request)
    {
        $sessionHandle = $request->session();
        $sessionHandle->set('user', (object)['name' => 'libai']);
        return Response::JSON($sessionHandle->all());
    }

    /**
     * JSONP 响应示例
     * query参数添加 callback=函数名，例如：http://127.0.0.1:8989/jsonp?callback=getUserInfo
     */
    #[GetMapping(path: '/jsonp')]
    public function jsonpExample(ServerRequestInterface $request)
    {
        return Response::JSONP($request, ['foo' => 'bar']);
    }
}
