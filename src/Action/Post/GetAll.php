<?php

namespace App\Action\Post;

use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Condition;
use App\View;

/**
 * Action which returns a collection response of all posts. It shows how to build complex nested JSON structures based
 * on SQL queries
 */
class GetAll implements ActionInterface
{
    private View\Page $view;

    public function __construct(View\Page $view)
    {
        $this->view = $view;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        return $this->view->getCollection(
            (int) $request->get('refId'),
            (int) $request->get('startIndex'),
            (int) $request->get('count'),
            $request->get('search'),
        );
    }
}
