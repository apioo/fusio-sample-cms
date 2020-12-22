<?php

namespace App\Service;

use App\Model;
use App\Repository;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use PSX\CloudEvents\Builder;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;

/**
 * Comment service which is responsible to create, update and delete a post. Please take a look at the page service for
 * more details
 */
class Comment
{
    /**
     * @var Repository\Comment
     */
    private $repository;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    public function __construct(Repository\Comment $repository, DispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function create(Model\Comment $comment, ContextInterface $context): int
    {
        $this->assertComment($comment);

        $id = $this->repository->insert(
            $comment->getRefId(),
            $context->getUser()->getId(),
            $comment->getContent()
        );

        $row = $this->repository->findById($id);
        $this->dispatchEvent('comment_created', $row, $id);

        return $id;
    }

    public function update(int $id, Model\Comment $comment): int
    {
        $row = $this->repository->findById($id);
        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->assertComment($comment);

        $this->repository->update($id, $comment->getRefId(), $comment->getContent());

        $row = $this->repository->findById($id);
        $this->dispatchEvent('comment_updated', $row, $id);

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->repository->findById($id);
        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->repository->delete($id);

        $this->dispatchEvent('comment_deleted', $row, $id);

        return $id;
    }

    private function dispatchEvent(string $type, array $data, int $id)
    {
        $event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource('/comment/' . $id)
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
    }

    private function assertComment(Model\Comment $post)
    {
        $refId = $post->getRefId();
        if ($refId === null) {
            throw new StatusCode\BadRequestException('No ref provided');
        }

        $content = $post->getContent();
        if (empty($content)) {
            throw new StatusCode\BadRequestException('No content provided');
        }
    }
}
