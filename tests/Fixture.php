<?php

namespace App\Tests;

use Fusio\Impl\Installation\DataBag;
use Fusio\Impl\Installation\NewInstallation;

class Fixture
{
    public const TEST_USERNAME = 'test';
    public const TEST_PASSWORD = 'test1234';

    protected static ?DataBag $dataSet = null;

    /**
     * Appends the default Fusio system inserts, through this it is i.e.
     * possible to add test users or apps which are required for your API. The
     * test token needs the required scopes to access your endpoints
     * 
     * @throws \Exception
     */
    public static function append(DataBag $dataBag): void
    {
        $expire = new \DateTime();
        $expire->add(new \DateInterval('P1M'));

        $dataBag->addUser('Administrator', self::TEST_USERNAME, 'test@test.com', password_hash(self::TEST_PASSWORD, PASSWORD_DEFAULT));
        $dataBag->addScope('default', 'comment', 'Comment scope');
        $dataBag->addScope('default', 'page', 'Page scope');
        $dataBag->addScope('default', 'post', 'Post scope');
        $dataBag->addScope('default', 'testing', 'Test scope');
        $dataBag->addUserScope('test', 'backend');
        $dataBag->addUserScope('test', 'consumer');
        $dataBag->addUserScope('test', 'comment');
        $dataBag->addUserScope('test', 'page');
        $dataBag->addUserScope('test', 'post');
        $dataBag->addUserScope('test', 'testing');
        $dataBag->addEvent('default', 'comment.created', '');
        $dataBag->addEvent('default', 'comment.updated', '');
        $dataBag->addEvent('default', 'comment.deleted', '');
        $dataBag->addEvent('default', 'page.created', '');
        $dataBag->addEvent('default', 'page.updated', '');
        $dataBag->addEvent('default', 'page.deleted', '');
        $dataBag->addEvent('default', 'post.created', '');
        $dataBag->addEvent('default', 'post.updated', '');
        $dataBag->addEvent('default', 'post.deleted', '');

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

    public static function getFixture(): DataBag
    {
        if (self::$dataSet !== null) {
            return self::$dataSet;
        }

        $dataBag = NewInstallation::getData();

        self::append($dataBag);

        return self::$dataSet = $dataBag;
    }
}

