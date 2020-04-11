<?php

namespace App\Action\Post;

use App\Service\Page;
use App\Service\Post;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

class Update extends ActionAbstract
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
            $id = (int) $request->getUriFragment('post_id');

            $this->postService->update($id, $request->getBody()->getPayload());

            $body = [
                'success' => true,
                'message' => 'Page successful updated'
            ];
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(200, [], $body);
    }
}
