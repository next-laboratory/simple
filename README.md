<p align="center">
<img src="https://raw.githubusercontent.com/topyao/max/master/public/favicon.ico" width="120" alt="Max">
</p>

<p align="center">轻量 • 简单 • 快速</p>

<p align="center">
<a href="https://github.com/topyao/max/issues"><img src="https://img.shields.io/github/issues/topyao/max" alt=""></a>
<a href="https://github.com/topyao/max"><img src="https://img.shields.io/github/stars/topyao/max" alt=""></a>
<img src="https://img.shields.io/badge/php-%3E%3D8.0-brightgreen" alt="">
<img src="https://img.shields.io/badge/license-apache%202-blue" alt="">
</p>

一款支持swoole, workerman, FPM环境的框架的组件化的轻量`PHP`框架，可以用作`API`开发，方便快速。框架默认安装了`session`和`view`扩展包，如果不需要可以直接移除。

## 贡献一览

[![Contributor over time](https://contributor-overtime-api.apiseven.com/contributors-svg?chart=contributorOverTime&repo=topyao/max,topyao/max-routing,topyao/max-session,topyao/max-view,topyao/max-di,topyao/max-cache,topyao/max-console,topyao/max-http,topyao/max-event,topyao/max-config,topyao/max-aop,topyao/max-env,topyao/max-database,topyao/max-log,topyao/max-redis,topyao/max-validator)](https://contributor-overtime-api.apiseven.com/contributors-svg?chart=contributorOverTime&repo=topyao/max,topyao/max-routing,topyao/max-session,topyao/max-view,topyao/max-di,topyao/max-cache,topyao/max-console,topyao/max-http,topyao/max-event,topyao/max-config,topyao/max-aop,topyao/max-env,topyao/max-database,topyao/max-log,topyao/max-redis,topyao/max-validator)

## 环境要求

```
PHP >= 8.0
SWOOLE >= 4.6
```

## 使用

### 安装

```shell
composer create-project max/simple:dev-master max
```

### 启动服务

> swoole服务

```php
php bin/swoole.php   // 异步模式
php bin/swooleco.php // 协程模式
```

> workerman服务

```php
php bin/workerman.php start
```

> 内置服务

```php
php bin/cli-server.php
```

> FPM模式，将请求指向public/index.php即可

## 区别

使用swoole/workerman支持注解，AOP等特性， FPM模式可以直接卸载AOP包。

## 简单入门

### 路由定义

> swoole/swooleco/workerman下可以使用注解定义

```php
<?php

namespace App\Controllers;

use App\Http\Response;
use Max\Routing\Annotations\Controller;
use Max\Routing\Annotations\GetMapping;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Controller(prefix: '/')]
class IndexController
{
    #[GetMapping(path: '/')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return (new Response())->HTML('Hello, ' . $request->get('name', 'MaxPHP!'));
    }
}

```

如上请求`0.0.0.0:8080` 会指向`index`方法，控制器方法接收`$request`参数和路由参数，控制器方法不支持依赖注入，最多只有一个参数，即当前请求类，且需要返回`ResponseInterface`实例。

> FPM或内置服务下不能使用注解

路由定义在`App\Kernel`类的`map`方法中定义

```php
$router->middleware(TestMiddleware::class)->group(function(Router $router) {
    $router->get('/', [IndexController::class, 'index']);
    $router->get('/test', function(\Psr\Http\Message\ServerRequestInterface $request) {
        return (new \App\Http\Response())->HTML('new');
    });
});
```

欢迎有兴趣的朋友参与开发

> 官网：https://www.1kmb.com
