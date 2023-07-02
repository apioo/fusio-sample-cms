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
 * Action which updates a page. Similar to the create action it only invokes the page service to update a specific page
 */
class Update implements ActionInterface
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
            $id = $this->pageService->update(
                (int) $request->get('page_id'),
                $request->getPayload()
            );

            $message = new Message();
            $message->setSuccess(true);
            $message->setMessage('Page successful updated');
            $message->setId($id);
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(200, [], $message);
    }
}
