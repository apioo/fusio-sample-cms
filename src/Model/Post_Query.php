<?php

declare(strict_types = 1);

namespace App\Model;

use PSX\Schema\Attribute\Description;

#[Description('Query parameters for a post')]
class Post_Query implements \JsonSerializable
{
    protected ?int $refId = null;
    protected ?string $title = null;
    protected ?string $summary = null;
    protected ?string $content = null;
    public function setRefId(?int $refId) : void
    {
        $this->refId = $refId;
    }
    public function getRefId() : ?int
    {
        return $this->refId;
    }
    public function setTitle(?string $title) : void
    {
        $this->title = $title;
    }
    public function getTitle() : ?string
    {
        return $this->title;
    }
    public function setSummary(?string $summary) : void
    {
        $this->summary = $summary;
    }
    public function getSummary() : ?string
    {
        return $this->summary;
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
        return (object) array_filter(array('refId' => $this->refId, 'title' => $this->title, 'summary' => $this->summary, 'content' => $this->content), static function ($value) : bool {
            return $value !== null;
        });
    }
}

