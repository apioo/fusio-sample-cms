<?php

namespace App\Service;

use App\Model;
use App\Table;
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
    private Table\Page $pageTable;
    private DispatcherInterface $dispatcher;

    public function __construct(Table\Page $pageTable, DispatcherInterface $dispatcher)
    {
        $this->pageTable = $pageTable;
        $this->dispatcher = $dispatcher;
    }

    public function create(Model\Page $page, ContextInterface $context): int
    {
        $this->assertPage($page);

        $row = new Table\Generated\PageRow();
        $row->setRefId($page->getRefId());
        $row->setUserId($context->getUser()->getId());
        $row->setTitle($page->getTitle() ?? throw new StatusCode\BadRequestException('Provided no title'));
        $row->setContent($page->getContent() ?? throw new StatusCode\BadRequestException('Provided no content'));
        $this->pageTable->create($row);

        $id = $this->pageTable->getLastInsertId();
        $this->dispatchEvent('page.created', $row, $id);

        return $id;
    }

    public function update(int $id, Model\Page $page): int
    {
        $row = $this->pageTable->find($id);
        if (!$row instanceof Table\Generated\PageRow) {
            throw new StatusCode\NotFoundException('Provided page does not exist');
        }

        $this->assertPage($page);

        $row->setRefId($page->getRefId());
        $row->setTitle($page->getTitle() ?? throw new StatusCode\BadRequestException('Provided no title'));
        $row->setContent($page->getContent() ?? throw new StatusCode\BadRequestException('Provided no content'));
        $this->pageTable->update($row);

        $this->dispatchEvent('page.updated', $row, $row->getId());

        return $id;
    }

    public function delete(int $id): int
    {
        $row = $this->pageTable->find($id);
        if (!$row instanceof Table\Generated\PageRow) {
            throw new StatusCode\NotFoundException('Provided page does not exist');
        }

        $this->pageTable->delete($row);

        $this->dispatchEvent('page.deleted', $row, $row->getId());

        return $id;
    }

    private function dispatchEvent(string $type, Table\Generated\PageRow $data, int $id): void
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

    private function assertPage(Model\Page $page): void
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
