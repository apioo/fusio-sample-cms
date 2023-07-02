<?php

namespace App\Tests\Api\Page;

use App\Tests\ApiTestCase;
use http\Env;
use PSX\Framework\Test\Environment;

class CollectionTest extends ApiTestCase
{
    public function testGet()
    {
        $response = $this->sendRequest('/page', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/collection_get.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPost()
    {
        $body     = json_encode(['refId' => 2, 'title' => 'foo', 'content' => 'foobar']);
        $response = $this->sendRequest('/page', 'POST', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Page successful created",
    "id": 5
}
JSON;

        $this->assertEquals(201, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        $actual = $this->connection->fetchAssociative('SELECT title, content FROM app_page WHERE id = :id', ['id' => 5]);
        $expect = [
            'title' => 'foo',
            'content' => 'foobar',
        ];

        $this->assertEquals($expect, $actual);
    }

    public function testPostInvalidPayload()
    {
        $body     = json_encode(['foo' => 'foo']);
        $response = $this->sendRequest('/page', 'POST', [
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
