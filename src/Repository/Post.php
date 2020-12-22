<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

/**
 * Repository which handles all database operations regarding a post
 */
class Post
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id)
    {
        return $this->connection->fetchAssoc('SELECT id, title, summary, content, insert_date FROM app_post WHERE id = :id', [
            'id' => $id,
        ]);
    }

    public function insert(int $refId, int $userId, string $title, string $summary, string $content): int
    {
        $this->connection->insert('app_post', [
            'ref_id' => $refId,
            'user_id' => $userId,
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
            'insert_date' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, int $refId, string $title, string $summary, string $content): int
    {
        $this->connection->update('app_post', [
            'ref_id' => $refId,
            'title' => $title,
            'summary' => $summary,
            'content' => $content,
        ], [
            'id' => $id
        ]);

        return $id;
    }

    public function delete(int $id): int
    {
        $this->connection->delete('app_post', [
            'id' => $id
        ]);

        return $id;
    }
}
