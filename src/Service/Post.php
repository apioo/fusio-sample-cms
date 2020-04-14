<?php

namespace App\Service;

use App\Schema\Page as SchemaPage;
use App\Schema\Post as SchemaPost;
use Doctrine\DBAL\Connection;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use PSX\CloudEvents\Builder;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;

/**
 * Post service which is responsible to create, update and delete a post. Please
 * take a look at the page service for more details
 */
class Post
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    public function __construct(Connection $connection, DispatcherInterface $dispatcher)
    {
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
    }

    public function create(SchemaPost $post, ContextInterface $context): int
    {
        $this->assertPost($post);

        $this->connection->beginTransaction();

        try {
            $data = [
                'page_id' => $post->getPageId(),
                'user_id' => $context->getUser()->getId(),
                'title' => $post->getTitle(),
                'summary' => $post->getSummary(),
                'content' => $post->getContent(),
                'insert_date' => (new \DateTime())->format('Y-m-d H:i:s'),
            ];

            $this->connection->insert('app_post', $data);
            $id = (int) $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not create a post', $e);
        }

        $this->dispatchEvent('post_created', $data);

        return $id;
    }

    public function update(int $id, SchemaPost $post): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM app_post WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        $this->assertPost($post);

        $this->connection->beginTransaction();

        try {
            $data = [
                'page_id' => $post->getPageId(),
                'title' => $post->getTitle(),
                'summary' => $post->getSummary(),
                'content' => $post->getContent(),
            ];

            $this->connection->update('app_post', $data, ['id' => $id]);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not update a post', $e);
        }

        $this->dispatchEvent('post_updated', $data, $id);

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM app_post WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided post does not exist');
        }

        try {
            $this->connection->delete('app_post', ['id' => $id]);
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not delete a post', $e);
        }

        $this->dispatchEvent('post_deleted', $row, $id);

        return $id;
    }

    private function dispatchEvent(string $type, array $data, ?int $id = null)
    {
        $event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource($id !== null ? '/post/' . $id : '/post')
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
    }

    private function assertPost(SchemaPost $post)
    {
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
