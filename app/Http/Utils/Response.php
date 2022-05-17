<?php

namespace App\Http\Utils;

use Max\Context\Context;
use Max\Http\Message\Stream\StringStream;
use Max\Swoole\Http\Response as HttpResponse;
use Psr\Http\Message\ResponseInterface;

class Response extends HttpResponse
{
    /**
     * @param $data
     *
     * @return ResponseInterface
     */
    public function json($data): ResponseInterface
    {
        return $this->withHeader('Content-Type', 'application/json; charset=utf-8')
                    ->withBody(new StringStream(json_encode($data)));
    }

    /**
     * @param string $html
     *
     * @return ResponseInterface
     */
    public function html(string $html): ResponseInterface
    {
        return $this->withHeader('Content-Type', 'text/html; charset=utf-8')
                    ->withBody(new StringStream($html));
    }

    /**
     * @param        $data
     * @param string $message
     * @param int    $code
     *
     * @return array
     */
    public function success($data = [], string $message = '请求成功', int $code = 200)
    {
        return [
            'status'  => true,
            'code'    => $code,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param string $message
     * @param int    $code
     * @param array  $data
     *
     * @return array
     */
    public function error(string $message = '请求失败', int $code = 400, array $data = [])
    {
        return [
            'status'  => false,
            'code'    => $code,
            'data'    => $data,
            'message' => $message,
        ];
    }

    /**
     * @param ResponseInterface $response
     */
    public function setPsr7(ResponseInterface $response)
    {
        Context::put(ResponseInterface::class, $response);
    }
}
