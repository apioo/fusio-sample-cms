<?php

namespace App\Action\Comment;

use App\View;
use Fusio\Engine\Action\RuntimeInterface;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Sql\Builder;
use PSX\Sql\Condition;

/**
 * Action which returns a collection response of all comments. It shows how to build complex nested JSON structures
 * based on SQL queries
 */
class GetAll implements ActionInterface
{
    private View\Comment $view;

    public function __construct(View\Comment $view)
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
