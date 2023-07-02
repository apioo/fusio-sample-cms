<?php

namespace App\Tests\Api\Page;

use App\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

class EntityTest extends ApiTestCase
{
    public function testGet()
    {
        $response = $this->sendRequest('/page/2', 'GET', [
            'User-Agent'    => 'Fusio TestCase',
        ]);

        $actual = (string) $response->getBody();
        $expect = file_get_contents(__DIR__ . '/resource/entity_get.json');

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);
    }

    public function testPut()
    {
        $body     = json_encode(['refId' => 2, 'title' => 'foo', 'content' => 'barbar']);
        $response = $this->sendRequest('/page/2', 'PUT', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ], $body);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Page successful updated",
    "id": 2
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        $actual = $this->connection->fetchAssociative('SELECT ref_id, title, content FROM app_page WHERE id = :id', ['id' => 2]);
        $expect = [
            'ref_id' => 2,
            'title' => 'foo',
            'content' => 'barbar',
        ];

        $this->assertEquals($expect, $actual);
    }

    public function testDelete()
    {
        $response = $this->sendRequest('/page/2', 'DELETE', [
            'User-Agent'    => 'Fusio TestCase',
            'Authorization' => 'Bearer ' . $this->accessToken
        ]);

        $actual = (string) $response->getBody();
        $expect = <<<'JSON'
{
    "success": true,
    "message": "Page successful deleted",
    "id": 2
}
JSON;

        $this->assertEquals(200, $response->getStatusCode(), $actual);
        $this->assertJsonStringEqualsJsonString($expect, $actual, $actual);

        $actual = $this->connection->fetchAssociative('SELECT id, title FROM app_page WHERE id = 2');
        $expect = null;

        $this->assertEquals($expect, $actual);
    }
}
