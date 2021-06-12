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

namespace App\Tests\Api\Post;

use App\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

/**
 * CollectionTest
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class CollectionTest extends ApiTestCase
{
    public function testDocumentation()
    {
        $response = $this->sendRequest('/system/doc/*/post', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/collection.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testGet()
    {
        $response = $this->sendRequest('/post', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/collection_get.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPost()
    {
        $body     = json_encode(['refId' => 0, 'title' => 'foo', 'summary' => 'foo', 'content' => 'bar']);
        $response = $this->sendRequest('/post', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Post successful created",
    "id": 2
}
JSON;

        $this->assertEquals(201, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = Environment::getService('connector')->getConnection('System');
        $actual = $connection->fetchAssoc('SELECT title, summary, content FROM app_post WHERE id = :id', ['id' => 2]);
        $expect = [
            'title' => 'foo',
            'summary' => 'foo',
            'content' => 'bar',
        ];

        $this->assertEquals($expect, $actual);
    }

    public function testPostInvalidPayload()
    {
        $body     = json_encode(['foo' => 'foo']);
        $response = $this->sendRequest('/post', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": false,
    "title": "Internal Server Error",
    "message": "No ref provided"
}
JSON;

        $this->assertEquals(400, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPut()
    {
        $response = $this->sendRequest('/post', 'PUT', [
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

    public function testDelete()
    {
        $response = $this->sendRequest('/post', 'DELETE', [
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

    private function getBlocks(): array
    {
        $blocks = [];
        $blocks[] = [
            'type' => 'headline',
            'content' => 'Foobar',
        ];
        $blocks[] = [
            'type' => 'paragraph',
            'content' => 'Lorem ipsum',
        ];

        return $blocks;
    }
}
