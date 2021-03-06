<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2016 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Tests;

use Fusio\Impl\Controller\SchemaApiController;
use Fusio\Impl\Framework\Loader\RoutingParser\DatabaseParser;
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
        return Fixture::getFixture()->toArray();
    }

    protected function setUp(): void
    {
        parent::setUp();

        // set debug mode to false
        Environment::getService('config')->set('psx_debug', false);

        // run deploy
        /** @var Deploy $deploy */
        $deploy = Environment::getService('system_deploy_service');
        $deploy->deploy(file_get_contents(__DIR__ . '/../.fusio.yml'), __DIR__ . '/..');

        // clear all cached routes after deployment since the deploy adds new
        // routes which are not in the database
        $routingParser = Environment::getService('routing_parser');
        if ($routingParser instanceof DatabaseParser) {
            $routingParser->clear();
        }

        // add scope for all routes
        $scopeId = Fixture::getFixture()->getId('fusio_scope', 'testing');
        $routes = $this->connection->fetchAll('SELECT id FROM fusio_routes WHERE category_id = :category', ['category' => 1]);
        foreach ($routes as $route) {
            $this->connection->insert('fusio_scope_routes', [
                'scope_id' => $scopeId,
                'route_id' => $route['id'],
                'allow'    => 1,
                'methods'  => 'GET|POST|PUT|DELETE',
            ]);
        }
    }
}
