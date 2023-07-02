<?php

namespace App\Tests\Api\Comment;

use App\Tests\ApiTestCase;
use PSX\Framework\Test\Environment;

class EntityTest extends ApiTestCase
{
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

        $actual = $this->connection->fetchAssociative('SELECT ref_id, content FROM app_comment WHERE id = :id', ['id' => 1]);
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

        $actual = $this->connection->fetchAssociative('SELECT id, content FROM app_comment WHERE id = 1');
        $expect = null;

        $this->assertEquals($expect, $actual);
    }
}
