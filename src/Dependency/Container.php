<?php

namespace App\Dependency;

use App\Service\Page;
use App\Service\Post;
use Fusio\Impl\Dependency\Container as FusioContainer;

/**
 * Custom dependency container. We can create a new service by simply defining
 * a new method which returns the service. Those services can be injected at
 * constructor argument at any action. Therefor you only need to specify the
 * fitting type-hint and the DIC injects the fitting service
 */
class Container extends FusioContainer
{
    public function getPageService(): Page
    {
        return new Page(
            $this->get('connector')->getConnection('System'),
            $this->get('engine_dispatcher')
        );
    }

    public function getPostService(): Post
    {
        return new Post(
            $this->get('connector')->getConnection('System'),
            $this->get('engine_dispatcher')
        );
    }
}
