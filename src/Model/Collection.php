<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;
/**
 * @template T
 */
#[Description('A collection of things')]
class Collection implements \JsonSerializable
{
    protected ?int $totalResults = null;
    /**
     * @var array<T>|null
     */
    protected ?array $entry = null;
    public function setTotalResults(?int $totalResults) : void
    {
        $this->totalResults = $totalResults;
    }
    public function getTotalResults() : ?int
    {
        return $this->totalResults;
    }
    /**
     * @param array<T>|null $entry
     */
    public function setEntry(?array $entry) : void
    {
        $this->entry = $entry;
    }
    public function getEntry() : ?array
    {
        return $this->entry;
    }
    public function jsonSerialize() : \stdClass
    {
        return (object) array_filter(array('totalResults' => $this->totalResults, 'entry' => $this->entry), static function ($value) : bool {
            return $value !== null;
        });
    }
}

