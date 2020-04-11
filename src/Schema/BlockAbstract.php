<?php

namespace App\Schema;

abstract class BlockAbstract implements \JsonSerializable
{
    /**
     * @return string|null
     */
    abstract public function getType(): ?string;
}
