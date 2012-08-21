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
use Fuktommy\WebIo;


/**
 * Index Page
 */
class IndexAction implements WebIo\Action
{
    /**
     * Execute
     * @param Fuktommy\WebIo\Context $context
     */
    public function execute(WebIo\Context $context)
    {
        $nickname = $context->get('get', 'nickname');
        if (empty($nickname)) {
            $nickname = $context->get('cookie', 'nickname', '');
        }

        if (empty($nickname)) {
            $items = array();
        } else {
            $todolist = new TodoList($context->getResource());
            $items = $todolist->getItemsByNickname($nickname);
        }

        $smarty = $context->getSmarty();
        $smarty->assign('config', $context->config);
        $smarty->assign('nickname', $nickname);
        $smarty->assign('items', $items);
        $smarty->display('index.tpl');
    }
}


Controller::factory()->run(new IndexAction(), Bootstrap::getContext());
