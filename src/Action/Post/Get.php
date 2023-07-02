<?php

namespace App\Action\Post;

use App\View;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

/**
 * Action which returns all details for a single post
 */
class Get implements ActionInterface
{
    private View\Post $view;

    public function __construct(View\Post $view)
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
