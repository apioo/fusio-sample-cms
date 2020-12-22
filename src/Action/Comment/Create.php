<?php

namespace App\Action\Comment;

use App\Model\Message;
use App\Service\Comment;
use App\Service\Post;
use Fusio\Engine\ActionAbstract;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\ParametersInterface;
use Fusio\Engine\RequestInterface;
use PSX\Http\Exception\InternalServerErrorException;
use PSX\Http\Exception\StatusCodeException;

/**
 * Action which creates a comment. Similar to the page create action it only
 * invokes the comment service to create a specific comment
 */
class Create extends ActionAbstract
{
    /**
     * @var Comment
     */
    private $commentService;

    public function __construct(Comment $commentService)
    {
        $this->commentService = $commentService;
    }

    public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
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
