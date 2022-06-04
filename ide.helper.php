<?php

namespace Psr\Http\Message {

    use Max\HttpMessage\ServerRequest;

    /**
     * @mixin ServerRequest
     */
    interface ServerRequestInterface
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
