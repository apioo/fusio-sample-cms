<?php

namespace App\Tests\Api\Comment;

use App\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

class CollectionTest extends ApiTestCase
{
    public function testGet()
    {
        $response = $this->sendRequest('/comment?refId=2', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/collection_get.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPost()
    {
        $body = json_encode(['refId' => 2, 'content' => 'bar']);
        $response = $this->sendRequest('/comment', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Comment successful created",
    "id": 2
}
JSON;

        $this->assertEquals(201, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        $actual = $this->connection->fetchAssociative('SELECT content FROM app_comment WHERE id = :id', ['id' => 2]);
        $expect = [
            'content' => 'bar',
        ];

        $this->assertEquals($expect, $actual);
    }

    public function testPostInvalidPayload()
    {
        $body     = json_encode(['foo' => 'foo']);
        $response = $this->sendRequest('/comment', 'POST', [
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
