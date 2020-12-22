<?php

namespace App\Action\Page;

use App\Model\Message;
use App\Service\Page;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which creates a page. The business logic to create a page is contained at the page service
 * (s. Dependency\Container). Otherwise this action is only a gateway to your service and contains not much logic, so
 * that you can write your service as framework independent as possible. It is similar to a controller at a classical
 * framework. 
 */
class Create extends ActionAbstract
{
    /**
     * @var Page
     */
    private $pageService;

    public function __construct(Page $pageService)
    {
        $this->pageService = $pageService;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        try {
            $id = $this->pageService->create(
                $request->getPayload(),
                $context
            );

            $message = new Message();
            $message->setSuccess(true);
            $message->setMessage('Page successful created');
            $message->setId($id);
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $message);
    }
}
