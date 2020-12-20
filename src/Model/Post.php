<?php

declare(strict_types = 1);

namespace App\Model;


class Post implements \JsonSerializable
{
    /**
     * @var int|null
     */
    protected $pageId;
    /**
     * @var string|null
     */
    protected $title;
    /**
     * @var string|null
     */
    protected $summary;
    /**
     * @var string|null
     */
    protected $content;
    /**
     * @var \DateTime|null
     */
    protected $insertDate;
    /**
     * @param int|null $pageId
     */
    public function setPageId(?int $pageId) : void
    {
        $this->pageId = $pageId;
    }
    /**
     * @return int|null
     */
    public function getPageId() : ?int
    {
        return $this->pageId;
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
     * @param string|null $summary
     */
    public function setSummary(?string $summary) : void
    {
        $this->summary = $summary;
    }
    /**
     * @return string|null
     */
    public function getSummary() : ?string
    {
        return $this->summary;
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
    /**
     * @param \DateTime|null $insertDate
     */
    public function setInsertDate(?\DateTime $insertDate) : void
    {
        $this->insertDate = $insertDate;
    }
    /**
     * @return \DateTime|null
     */
    public function getInsertDate() : ?\DateTime
    {
        return $this->insertDate;
    }
    public function jsonSerialize()
    {
        return (object) array_filter(array('pageId' => $this->pageId, 'title' => $this->title, 'summary' => $this->summary, 'content' => $this->content, 'insertDate' => $this->insertDate), static function ($value) : bool {
            return $value !== null;
        });
    }
}
