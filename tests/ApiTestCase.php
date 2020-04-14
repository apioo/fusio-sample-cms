<?php

namespace App\Tests;

use Fusio\Impl\Controller\SchemaApiController;
use Fusio\Impl\Loader\DatabaseRoutes;
use Fusio\Impl\Service\System\Deploy;
use PSX\Framework\Test\ControllerDbTestCase;
use PSX\Framework\Test\Environment;

/**
 * ApiTestCase
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
abstract class ApiTestCase extends ControllerDbTestCase
{
    public function getDataSet()
    {
        return Fixture::getFixture();
    }

    protected function setUp()
    {
        parent::setUp();

        // set debug mode to false
        Environment::getService('config')->set('psx_debug', false);

        // run deploy
        /** @var Deploy $deploy */
        $deploy = Environment::getService('system_deploy_service');
        $deploy->deploy(file_get_contents(__DIR__ . '/.fusio.yml'), __DIR__ . '/..');

        // clear all cached routes after deployment since the deploy adds new
        // routes which are not in the database
        $routingParser = Environment::getService('routing_parser');
        if ($routingParser instanceof DatabaseRoutes) {
            $routingParser->clear();
        }

        // after the deployment we must assign all routes to the scope from our
        // test token so that we can access every endpoint in the tests
        $routes = $this->connection->fetchAll('SELECT * FROM fusio_routes WHERE controller = :controller', ['controller' => SchemaApiController::class]);
        foreach ($routes as $route) {
            $this->connection->insert('fusio_scope_routes', [
                'scope_id' => 4,
                'route_id' => $route['id'],
                'allow'    => 1,
                'methods'  => 'GET|POST|PUT|DELETE',
            ]);
        }
    }
}
