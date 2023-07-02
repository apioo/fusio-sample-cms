<?php

namespace App\Action\Page;

use App\Model\Message;
use App\Service\Page;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Response\FactoryInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which deletes a page. Similar to the "create" action it only invokes the page service to delete a specific page
 */
class Delete implements ActionInterface
{
    private Page $pageService;
    private FactoryInterface $response;

    public function __construct(Page $pageService, FactoryInterface $response)
    {
        $this->pageService = $pageService;
        $this->response = $response;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        try {
            $id = $this->pageService->delete(
                (int) $request->get('page_id')
            );

            $message = new Message();
            $message->setSuccess(true);
            $message->setMessage('Page successful deleted');
            $message->setId($id);
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(200, [], $message);
    }
}
