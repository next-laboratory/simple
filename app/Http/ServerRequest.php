<?php

declare(strict_types=1);

/**
 * This file is part of MarxPHP.
 *
 * @link     https://github.com/marxphp
 * @license  https://github.com/marxphp/max/blob/master/LICENSE
 */

namespace App\Http;

use Max\Http\Message\ServerRequest as PsrServerRequest;
use Max\Session\Session;

class ServerRequest extends PsrServerRequest
{
    public function session(): ?Session
    {
        if ($session = $this->getAttribute('Max\Session\Session')) {
            return $session;
        }
        throw new \RuntimeException('Session is not started');
    }

    /**
     * 获取客户端真实IP.
     */
    public function getRealIp(): string
    {
        if ($xForwardedFor = $this->getHeaderLine('X-Forwarded-For')) {
            if ($ips = explode(', ', $xForwardedFor)) {
                return $ips[0];
            }
        }
        if ($xRealIp = $this->getHeaderLine('X-Real-IP')) {
            return $xRealIp;
        }
        $serverParams = $this->getServerParams();
        return $serverParams['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    public function isPjax(bool $pjax = false, string $headerName = 'X-Pjax', string $pjaxVar = '_pjax'): bool
    {
        $headerExists = (bool) $this->getHeaderLine($headerName);
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
            || ($this->getHeaderLine('X-Wap-Profile') && $this->getHeaderLine('Profile'));
    }

    public function isSecure($httpsAgentName = ''): bool
    {
        if ($this->getServer('HTTPS') && ($this->getServer('HTTPS') == '1' || strtolower($this->getServer('HTTPS')) == 'on')) {
            return true;
        }
        if ($this->getServer('REQUEST_SCHEME') == 'https') {
            return true;
        }
        if ($this->getServer('SERVER_PORT') == '443') {
            return true;
        }
        if ($this->getHeaderLine('HTTP_X_FORWARDED_PROTO') == 'https') {
            return true;
        }
        if ($httpsAgentName && $this->getServer($httpsAgentName)) {
            return true;
        }

        return false;
    }
}
