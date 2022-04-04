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

namespace App\Console\Commands;

use Max\Console\Commands\Command;

class Swagger extends Command
{
    /**
     * @var string
     */
    protected string $name = 'swagger';

    /**
     * @var string
     */
    protected string $description = 'Generate a swagger doc.';

    /**
     * @var array|string[]
     */
    protected array $scanDir = [
        BASE_PATH . 'app/Http/Controllers'
    ];

    /**
     * run
     */
    public function run()
    {
        $version = $this->input->getOption('-v');
        $swagger = new \Max\Swagger\Swagger($this->scanDir, $version, BASE_PATH . 'swagger.json');
        $swagger->generateJson();
    }
}
