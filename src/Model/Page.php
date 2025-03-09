<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;

#[Description('A specific page')]
class Page implements \JsonSerializable, \PSX\Record\RecordableInterface
{
    protected ?int $refId = null;
    protected ?string $title = null;
    protected ?string $content = null;
    protected ?\PSX\DateTime\LocalDateTime $insertDate = null;
    public function setRefId(?int $refId): void
    {
        $this->refId = $refId;
    }
    public function getRefId(): ?int
    {
        return $this->refId;
    }
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
    public function getContent(): ?string
    {
        return $this->content;
    }
    public function setInsertDate(?\PSX\DateTime\LocalDateTime $insertDate): void
    {
        $this->insertDate = $insertDate;
    }
    public function getInsertDate(): ?\PSX\DateTime\LocalDateTime
    {
        return $this->insertDate;
    }
    public function toRecord(): \PSX\Record\RecordInterface
    {
        /** @var \PSX\Record\Record<mixed> $record */
        $record = new \PSX\Record\Record();
        $record->put('refId', $this->refId);
        $record->put('title', $this->title);
        $record->put('content', $this->content);
        $record->put('insertDate', $this->insertDate);
        return $record;
    }
    public function jsonSerialize(): object
    {
        return (object) $this->toRecord()->getAll();
    }
}

