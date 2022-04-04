<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>路由列表</title>
</head>
<body>
<table border="1px">
    <tr>
        <th>请求方法</th>
        <th>路径</th>
        <th>方法</th>
        <th>中间件</th>
        <th>域名</th>
    </tr>
    @foreach($routes as $route)
        <tr>
            <td>{{implode('|', $route->getMethods())}}</td>
            <td>{{$route->getPath()}}</td>
            <td>{{($route->getAction() instanceof Closure) ? 'Closure': $route->getAction()}}</td>
            <td>{{implode('|', array_merge(make(\Psr\Http\Server\RequestHandlerInterface::class)->getMiddlewares(),$route->getMiddlewares()))}}</td>
            <td>{{$route->getDomain()}}</td>
        </tr>
    @endforeach
</table>
</body>
</html>
