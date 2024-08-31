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
 * Post service which is responsible to create, update and delete a post. Please take a look at the page service for
 * more details
 */
class Post
{
    private Table\Post $postTable;
    private DispatcherInterface $dispatcher;

    public function __construct(Table\Post $postTable, DispatcherInterface $dispatcher)
    {
        $this->postTable = $postTable;
        $this->dispatcher = $dispatcher;
    }

    public function create(Model\Post $post, ContextInterface $context): int
    {
        $this->assertPost($post);

        $row = new Table\Generated\PostRow();
        $row->setRefId($post->getRefId());
        $row->setUserId($context->getUser()->getId());
        $row->setTitle($post->getTitle() ?? throw new StatusCode\BadRequestException('Provided no title'));
        $row->setSummary($post->getSummary() ?? throw new StatusCode\BadRequestException('Provided no summary'));
        $row->setContent($post->getContent() ?? throw new StatusCode\BadRequestException('Provided no content'));
        $row->setInsertDate(LocalDateTime::now());
        $this->postTable->create($row);

        $id = $this->postTable->getLastInsertId();
        $this->dispatchEvent('post.created', $row, $id);

        return $id;
    }

    public function update(int $id, Model\Post $post): int
    {
        $row = $this->postTable->find($id);
        if (!$row instanceof Table\Generated\PostRow) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->assertPost($post);

        $row->setRefId($post->getRefId());
        $row->setTitle($post->getTitle() ?? throw new StatusCode\BadRequestException('Provided no title'));
        $row->setSummary($post->getSummary() ?? throw new StatusCode\BadRequestException('Provided no summary'));
        $row->setContent($post->getContent() ?? throw new StatusCode\BadRequestException('Provided no content'));
        $this->postTable->update($row);

        $this->dispatchEvent('post.updated', $row, $row->getId());

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->postTable->find($id);
        if (!$row instanceof Table\Generated\PostRow) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->postTable->delete($row);

        $this->dispatchEvent('post.deleted', $row, $row->getId());

        return $id;
    }

    private function dispatchEvent(string $type, Table\Generated\PostRow $data, int $id): void
    {
        $event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource('/post/' . $id)
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
    }

    private function assertPost(Model\Post $post): void
    {
        $refId = $post->getRefId();
        if ($refId === null) {
            throw new StatusCode\BadRequestException('No ref provided');
        }

        $title = $post->getTitle();
        if ($title === null) {
            throw new StatusCode\BadRequestException('No title provided');
        }

        $summary = $post->getSummary();
        if ($summary === null) {
            throw new StatusCode\BadRequestException('No summary provided');
        }

        $content = $post->getContent();
        if ($content === null) {
            throw new StatusCode\BadRequestException('No content provided');
        }
    }
}
