<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
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
