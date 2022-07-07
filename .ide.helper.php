<?php

namespace Psr\Http\Message {

    use App\Http\Response;
    use App\Http\ServerRequest;

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

namespace Psr\Log {

    use App\Logger;

    /**
     * @mixin Logger
     */
    interface LoggerInterface
    {
    }
}
