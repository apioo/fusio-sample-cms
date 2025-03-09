<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;
/**
 * @template T
 */
#[Description('A collection of things')]
class Collection implements \JsonSerializable, \PSX\Record\RecordableInterface
{
    protected ?int $totalResults = null;
    /**
     * @var array<T>|null
     */
    protected ?array $entry = null;
    public function setTotalResults(?int $totalResults): void
    {
        $this->totalResults = $totalResults;
    }
    public function getTotalResults(): ?int
    {
        return $this->totalResults;
    }
    /**
     * @param array<T>|null $entry
     */
    public function setEntry(?array $entry): void
    {
        $this->entry = $entry;
    }
    /**
     * @return array<T>|null
     */
    public function getEntry(): ?array
    {
        return $this->entry;
    }
    public function toRecord(): \PSX\Record\RecordInterface
    {
        /** @var \PSX\Record\Record<mixed> $record */
        $record = new \PSX\Record\Record();
        $record->put('totalResults', $this->totalResults);
        $record->put('entry', $this->entry);
        return $record;
    }
    public function jsonSerialize(): object
    {
        return (object) $this->toRecord()->getAll();
    }
}

