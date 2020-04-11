<?php declare(strict_types=1);

namespace App\Migrations\System;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200328195520 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $date = new \DateTime();

        $pages = [
            [null, 1, 0, 'Home', \json_encode([['type' => 'headline', 'content' => 'Home'], ['type' => 'paragraph', 'content' => '<p>Hello World!</p>']]), $date->format('Y-m-d H:i:s')],
            [null, 1, 1, 'Blog', \json_encode([['type' => 'headline', 'content' => 'Blog'], ['type' => 'post']]), $date->format('Y-m-d H:i:s')],
            [null, 1, 2, 'About', \json_encode([['type' => 'headline', 'content' => 'About'], ['type' => 'paragraph', 'content' => '<p>About page</p>']]), $date->format('Y-m-d H:i:s')],
            [2, 1, 0, 'Imprint', \json_encode([['type' => 'headline', 'content' => 'Imprint'], ['type' => 'paragraph', 'content' => '<p>Imprint page</p>']]), $date->format('Y-m-d H:i:s')],
        ];

        foreach ($pages as $row) {
            $this->addSql('INSERT INTO app_page (parent_id, user_id, sort, title, data, insert_date) VALUES (?, ?, ?, ?, ?, ?)', $row);
        }

        $posts = [
            [2, 1, 'My Post', 'Lorem ipsum', 'Lorem ipsum', $date->format('Y-m-d H:i:s')],
        ];

        foreach ($posts as $row) {
            $this->addSql('INSERT INTO app_post (page_id, user_id, title, summary, content, insert_date) VALUES (?, ?, ?, ?, ?, ?)', $row);
        }
    }

    public function down(Schema $schema) : void
    {
    }
}
