<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

/**
 * Repository which handles all database operations regarding a page
 */
class Page
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
        return $this->connection->fetchAssoc('SELECT id, title, content, insert_date FROM app_page WHERE id = :id', [
            'id' => $id,
        ]);
    }

    public function insert(int $refId, int $userId, string $title, string $content): int
    {
        $this->connection->insert('app_page', [
            'ref_id' => $refId,
            'user_id' => $userId,
            'title' => $title,
            'content' => $content,
            'insert_date' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, int $refId, string $title, string $content): int
    {
        $this->connection->update('app_page', [
            'ref_id' => $refId,
            'title' => $title,
            'content' => $content,
        ], [
            'id' => $id
        ]);

        return $id;
    }

    public function delete(int $id): int
    {
        $this->connection->delete('app_page', [
            'id' => $id
        ]);

        return $id;
    }
}
