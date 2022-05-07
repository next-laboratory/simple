<?php

namespace Psr\Http\Message {

    use App\Http\Utils\Response;
    use App\Http\Utils\ServerRequest;

    /**
     * @mixin ServerRequest
     */
    interface ServerRequestInterface
    {
    }

    /**
     * @mixin Response
     */
    interface ResponseInterface
    {
    }
}

namespace Psr\Http\Server {

    use Max\Http\Server\RequestHandler;

    /**
     * @mixin RequestHandler
     */
    interface RequestHandlerInterface
    {
    }
}

namespace Psr\Container {

    use Max\Di\Container;

    /**
     * @mixin Container
     */
    interface ContainerInterface
    {
    }
}

namespace Psr\SimpleCache {

    use Max\Cache\Cache;

    /**
     * @mixin Cache
     */
    interface CacheInterface
    {
    }
}
