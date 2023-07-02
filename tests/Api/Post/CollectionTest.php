<?php

namespace App\Tests\Api\Post;

use App\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

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
