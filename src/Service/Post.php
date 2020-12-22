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
 * Post service which is responsible to create, update and delete a post. Please take a look at the page service for
 * more details
 */
class Post
{
    /**
     * @var Repository\Post
     */
    private $repository;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    public function __construct(Repository\Post $repository, DispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function create(Model\Post $post, ContextInterface $context): int
    {
        $this->assertPost($post);

        $id = $this->repository->insert(
            $post->getRefId(),
            $context->getUser()->getId(),
            $post->getTitle(),
            $post->getSummary(),
            $post->getContent()
        );

        $row = $this->repository->findById($id);
        $this->dispatchEvent('post_created', $row, $id);

        return $id;
    }

    public function update(int $id, Model\Post $post): int
    {
        $row = $this->repository->findById($id);
        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->assertPost($post);

        $id = $this->repository->update(
            $id,
            $post->getRefId(),
            $post->getTitle(),
            $post->getSummary(),
            $post->getContent()
        );

        $row = $this->repository->findById($id);
        $this->dispatchEvent('post_updated', $row, $id);

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->repository->findById($id);
        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->repository->delete($id);

        $this->dispatchEvent('post_deleted', $row, $id);

        return $id;
    }

    private function dispatchEvent(string $type, array $data, int $id)
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

    private function assertPost(Model\Post $post)
    {
        $refId = $post->getRefId();
        if ($refId === null) {
            throw new StatusCode\BadRequestException('No ref provided');
        }

        $title = $post->getTitle();
        if (empty($title)) {
            throw new StatusCode\BadRequestException('No title provided');
        }

        $summary = $post->getSummary();
        if (empty($summary)) {
            throw new StatusCode\BadRequestException('No summary provided');
        }

        $content = $post->getContent();
        if (empty($content)) {
            throw new StatusCode\BadRequestException('No content provided');
        }
    }
}
