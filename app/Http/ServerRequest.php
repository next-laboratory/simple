<?php

declare(strict_types=1);

/**
 * This file is part of MaxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use Max\Http\Message\Contract\HeaderInterface;
use Max\Http\Server\ServerRequest as PsrServerRequest;
use Max\Session\Session;
use RuntimeException;

class ServerRequest extends PsrServerRequest
{
    public function session(): ?Session
    {
        if ($session = $this->getAttribute('Max\Session\Session')) {
            return $session;
        }
        throw new RuntimeException('Session is not started');
    }

    /**
     * 获取客户端真实IP.
     */
    public function getRealIp(): string
    {
        if ($xForwardedFor = $this->getHeaderLine(HeaderInterface::HEADER_X_FORWARDED_FOR)) {
            $ips = explode(',', $xForwardedFor);
            return trim($ips[0]);
        }
        if ($xRealIp = $this->getHeaderLine('X-Real-IP')) {
            return $xRealIp;
        }
        $serverParams = $this->getServerParams();
        return $serverParams['remote_addr'] ?? '127.0.0.1';
    }

    public function isPjax(bool $pjax = false, string $headerName = 'X-Pjax', string $pjaxVar = '_pjax'): bool
    {
        $headerExists = (bool)$this->getHeaderLine($headerName);
        if ($headerExists === $pjax) {
            return $headerExists;
        }

        return $this->query($pjaxVar) ? true : $headerExists;
    }

    public function isMobile(): bool
    {
        return (($userAgent = $this->getHeaderLine('User-Agent')) && preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $userAgent))
            || ($via = $this->getHeaderLine('Via')) && stristr($via, 'wap')
            || (($accept = $this->getHeaderLine('Accept')) && strpos(strtoupper($accept), 'VND.WAP.WML'))
            || (($this->getHeaderLine('X-Wap-Profile') && $this->getHeaderLine('Profile')));
    }

    public function isSecure($httpsAgentName = ''): bool
    {
        if ($this->getServer('HTTPS') && ('1' == $this->getServer('HTTPS') || 'on' == strtolower($this->getServer('HTTPS')))) {
            return true;
        }
        if ('https' == $this->getServer('REQUEST_SCHEME')) {
            return true;
        }
        if ('443' == $this->getServer('SERVER_PORT')) {
            return true;
        }
        if ('https' == $this->getHeaderLine('HTTP_X_FORWARDED_PROTO')) {
            return true;
        }
        if ($httpsAgentName && $this->getServer($httpsAgentName)) {
            return true;
        }

        return false;
    }
}
