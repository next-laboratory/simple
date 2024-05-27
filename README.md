<p align="center">
<img src="https://raw.githubusercontent.com/next-laboratory/simple/master/public/favicon.ico" width="120" alt="Max">
</p>

<p align="center">轻量 • 简单 • 快速</p>

<p align="center">
<a href="https://github.com/next-laboratory/simple/issues"><img src="https://img.shields.io/github/issues/next-laboratory/simple" alt=""></a>
<a href="https://github.com/next-laboratory/simple"><img src="https://img.shields.io/github/stars/next-laboratory/simple" alt=""></a>
<img src="https://img.shields.io/badge/php-%3E%3D8.0-brightgreen" alt="">
<img src="https://img.shields.io/badge/license-apache%202-blue" alt="">
</p>

一款支持swoole, workerman, FPM环境的组件化的轻量`PHP`框架，可以用作`API`开发，方便快速。框架默认安装了`next/session`扩展包，如果不需要可以直接移除。

## 环境要求

```
PHP  ^8.2
```

> 如果使用swoole，务必安装4.6以上版本，如果使用workerman, 务必使用4.0以上版本

## 使用

### 安装

```shell
composer config -g repo.packagist composer https://repo.packagist.org # 更换官方仓库
composer create-project next/simple
```

### 启动服务

```php
php bin/cli.php serve:swoole     // swoole异步模式
php bin/cli.php serve:swoole-co  // swoole协程模式
php bin/cli.php serve:workerman  // workerman服务
php bin/cli.php serve:cli-server // 内置服务
```

> FPM模式，将请求指向public/index.php即可

## 区别

使用swoole/workerman/amp/react等服务支持注解、AOP等特性， FPM模式可以直接卸载AOP包。

## 简单入门

### 路由定义

> swoole/swoole-co/workerman/amp/react下配置了AOP就可以使用注解定义

```php
<?php

namespace App\Controllers;

use App\Http\Response;
use Next\Routing\Attribute\Controller;
use Next\Routing\Attribute\GetMapping;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

#[Controller(prefix: '/')]
class IndexController
{
    #[GetMapping(path: '/')]
    public function index(ServerRequestInterface $request): ResponseInterface
    {
        return Response::HTML('Hello, ' . $request->query('name', 'nextphp!'));
    }
}

```

如上请求`0.0.0.0:8989` 会指向`index`方法，控制器方法支持依赖注入，如需当前请求示例，则请求参数名必须是`request`
，其他路由参数均会被注入，控制器方法需要返回`ResponseInterface`实例。

> FPM或内置服务下不能使用注解

路由定义在`App\Http\Kernel`类的`map`方法中定义

```php
$router->middleware(TestMiddleware::class)->group(function(Router $router) {
    $router->get('/', [IndexController::class, 'index']);
    $router->get('/test', function(\Psr\Http\Message\ServerRequestInterface $request) {
        return \App\Http\Response::HTML('new');
    });
});
```

### 其他文档

其他文档参考相应包的`README`

## 参与开发

欢迎有兴趣的朋友参与开发

## 致谢

感谢PHP最好用IDE: <a href="https://www.jetbrains.com/?from=next-laboratory">PHPStorm</a>
