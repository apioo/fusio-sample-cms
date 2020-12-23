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
}
