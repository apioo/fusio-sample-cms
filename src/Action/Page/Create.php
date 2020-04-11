<?php

namespace App\Action\Page;

use App\Service\Page;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

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
            $this->pageService->create($request->getBody()->getPayload(), $context);

            $body = [
                'success' => true, 
                'message' => 'Page successful created'
            ];
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $body);
    }
}
