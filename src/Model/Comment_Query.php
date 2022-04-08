<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;

#[Description('Query parameters for a comment')]
class Comment_Query implements \JsonSerializable
{
    protected ?int $refId = null;
    protected ?string $content = null;
    public function setRefId(?int $refId) : void
    {
        $this->refId = $refId;
    }
    public function getRefId() : ?int
    {
        return $this->refId;
    }
    public function setContent(?string $content) : void
    {
        $this->content = $content;
    }
    public function getContent() : ?string
    {
        return $this->content;
    }
    public function jsonSerialize() : \stdClass
    {
        return (object) array_filter(array('refId' => $this->refId, 'content' => $this->content), static function ($value) : bool {
            return $value !== null;
        });
    }
}

