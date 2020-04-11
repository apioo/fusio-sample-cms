<?php 

namespace App\Schema;

/**
 * @Title("Post")
 */
class Post
{
    /**
     * @Key("pageId")
     * @Type("integer")
     * @var integer
     */
    protected $pageId;

    /**
     * @Key("title")
     * @Type("string")
     * @MaxLength(255)
     * @var string
     */
    protected $title;

    /**
     * @Key("summary")
     * @Type("string")
     * @var string
     */
    protected $summary;

    /**
     * @Key("content")
     * @Type("string")
     * @var string
     */
    protected $content;

    /**
     * @Key("insertDate")
     * @Type("string")
     * @Format("date-time")
     * @var \DateTime
     */
    protected $insertDate;

    /**
     * @return int
     */
    public function getPageId(): ?int
    {
        return $this->pageId;
    }

    /**
     * @param int $pageId
     */
    public function setPageId(?int $pageId)
    {
        $this->pageId = $pageId;
    }

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(?string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getSummary(): ?string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(?string $content)
    {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getInsertDate(): ?\DateTime
    {
        return $this->insertDate;
    }

    /**
     * @param \DateTime $insertDate
     */
    public function setInsertDate(?\DateTime $insertDate)
    {
        $this->insertDate = $insertDate;
    }
}