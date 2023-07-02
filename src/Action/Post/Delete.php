<?php

namespace App\Action\Post;

use App\Model\Message;
use App\Service\Post;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Response\FactoryInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which deletes a post. Similar to the create action it only invokes the post service to delete a specific post
 */
class Delete implements ActionInterface
{
    private Post $postService;
    private FactoryInterface $response;

    public function __construct(Post $postService, FactoryInterface $response)
    {
        $this->postService = $postService;
        $this->response = $response;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        try {
            $id = $this->postService->delete(
                (int) $request->get('id')
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
