<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2020 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Tests\Api\Comment;

use App\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

/**
 * EntityTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class EntityTest extends ApiTestCase
{
    public function testDocumentation()
    {
        $response = $this->sendRequest('/system/doc/*/comment/1', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/entity.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGet()
    {
        $response = $this->sendRequest('/comment/1', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/entity_get.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPost()
    {
        $response = $this->sendRequest('/comment/1', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": false,
    "title": "Internal Server Error",
    "message": "Given request method is not supported"
}
JSON;

        $this->assertEquals(405, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPut()
    {
        $body     = json_encode(['refId' => 4, 'content' => 'bar']);
        $response = $this->sendRequest('/comment/1', 'PUT', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Comment successful updated",
    "id": 1
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = Environment::getService('connector')->getConnection('System');
        $actual = $connection->fetchAssoc('SELECT ref_id, content FROM app_comment WHERE id = :id', ['id' => 1]);
        $expect = [
            'ref_id' => 4,
            'content' => 'bar',
        ];

        $this->assertEquals($expect, $actual);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('/comment/1', 'DELETE', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ]);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Comment successful deleted",
    "id": 1
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = Environment::getService('connector')->getConnection('System');
        $actual = $connection->fetchAssoc('SELECT id, content FROM app_comment WHERE id = 1');
        $expect = null;

        $this->assertEquals($expect, $actual);
    }
}
