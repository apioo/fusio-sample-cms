<?php

namespace App\Tests\Api\Post;

use App\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

class CollectionTest extends ApiTestCase
{
    public function testGet()
    {
        $response = $this->sendRequest('/post?refId=2', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/collection_get.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPost()
    {
        $body = json_encode(['refId' => 2, 'title' => 'foo', 'summary' => 'foo', 'content' => 'bar']);
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

        $actual = $this->connection->fetchAssociative('SELECT title, summary, content FROM app_post WHERE id = :id', ['id' => 2]);
        $expect = [
            'title' => 'foo',
            'summary' => 'foo',
            'content' => 'bar',
        ];

        $this->assertEquals($expect, $actual);
    }

    public function testPostInvalidPayload()
    {
        $body = json_encode(['foo' => 'foo']);
        $response = $this->sendRequest('/post', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ], $body);

        $body = (string) $response->getBody();
        $data = \json_decode($body);

        $this->assertEquals(400, $response->getStatusCode(), $body);
        $this->assertFalse($data->success, $body);
        $this->assertStringStartsWith('No ref provided', $data->message, $body);
    }
}
