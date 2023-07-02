<?php

namespace App\Action\Comment;

use App\Model\Message;
use App\Service\Comment;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Response\FactoryInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which deletes a comment. Similar to the create action it only invokes the
 * comment service to delete a specific comment
 */
class Delete implements ActionInterface
{
    private Comment $commentService;
    private FactoryInterface $response;

    public function __construct(Comment $commentService, FactoryInterface $response)
    {
        $this->commentService = $commentService;
        $this->response = $response;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context): mixed
    {
        try {
            $id = $this->commentService->delete(
                (int) $request->get('comment_id')
            );

            $message = new Message();
            $message->setSuccess(true);
            $message->setMessage('Comment successful deleted');
            $message->setId($id);
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(200, [], $message);
    }
}
