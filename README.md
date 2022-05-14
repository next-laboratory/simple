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

一款基于`swoole`的组件化的轻量`PHP`框架，可以用作`API`开发，方便快速。框架默认安装了`session`和`view`扩展包，如果不需要可以直接移除。

## 主要特性

- 组件和框架核心分离
- 基于 Psr7 的 HTTP-Message
- 基于 Psr11 的容器
- 基于 Psr14 的事件
- 基于 Psr15 的中间件
- 基于 Psr16 的缓存组件，支持 File,Memcached,Redis,APC[可扩展]
- max/database 支持连接池
- AOP，支持路由功能，验证器，swagger，blade视图

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
composer create-project max/http-project:dev-master
```

### 启动服务

```shell
php bin/max swoole start
php bin/max swoole stop
```

这行命令会在你命令执行目录安装框架

> 欢迎有兴趣的朋友参与开发

<a href="https://www.1kmb.com/note/283.html">开发文档</a>

> 官网：https://www.1kmb.com
