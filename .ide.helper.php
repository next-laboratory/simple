<?php

declare(strict_types=1);

/**
 * This file is part of nextphp.
 *
 * @link     https://github.com/next-laboratory
 * @license  https://github.com/next-laboratory/next/blob/master/LICENSE
 */

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

    use Next\Di\Container;

    /**
     * @mixin Container
     */
    interface ContainerInterface
    {
    }
}

namespace Psr\SimpleCache {

    use Next\Cache\Cache;

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
