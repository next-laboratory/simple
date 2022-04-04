<?php

declare(strict_types=1);
/**
 * This file is part of the Max package.
 *
 * (c) Cheng Yao <987861463@qq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Controllers;

use App\Http\AbstractController;
use Max\Foundation\Http\Annotations\Controller;
use Max\Foundation\Http\Annotations\GetMapping;

#[Controller(prefix: '/')]
class IndexController extends AbstractController
{
    /**
     * @return string
     */
    #[GetMapping(path: '/')]
    public function index(): string
    {
        return '<!DOCTYPE html><html lang="zh"><head><meta charset="UTF-8"><title>MaxPHP-组件化的轻量PHP框架！</title><meta name="viewport"content="width=device-width, initial-scale=1.0"></head><style>html,body{margin:0;padding:0;border:none;height:100%}.box{height:100%;display:flex;justify-content:center;align-items:center}.content{text-align:center;width:15em}.icon{width:8em;height:8em}.tip-box{display:flex;justify-content:space-between;margin-top:2em}</style><body><div class="box"><div class="content"><div class="icon"style="border-radius: 50%; color: white; background-color: #4F70F6; line-height: 8em; box-shadow: grey 0 0 5px 0; margin: 0 auto"><h1>Max</h1></div><h4>组件化的轻量PHP框架</h4><div class="tip-box"><a href="https://github.com/bugmi/max">Github</a><a href="https://packagist.org/packages/max">Packagist</a><a href="https://www.1kmb.com/note/283.html">文档</a></div></div></div></body></html>';
    }
}
