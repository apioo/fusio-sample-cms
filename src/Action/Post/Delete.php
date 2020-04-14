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

/**
 * Action which deletes a post. Similar to the create action it only invokes the
 * post service to delete a specific post
 */
class Delete extends ActionAbstract
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

            $this->postService->delete($id);

            $body = [
                'success' => true,
                'message' => 'Post successful deleted'
            ];
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(200, [], $body);
    }
}
