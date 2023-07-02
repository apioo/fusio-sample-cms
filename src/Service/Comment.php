<?php

namespace App\Service;

use App\Model;
use App\Table;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use PSX\CloudEvents\Builder;
use PSX\DateTime\LocalDateTime;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;

/**
 * Comment service which is responsible to create, update and delete a post. Please take a look at the page service for
 * more details
 */
class Comment
{
    private Table\Comment $commentTable;
    private DispatcherInterface $dispatcher;

    public function __construct(Table\Comment $commentTable, DispatcherInterface $dispatcher)
    {
        $this->commentTable = $commentTable;
        $this->dispatcher = $dispatcher;
    }

    public function create(Model\Comment $comment, ContextInterface $context): int
    {
        $this->assertComment($comment);

        $row = new Table\Generated\CommentRow();
        $row->setRefId($comment->getRefId());
        $row->setUserId($context->getUser()->getId());
        $row->setContent($comment->getContent() ?? throw new StatusCode\BadRequestException('Provided no content'));
        $row->setInsertDate(LocalDateTime::now());
        $this->commentTable->create($row);

        $id = $this->commentTable->getLastInsertId();
        $this->dispatchEvent('comment.created', $row, $id);

        return $id;
    }

    public function update(int $id, Model\Comment $comment): int
    {
        $row = $this->commentTable->find($id);
        if (!$row instanceof Table\Generated\CommentRow) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->assertComment($comment);

        $row->setRefId($comment->getRefId());
        $row->setContent($comment->getContent() ?? throw new StatusCode\BadRequestException('Provided no content'));
        $this->commentTable->update($row);

        $this->dispatchEvent('comment.updated', $row, $row->getId());

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->commentTable->find($id);
        if (!$row instanceof Table\Generated\CommentRow) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->commentTable->delete($row);

        $this->dispatchEvent('comment.deleted', $row, $row->getId());

        return $id;
    }

    private function dispatchEvent(string $type, Table\Generated\CommentRow $data, int $id): void
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

    private function assertComment(Model\Comment $post): void
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
