<?php
/*
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
namespace Fuktommy\TodoRss;

require_once __DIR__ . '/../libs/bootstrap.php';
use Fuktommy\TodoRss\Bootstrap;
use Fuktommy\PubSubHubbub;
use Fuktommy\WebIo;


/**
 * Add Action
 */
class AddAction implements WebIo\Action
{
    /**
     * Execute
     * @param Fuktommy\WebIo\Context $context
     */
    public function execute(WebIo\Context $context)
    {
        $nickname = $context->get('post', 'nickname', '');
        $nickname = preg_replace('/[^-_A-Za-z0-9]/', '_', $nickname);
        $body = $context->get('post', 'body');

        if (empty($nickname) || empty($body)
            || (! $context->isSameOriginReferer())) {
            $context->putHeader('400 Bad Request HTTP/1.0');
            return;
        }

        $address = $context->get('server', 'REMOTE_ADDR');
        $logBody = preg_replace('/\s+/', ' ', $body);
        $context->getLog('todo.rss')
                ->info("{$nickname} add {$logBody} from {$address}");

        $list = new TodoList($context->getResource());
        $list->setUp();
        $list->append($nickname, $body);
        $list->commit();

        $expire = time() + 365 * 24 * 60 * 60;
        $context->setCookie('nickname', $nickname, $expire);
        $context->putHeader('Location', '/');
    }
}


Controller::factory()->run(new AddAction(), Bootstrap::getContext());
