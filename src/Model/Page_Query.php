<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * @Description("Query parameters for a page")
 */
class Page_Query implements \JsonSerializable
{
    /**
     * @var int|null
     */
    protected $refId;
    /**
     * @var string|null
     */
    protected $title;
    /**
     * @var string|null
     */
    protected $content;
    /**
     * @param int|null $refId
     */
    public function setRefId(?int $refId) : void
    {
        $this->refId = $refId;
    }
    /**
     * @return int|null
     */
    public function getRefId() : ?int
    {
        return $this->refId;
    }
    /**
     * @param string|null $title
     */
    public function setTitle(?string $title) : void
    {
        $this->title = $title;
    }
    /**
     * @return string|null
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }
    /**
     * @param string|null $content
     */
    public function setContent(?string $content) : void
    {
        $this->content = $content;
    }
    /**
     * @return string|null
     */
    public function getContent() : ?string
    {
        return $this->content;
    }
    public function jsonSerialize()
    {
        return (object) array_filter(array('refId' => $this->refId, 'title' => $this->title, 'content' => $this->content), static function ($value) : bool {
            return $value !== null;
        });
    }
}
