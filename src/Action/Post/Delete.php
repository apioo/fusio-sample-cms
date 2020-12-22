<?php

namespace App\Action\Post;

use App\Model\Message;
use App\Service\Post;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which deletes a post. Similar to the create action it only invokes the post service to delete a specific post
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
            $id = $this->postService->delete(
                (int) $request->get('post_id')
            );

            $message = new Message();
            $message->setSuccess(true);
            $message->setMessage('Post successful deleted');
            $message->setId($id);
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(200, [], $message);
    }
}
