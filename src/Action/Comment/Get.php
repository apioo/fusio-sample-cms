<?php

namespace App\Action\Comment;

use App\View;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

/**
 * Action which returns a specific comment
 */
class Get implements ActionInterface
{
    private View\Comment $view;

    public function __construct(View\Comment $view)
    {
        $this->view = $view;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        return $this->view->getEntity(
            (int) $request->get('id')
        );
    }
}
