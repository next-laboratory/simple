<?php

return array (
  'bindings' => 
  array (
    'Psr\\EventDispatcher\\ListenerProviderInterface' => 'Max\\Event\\ListenerProvider',
    'Psr\\EventDispatcher\\EventDispatcherInterface' => 'Max\\Event\\EventDispatcher',
    'Max\\Event\\Contracts\\EventDispatcherInterface' => 'Max\\Event\\EventDispatcher',
    'Psr\\SimpleCache\\CacheInterface' => 'Max\\Foundation\\Cache\\Cache',
    'Psr\\Container\\ContainerInterface' => 'Max\\Di\\Container',
    'Psr\\Http\\Message\\ServerRequestInterface' => 'Max\\Http\\ServerRequest',
    'Psr\\Http\\Message\\ResponseInterface' => 'Max\\Http\\Response',
    'Psr\\Http\\Server\\RequestHandlerInterface' => 'Max\\Http\\RequestHandler',
    'Psr\\Log\\LoggerInterface' => 'Max\\Log\\Logger',
  ),
);