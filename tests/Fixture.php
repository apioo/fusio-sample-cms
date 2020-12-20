<?php

namespace App\Tests;

use Fusio\Impl\Connection\Native;
use Fusio\Impl\Migrations\DataBag;
use Fusio\Impl\Migrations\NewInstallation;
use PSX\Framework\Test\Environment;

/**
 * Fixture
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Fixture
{
    protected static $dataSet;

    /**
     * Returns the default Fusio system inserts, through this it is i.e.
     * possible to add test users or apps which are required for your API. The
     * test token needs the required scopes to access your endpoints
     * 
     * @return array
     * @throws \Exception
     */
    public static function append(DataBag $dataBag): void
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P1M'));

        $scopes = ['testing'];

        $dataBag->addScope('default', 'testing', 'Test scope');
        $dataBag->addAppScope('Backend', 'testing');
        $dataBag->addAppToken('Backend', 'Administrator', 'da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf', '', implode(',', $scopes), $expire->format('Y-m-d H:i:s'), '2015-06-25 22:49:09');
        $dataBag->addUserScope('Administrator', 'testing');
        $dataBag->addEvent('default', 'page_created', '');
        $dataBag->addEvent('default', 'page_updated', '');
        $dataBag->addEvent('default', 'page_deleted', '');
        $dataBag->addEvent('default', 'post_created', '');
        $dataBag->addEvent('default', 'post_updated', '');
        $dataBag->addEvent('default', 'post_deleted', '');

        $inserts = self::getDemoInserts();
        foreach ($inserts as $tableName => $rows) {
            $dataBag->addTable($tableName, $rows);
        }
    }

    /**
     * Returns the demo inserts for your app specific tables. In this case we
     * simply add entries to the app_todo table
     * 
     * @return array
     * @throws \Exception
     */
    public static function getDemoInserts()
    {
        $pages = [
            ['parent_id' => null, 'user_id' => 1, 'sort' => 0, 'title' => 'Home', 'data' => \json_encode([['type' => 'headline', 'content' => 'Home'], ['type' => 'paragraph', 'content' => '<p>Hello World!</p>']]), 'insert_date' => '2020-04-09 19:49:00'],
            ['parent_id' => null, 'user_id' => 1, 'sort' => 1, 'title' => 'Blog', 'data' => \json_encode([['type' => 'headline', 'content' => 'Blog'], ['type' => 'post']]), 'insert_date' => '2020-04-09 19:49:00'],
            ['parent_id' => null, 'user_id' => 1, 'sort' => 2, 'title' => 'About', 'data' => \json_encode([['type' => 'headline', 'content' => 'About'], ['type' => 'paragraph', 'content' => '<p>About page</p>']]), 'insert_date' => '2020-04-09 19:49:00'],
            ['parent_id' => 2,    'user_id' => 1, 'sort' => 0, 'title' => 'Imprint', 'data' => \json_encode([['type' => 'headline', 'content' => 'Imprint'], ['type' => 'paragraph', 'content' => '<p>Imprint page</p>']]), 'insert_date' => '2020-04-09 19:49:00'],
        ];

        $posts = [
            ['page_id' => 2, 'user_id' => 1, 'title' => 'My Post', 'summary' => 'Lorem ipsum', 'content' => 'Lorem ipsum', 'insert_date' => '2020-04-09 19:49:00'],
        ];

        return [
            'app_page' => $pages,
            'app_post' => $posts,
        ];
    }

    public static function getFixture()
    {
        if (self::$dataSet !== null) {
            return self::$dataSet;
        }

        $dataBag = NewInstallation::getData();

        // replace System connection class
        $dataBag->replace('fusio_connection', 'System', 'class', Native::class);

        self::append($dataBag);

        return self::$dataSet = $dataBag;
    }
}

