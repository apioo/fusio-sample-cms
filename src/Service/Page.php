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
 * Page service which is responsible to create, update and delete a page. It uses the page repository to execute the
 * DML queries and also the dispatcher to trigger specific events. The users of your API can then register HTTP callback
 * urls which are invoked if such an event happens. Note in order to activate the dispatching of the events you need to
 * create a cron to execute the command: php bin/fusio event:execute
 */
class Page
{
    /**
     * @var Repository\Page
     */
    private $repository;

    /**
     * @var DispatcherInterface
     */
    private $dispatcher;

    public function __construct(Repository\Page $repository, DispatcherInterface $dispatcher)
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    public function create(Model\Page $page, ContextInterface $context): int
    {
        $this->assertPage($page);

        $id = $this->repository->insert(
            $page->getRefId(),
            $context->getUser()->getId(),
            $page->getTitle(),
            $page->getContent()
        );

        $row = $this->repository->findById($id);
        $this->dispatchEvent('page_created', $row, $id);

        return $id;
    }

    public function update(int $id, Model\Page $page): int
    {
        $row = $this->repository->findById($id);
        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided page does not exist');
        }

        $this->assertPage($page);

        $this->repository->update($id, $page->getRefId(), $page->getTitle(), $page->getContent());

        $row = $this->repository->findById($id);
        $this->dispatchEvent('page_updated', $row, $id);

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->repository->findById($id);
        if (empty($row)) {
            throw new StatusCode\NotFoundException('Provided page does not exist');
        }

        $this->repository->delete($id);

        $this->dispatchEvent('page_deleted', $row, $id);

        return $id;
    }

    private function dispatchEvent(string $type, array $data, int $id)
    {
        $event = (new Builder())
            ->withId(Uuid::pseudoRandom())
            ->withSource('/page/' . $id)
            ->withType($type)
            ->withDataContentType('application/json')
            ->withData($data)
            ->build();

        $this->dispatcher->dispatch($type, $event);
    }

    private function assertPage(Model\Page $page)
    {
        $refId = $page->getRefId();
        if ($refId === null) {
            throw new StatusCode\BadRequestException('No ref provided');
        }

        $title = $page->getTitle();
        if (empty($title)) {
            throw new StatusCode\BadRequestException('No title provided');
        }

        $content = $page->getContent();
        if (empty($content)) {
            throw new StatusCode\BadRequestException('No content provided');
        }
    }
}
