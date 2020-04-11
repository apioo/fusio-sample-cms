<?php

namespace App\Dependency;

use App\Service\Page;
use App\Service\Post;
use Fusio\Impl\Dependency\Container as FusioContainer;

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
