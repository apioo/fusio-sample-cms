<?php

namespace App\Tests;

use Fusio\Impl\Connection\Native;
use Fusio\Impl\Migrations\DataBag;
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
    public const TEST_USERNAME = 'test';
    public const TEST_PASSWORD = 'test1234';

    protected static $dataSet;

    /**
     * Appends the default Fusio system inserts, through this it is i.e.
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

        $dataBag->addUser('Administrator', self::TEST_USERNAME, 'test@test.com', password_hash(self::TEST_PASSWORD, PASSWORD_DEFAULT));
        $dataBag->addScope('default', 'testing', 'Test scope');
        $dataBag->addUserScope('test', 'backend');
        $dataBag->addUserScope('test', 'consumer');
        $dataBag->addUserScope('test', 'testing');
        $dataBag->addEvent('default', 'comment_created', '');
        $dataBag->addEvent('default', 'comment_updated', '');
        $dataBag->addEvent('default', 'comment_deleted', '');
        $dataBag->addEvent('default', 'page_created', '');
        $dataBag->addEvent('default', 'page_updated', '');
        $dataBag->addEvent('default', 'page_deleted', '');
        $dataBag->addEvent('default', 'post_created', '');
        $dataBag->addEvent('default', 'post_updated', '');
        $dataBag->addEvent('default', 'post_deleted', '');

        $dataBag->addTable('app_page', [
            ['ref_id' => 0, 'user_id' => 1, 'title' => 'Home', 'content' => 'Home page', 'insert_date' => '2020-04-09 19:49:00'],
            ['ref_id' => 0, 'user_id' => 1, 'title' => 'Blog', 'content' => 'Blog page', 'insert_date' => '2020-04-09 19:49:00'],
            ['ref_id' => 0, 'user_id' => 1, 'title' => 'About', 'content' => 'About page', 'insert_date' => '2020-04-09 19:49:00'],
            ['ref_id' => 2, 'user_id' => 1, 'title' => 'Imprint', 'content' => 'Imprint page', 'insert_date' => '2020-04-09 19:49:00'],
        ]);

        $dataBag->addTable('app_post', [
            ['ref_id' => 2, 'user_id' => 1, 'title' => 'My Post', 'summary' => 'Lorem ipsum', 'content' => 'Lorem ipsum', 'insert_date' => '2020-04-09 19:49:00'],
        ]);

        $dataBag->addTable('app_comment', [
            ['ref_id' => 2, 'user_id' => 1, 'content' => 'Lorem ipsum', 'insert_date' => '2020-04-09 19:49:00'],
        ]);
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

