<?php 

namespace App\Schema;

/**
 * @Title("Page")
 */
class Page
{
    /**
     * @Key("parentId")
     * @Type("integer")
     * @var integer
     */
    protected $parentId;

    /**
     * @Key("status")
     * @Type("integer")
     * @var integer
     */
    protected $status;

    /**
     * @Key("sort")
     * @Type("integer")
     * @var integer
     */
    protected $sort;

    /**
     * @Key("title")
     * @Type("string")
     * @MaxLength(255)
     * @var string
     */
    protected $title;

    /**
     * @Key("blocks")
     * @Type("array")
     * @Items(@Schema(oneOf={@Ref("App\Schema\Block\Code"), @Ref("App\Schema\Block\Headline"), @Ref("App\Schema\Block\Image"), @Ref("App\Schema\Block\Paragraph")}))
     * @var array
     */
    protected $blocks;

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
    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    /**
     * @param int $parentId
     */
    public function setParentId(?int $parentId): void
    {
        $this->parentId = $parentId;
    }

    /**
     * @return int
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getSort(): ?int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     */
    public function setSort(?int $sort): void
    {
        $this->sort = $sort;
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
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getBlocks(): ?array
    {
        return $this->blocks;
    }

    /**
     * @param array $blocks
     */
    public function setBlocks(?array $blocks): void
    {
        $this->blocks = $blocks;
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
    public function setInsertDate(?\DateTime $insertDate): void
    {
        $this->insertDate = $insertDate;
    }
}