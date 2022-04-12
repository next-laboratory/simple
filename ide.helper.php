<?php

namespace Psr\Http\Message {

    use Max\Http\Response;
    use Max\Http\ServerRequest;

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

    use Max\Foundation\Cache\Cache;

    /**
     * @mixin Cache
     */
    interface CacheInterface
    {
    }
}
