<?php

namespace App\Action\Comment;

use App\Model\Message;
use App\Service\Comment;
use Fusio\Engine\ActionInterface;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use Fusio\Engine\Response\FactoryInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which creates a comment. Similar to the page create action it only
 * invokes the comment service to create a specific comment
 */
class Create implements ActionInterface
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
            $id = $this->commentService->create(
                $request->getPayload(),
                $context
            );

            $message = new Message();
            $message->setSuccess(true);
            $message->setMessage('Comment successful created');
            $message->setId($id);
        } catch (StatusCodeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new InternalServerErrorException($e->getMessage());
        }

        return $this->response->build(201, [], $message);
    }
}
