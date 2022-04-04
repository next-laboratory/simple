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

namespace App\Jobs;

use Max\Queue\Jobs\Job;

class SendMail extends Job
{
    public function handle()
    {
        echo 'test';
    }
}
