<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230701193621 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $commentTable = $schema->createTable('app_comment');
        $commentTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $commentTable->addColumn('ref_id', 'integer', ['notnull' => false]);
        $commentTable->addColumn('user_id', 'integer', ['notnull' => false]);
        $commentTable->addColumn('content', 'string');
        $commentTable->addColumn('insert_date', 'datetime');
        $commentTable->setPrimaryKey(['id']);

        $pageTable = $schema->createTable('app_page');
        $pageTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $pageTable->addColumn('ref_id', 'integer', ['notnull' => false]);
        $pageTable->addColumn('user_id', 'integer', ['notnull' => false]);
        $pageTable->addColumn('title', 'string');
        $pageTable->addColumn('content', 'text');
        $pageTable->addColumn('insert_date', 'datetime');
        $pageTable->setPrimaryKey(['id']);

        $postTable = $schema->createTable('app_post');
        $postTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $postTable->addColumn('ref_id', 'integer', ['notnull' => false]);
        $postTable->addColumn('user_id', 'integer', ['notnull' => false]);
        $postTable->addColumn('title', 'string');
        $postTable->addColumn('summary', 'text');
        $postTable->addColumn('content', 'text');
        $postTable->addColumn('insert_date', 'datetime');
        $postTable->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
    }

    public function postUp(Schema $schema): void
    {
        $date = new \DateTime();

        $pages = [
            [0, 1, 'Home', 'This is a home page', $date->format('Y-m-d H:i:s')],
            [0, 1, 'Blog', 'This is my super blog page', $date->format('Y-m-d H:i:s')],
            [0, 1, 'About', 'This is an about page', $date->format('Y-m-d H:i:s')],
        ];

        foreach ($pages as $row) {
            $this->addSql('INSERT INTO app_page (ref_id, user_id, title, content, insert_date) VALUES (?, ?, ?, ?, ?)', $row);
        }

        $posts = [
            [2, 1, 'My Post', 'Lorem ipsum', 'Lorem ipsum', $date->format('Y-m-d H:i:s')],
        ];

        foreach ($posts as $row) {
            $this->addSql('INSERT INTO app_post (ref_id, user_id, title, summary, content, insert_date) VALUES (?, ?, ?, ?, ?, ?)', $row);
        }

        $comments = [
            [1, 1, 'My comment', $date->format('Y-m-d H:i:s')],
        ];

        foreach ($comments as $row) {
            $this->addSql('INSERT INTO app_comment (ref_id, user_id, content, insert_date) VALUES (?, ?, ?, ?)', $row);
        }
    }
}
