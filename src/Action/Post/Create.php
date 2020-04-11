<?php

namespace App\Action\Post;

use App\Service\Post;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

class Create extends ActionAbstract
{
    /**
     * @var Post
     */
    private $postService;

    public function __construct(Post $postService)
    {
        $this->postService = $postService;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
    {
        try {
            $this->postService->create($request->getBody()->getPayload(), $context);

            $body = [
                'success' => true, 
                'message' => 'Post successful created'
            ];
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $body);
    }
}
