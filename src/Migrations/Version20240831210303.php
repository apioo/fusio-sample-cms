<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831210303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
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

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }

    public function isTransactional(): bool
    {
        return false;
    }
}
