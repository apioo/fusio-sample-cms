<?php

namespace App\Action\Page;

use App\View;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;

/**
 * Action which returns all details for a single page
 */
class Get implements ActionInterface
{
    private View\Page $view;

    public function __construct(View\Page $view)
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
