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
use Fuktommy\Db\Migration;

/**
 * Storage
 * @package Fuktommy\TodoRss
 */
class TodoList
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * Constructor.
     * @param \Fuktommy\WebIo\Resource $resource
     * @throws PDOException
     */
    public function __construct(\Fuktommy\WebIo\Resource $resource)
    {
        $this->db = $resource->getDb();
    }

    /**
     * Set up storage.
     *
     * Call it before append().
     */
    public function setUp()
    {
        $this->db->beginTransaction();
        $migration = new Migration($this->db);
        $migration->execute(
            "CREATE TABLE IF NOT EXISTS `todolist`"
            . " (`id` CHAR PRIMARY KEY NOT NULL,"
            . "  `nickname` CHAR NOT NULL,"
            . "  `date` CHAR NOT NULL,"
            . "  `body` TEXT NOT NULL)"
        );
        $migration->execute(
            "CREATE INDEX `nickname` ON `todolist` (`nickname`)"
        );
        $migration->execute(
            "CREATE INDEX `date` ON `todolist` (`date`)"
        );
    }

    /**
     * Select items by nickname.
     * @param string $nickname
     * @return array<Fuktommy\TodoRss\Item>
     * @throws PDOException
     */
    public function getItemsByNickname($nickname)
    {
        $state = $this->db->prepare(
            "SELECT `id`, `nickname`, `date`, `body` FROM `todolist`"
            . " WHERE `nickname` = :nickname"
            . " ORDER BY `date` DESC"
        );
        $state->execute(array('nickname' => $nickname));
        return array_map(function ($r) { return new Item($r); },
                         $state->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * Select entry by id.
     * @param string $id
     * @return array
     * @throws PDOException
     */
    public function getEntry($id)
    {
        $state = $this->db->prepare(
            "SELECT `id`, `nickname`, `date`, `body` FROM `todolist`"
            . " WHERE `id` = :id"
        );
        $state->execute(array('id' => $id));
        return array_map(function ($r) { return new Item($r); },
                         $state->fetchAll(\PDO::FETCH_ASSOC));
    }

    private function _timeFormat($unixtime)
    {
        return gmstrftime('%FT%TZ', $unixtime);
    }

    /**
     * Append the enrty to todo list.
     * @param string $nickname
     * @param string $body
     * @throws PDOException
     */
    public function append($nickname, $body)
    {
        $exception = null;
        for ($i = 0; $i < 10; $i++) {
            try {
                $this->_do_append($nickname, $body);
                return;
            } catch (PDOException $e) {
                // expect duplicated primary key
                $exception = $e;
            }
        }
        throw $exception;
    }

    /**
     * Append the enrty to todo list. Try one time.
     * @param string $nickname
     * @param string $body
     * @throws PDOException
     */
    public function _do_append($nickname, $body)
    {
        $str = sprintf("%s:%s:%s", mt_rand(), $nickname, $body);
        $id = strtr(base64_encode(sha1($str, true)),
                    array('+' => '-', '/' => '_', '=' => ''));

        $state = $this->db->prepare(
            "INSERT INTO `todolist`"
            . " (`id`, `nickname`, `date`, `body`)"
            . " VALUES (:id, :nickname, :date, :body)"
        );
        $state->execute(array(
            'id' => $id,
            'nickname' => $nickname,
            'date' => $this->_timeFormat(time()),
            'body' => $body,
        ));
    }

    /**
     * Remove old records.
     * @param int $threshold unixtime
     * @throws PDOException
     */
    public function removeOlderThan($threshold)
    {
        $state = $this->db->prepare(
            "DELETE FROM `todolist`"
            . " WHERE `date` <= :date"
        );
        $state->execute(array(
            'date' => $this->_timeFormat($threshold),
        ));
    }

    /**
     * Commit transaction.
     *
     * Call it after append()
     */
    public function commit()
    {
        $this->db->commit();
    }
}
