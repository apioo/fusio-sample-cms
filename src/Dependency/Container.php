<?php

namespace App\Dependency;

use App\Service;
use App\Repository;
use Fusio\Impl\Dependency\Container as FusioContainer;

/**
 * Custom dependency container. We can create a new service by simply defining a new method which returns the service.
 * Those services can be injected at constructor argument at any action. Therefore you only need to specify the fitting
 * type-hint and the DIC injects the fitting service
 */
class Container extends FusioContainer
{
    public function getCommentService(): Service\Comment
    {
        return new Service\Comment(
            $this->get('comment_repository'),
            $this->get('engine_dispatcher')
        );
    }

    public function getPageService(): Service\Page
    {
        return new Service\Page(
            $this->get('page_repository'),
            $this->get('engine_dispatcher')
        );
    }

    public function getPostService(): Service\Post
    {
        return new Service\Post(
            $this->get('post_repository'),
            $this->get('engine_dispatcher')
        );
    }

    public function getCommentRepository(): Repository\Comment
    {
        return new Repository\Comment(
            $this->get('connector')->getConnection('System')
        );
    }

    public function getPageRepository(): Repository\Page
    {
        return new Repository\Page(
            $this->get('connector')->getConnection('System')
        );
    }

    public function getPostRepository(): Repository\Post
    {
        return new Repository\Post(
            $this->get('connector')->getConnection('System')
        );
    }
}
