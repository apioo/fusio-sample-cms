<?php

namespace App\Service;

use App\Model;
use Doctrine\DBAL\Connection;
use Fusio\Engine\ContextInterface;
use Fusio\Engine\DispatcherInterface;
use PSX\CloudEvents\Builder;
use PSX\Framework\Util\Uuid;
use PSX\Http\Exception as StatusCode;

/**
 * Page service which is responsible to create, update and delete a page. It
 * uses the doctrine connection to execute the DML queries and also the
 * dispatcher to trigger specific events. The users of your API can then register
 * HTTP callback urls which are invoked if such an event happens. Note in order
 * to activate the dispatching of the events you need to create a cron to
 * execute the command: php bin/fusio event:execute
 */
class Page
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

    public function create(Model\Page $page, ContextInterface $context): int
    {
        $this->assertPage($page);

        $this->connection->beginTransaction();

        try {
            $data = [
                'parent_id' => $page->getParentId(),
                'user_id' => $context->getUser()->getId(),
                'title' => $page->getTitle(),
                'data' => \json_encode($page->getBlocks()),
                'insert_date' => (new \DateTime())->format('Y-m-d H:i:s'),
            ];

            $this->connection->insert('app_page', $data);
            $id = (int) $this->connection->lastInsertId();

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not insert a page', $e);
        }

        $this->dispatchEvent('page_created', $data);

        return $id;
    }

    public function update(int $id, Model\Page $page): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM app_page WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided page does not exist');
        }

        $this->assertPage($page);

        $this->connection->beginTransaction();

        try {
            $data = [
                'parent_id' => $page->getParentId(),
                'title' => $page->getTitle(),
                'data' => \json_encode($page->getBlocks()),
            ];

            $this->connection->update('app_page', $data, ['id' => $id]);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not update a page', $e);
        }

        $this->dispatchEvent('page_updated', $data, $id);

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->connection->fetchAssoc('SELECT id FROM app_page WHERE id = :id', [
            'id' => $id,
        ]);

        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided page does not exist');
        }

        try {
            $this->connection->delete('app_page', ['id' => $id]);
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            throw new StatusCode\InternalServerErrorException('Could not delete a page', $e);
        }

        $this->dispatchEvent('page_deleted', $row, $id);

        return $id;
    }

    private function dispatchEvent(string $type, array $data, ?int $id = null)
    {
        $event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource($id !== null ? '/page/' . $id : '/page')
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
    }

    private function assertPage(Model\Page $page)
    {
        $title = $page->getTitle();
        if (empty($title)) {
            throw new StatusCode\BadRequestException('No title provided');
        }

        $blocks = $page->getBlocks();
        if (empty($blocks)) {
            throw new StatusCode\BadRequestException('No blocks provided');
        }
    }
}
