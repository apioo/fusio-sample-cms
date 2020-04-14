<?php

namespace App\Tests;

use Fusio\Impl\Connection\Native;
use Fusio\Impl\Migrations\NewInstallation;

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
    public static function getSystemInserts()
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P1M'));

        $scopes = ['page', 'post'];

        return [
            'fusio_scope' => [
                ['name' => 'testing', 'description' => 'Test scope'],
            ],
            'fusio_app_scope' => [
                ['app_id' => 1, 'scope_id' => 4],
            ],
            'fusio_app_token' => [
                ['app_id' => 1, 'user_id' => 1, 'status' => 1, 'token' => 'da250526d583edabca8ac2f99e37ee39aa02a3c076c0edc6929095e20ca18dcf', 'scope' => implode(',', $scopes), 'ip' => '127.0.0.1', 'expire' => $expire->format('Y-m-d H:i:s'), 'date' => '2015-06-25 22:49:09'],
            ],
            'fusio_user_scope' => [
                ['user_id' => 1, 'scope_id' => 4],
            ],
            'fusio_event' => [
                ['status' => 1, 'name' => 'page_created', 'description' => '', ],
                ['status' => 1, 'name' => 'page_updated', 'description' => '', ],
                ['status' => 1, 'name' => 'page_deleted', 'description' => '', ],
                ['status' => 1, 'name' => 'post_created', 'description' => '', ],
                ['status' => 1, 'name' => 'post_updated', 'description' => '', ],
                ['status' => 1, 'name' => 'post_deleted', 'description' => '', ],
            ],
        ];
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

        $installInserts = NewInstallation::getData();

        // replace System connection class
        $installInserts['fusio_connection'][0]['class'] = Native::class;

        $dataSet = array_merge_recursive(
            $installInserts,
            self::getSystemInserts(),
            self::getDemoInserts()
        );

        return self::$dataSet = $dataSet;
    }
}

