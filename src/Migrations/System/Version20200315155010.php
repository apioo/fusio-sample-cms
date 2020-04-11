<?php declare(strict_types=1);

namespace App\Migrations\System;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200315155010 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $pageTable = $schema->createTable('app_page');
        $pageTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $pageTable->addColumn('parent_id', 'integer', ['notnull' => false]);
        $pageTable->addColumn('user_id', 'integer', ['notnull' => false]);
        $pageTable->addColumn('status', 'integer', ['default' => 1]);
        $pageTable->addColumn('sort', 'integer', ['default' => 0]);
        $pageTable->addColumn('title', 'string');
        $pageTable->addColumn('data', 'json');
        $pageTable->addColumn('insert_date', 'datetime');
        $pageTable->setPrimaryKey(['id']);

        $postTable = $schema->createTable('app_post');
        $postTable->addColumn('id', 'integer', ['autoincrement' => true]);
        $postTable->addColumn('page_id', 'integer', ['notnull' => false]);
        $postTable->addColumn('user_id', 'integer', ['notnull' => false]);
        $postTable->addColumn('status', 'integer', ['default' => 1]);
        $postTable->addColumn('title', 'string');
        $postTable->addColumn('summary', 'text');
        $postTable->addColumn('content', 'text');
        $postTable->addColumn('insert_date', 'datetime');
        $postTable->setPrimaryKey(['id']);
    }

    public function down(Schema $schema) : void
    {
    }
}
