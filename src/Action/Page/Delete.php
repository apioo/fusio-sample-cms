<?php

namespace App\Action\Page;

use App\Service\Page;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which deletes a page. Similar to the create action it only invokes the
 * page service to delete a specific page
 */
class Delete extends ActionAbstract
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
            $id = (int) $request->getUriFragment('page_id');

            $this->pageService->delete($id);

            $body = [
                'success' => true,
                'message' => 'Page successful deleted'
            ];
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(200, [], $body);
    }
}
