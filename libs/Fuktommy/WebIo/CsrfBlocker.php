<?php
/* CSRF Blocker.
 *
 * Copyright (c) 2012 Satoshi Fukutomi <info@fuktommy.com>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHORS AND CONTRIBUTORS ``AS IS'' AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHORS OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
 * OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
 * OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
 * SUCH DAMAGE.
 */
namespace Fuktommy\WebIo;

/**
 * CSRF Blocker.
 * @package Fuktommy\WebIo
 */
class CsrfBlocker
{
    /**
     * @var string
     */
    private $cookieTokenKey;

    /**
     * @var string
     */
    private $postTokenKey;

    /**
     * @param string $postTokenKey
     * @param string $cookieTokenKey
     */
    public function __construct($postTokenKey = 'token', $cookieTokenKey = 'token')
    {
        $this->postTokenKey = $postTokenKey;
        $this->cookieTokenKey = $cookieTokenKey;
    }

    /**
     * Accept access.
     * @param \Fuktommy\WebIo\Context $context
     * @return bool
     */
    public function accept(Context $context)
    {
        $referer = $context->get('server', 'HTTP_REFERER');
        if ($referer) {
            $host = $context->get('server', 'HTTP_HOST');
            $refererHost = parse_url($referer, PHP_URL_HOST);
            return $host === $refererHost;
        }

        $cookieToken = $context->get('cookie', $this->cookieTokenKey);
        if ($cookieToken) {
            $postToken = $context->get('post', $this->postTokenKey);
            return $cookieToken === $postToken;
        }

        return false;
    }

    /**
     * Set tokens.
     * @param \Fuktommy\WebIo\Context $context
     * @param \Smarty $smarty
     */
    public function setTokens(Context $context, $smarty)
    {
        $token = $context->get('cookie', $this->cookieTokenKey);
        if (! $token) {
            $token = sha1(implode(':', array(mt_rand(), mt_rand(), mt_rand(), mt_rand())));
        }
        $context->setCookie($this->cookieTokenKey, $token);
        $smarty->assign($this->postTokenKey, $token);
    }
}
