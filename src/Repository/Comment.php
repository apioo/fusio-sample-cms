<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;

/**
 * Repository which handles all database operations regarding a comment
 */
class Comment
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
        return $this->connection->fetchAssoc('SELECT id, content, insert_date FROM app_comment WHERE id = :id', [
            'id' => $id,
        ]);
    }

    public function insert(int $refId, int $userId, string $content): int
    {
        $this->connection->insert('app_comment', [
            'ref_id' => $refId,
            'user_id' => $userId,
            'content' => $content,
            'insert_date' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, int $refId, string $content): int
    {
        $this->connection->update('app_comment', [
            'ref_id' => $refId,
            'content' => $content,
        ], [
            'id' => $id
        ]);

        return $id;
    }
    
    public function delete(int $id): int
    {
        $this->connection->delete('app_comment', [
            'id' => $id
        ]);

        return $id;
    }
}
