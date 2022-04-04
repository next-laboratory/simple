<?php

return array (
  'bindings' => 
  array (
    'Psr\\EventDispatcher\\ListenerProviderInterface' => 'Max\\Event\\ListenerProvider',
    'Psr\\EventDispatcher\\EventDispatcherInterface' => 'Max\\Event\\EventDispatcher',
    'Max\\Event\\Contracts\\EventDispatcherInterface' => 'Max\\Event\\EventDispatcher',
    'Psr\\SimpleCache\\CacheInterface' => 'Max\\Foundation\\Cache\\Cache',
    'Psr\\Container\\ContainerInterface' => 'Max\\Di\\Container',
    'Psr\\Http\\Message\\ServerRequestInterface' => 'Max\\Foundation\\Http\\ServerRequest',
    'Psr\\Http\\Message\\ResponseInterface' => 'Max\\Foundation\\Http\\Response',
    'Psr\\Http\\Server\\RequestHandlerInterface' => 'Max\\Foundation\\Http\\RequestHandler',
    'Psr\\Log\\LoggerInterface' => 'Max\\Log\\Logger',
  ),
);