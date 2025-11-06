<p align="center">
<img src="https://raw.githubusercontent.com/next-laboratory/simple/master/public/favicon.ico" width="120" alt="Max">
</p>

<p align="center">轻量 • 简单 • 快速</p>

<p align="center">
<a href="https://github.com/next-laboratory/simple/issues"><img src="https://img.shields.io/github/issues/next-laboratory/simple" alt=""></a>
<a href="https://github.com/next-laboratory/simple"><img src="https://img.shields.io/github/stars/next-laboratory/simple" alt=""></a>
<img src="https://img.shields.io/badge/php-%3E%3D8.2-brightgreen" alt="">
<img src="https://img.shields.io/badge/license-apache%202-blue" alt="">
</p>

一款支持swoole, workerman, FPM环境的组件化的轻量`PHP`框架

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
php bin/cli-server.php           // 内置服务
php bin/swoole.php               // swoole
php bin/workerman.php            // workerman
```

> FPM模式，将请求指向public/index.php即可

## 区别

使用swoole/workerman/amp/react等服务支持注解、AOP等特性， FPM模式可以直接卸载AOP包。

## 简单入门

### 路由定义

> 路由定义在 `app/router.php` 文件中，也可以使用注解定义（需要安装AOP包，且不支持FPM/内置服务）

下面是在`app/router.php`中定义的路由

```php
$router->middleware(new SessionMiddleware(), new VerifyCSRFToken())
   ->group(function (Router $router) {
       $router->get('/', [new IndexController(), 'index']);
       $router->get('openapi', [new IndexController(), 'opanapi']);
   });
```

当你使用swoole/swoole/workerman/amp/reactphp并且使用了AOP就可以使用注解定义

```php
<?php

namespace App\Controllers;

use App\Http\Response;use Next\Routing\Attribute\Controller;use Next\Routing\Attribute\GetMapping;use Psr\Http\Message\ResponseInterface;use Psr\Http\Message\ServerRequestInterface;

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

### 其他文档

其他文档参考相应包的`README`

## 参与开发

欢迎有兴趣的朋友参与开发
