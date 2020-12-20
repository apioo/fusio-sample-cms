<?php

declare(strict_types = 1);

namespace App\Model;


class Page implements \JsonSerializable
{
    /**
     * @var int|null
     */
    protected $parentId;
    /**
     * @var int|null
     */
    protected $status;
    /**
     * @var int|null
     */
    protected $sort;
    /**
     * @var string|null
     */
    protected $title;
    /**
     * @var array<Block_Code|Block_Headline|Block_Image|Block_Paragraph>|null
     */
    protected $blocks;
    /**
     * @var \DateTime|null
     */
    protected $insertDate;
    /**
     * @param int|null $parentId
     */
    public function setParentId(?int $parentId) : void
    {
        $this->parentId = $parentId;
    }
    /**
     * @return int|null
     */
    public function getParentId() : ?int
    {
        return $this->parentId;
    }
    /**
     * @param int|null $status
     */
    public function setStatus(?int $status) : void
    {
        $this->status = $status;
    }
    /**
     * @return int|null
     */
    public function getStatus() : ?int
    {
        return $this->status;
    }
    /**
     * @param int|null $sort
     */
    public function setSort(?int $sort) : void
    {
        $this->sort = $sort;
    }
    /**
     * @return int|null
     */
    public function getSort() : ?int
    {
        return $this->sort;
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
     * @param array<Block_Code|Block_Headline|Block_Image|Block_Paragraph>|null $blocks
     */
    public function setBlocks(?array $blocks) : void
    {
        $this->blocks = $blocks;
    }
    /**
     * @return array<Block_Code|Block_Headline|Block_Image|Block_Paragraph>|null
     */
    public function getBlocks() : ?array
    {
        return $this->blocks;
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
        return (object) array_filter(array('parentId' => $this->parentId, 'status' => $this->status, 'sort' => $this->sort, 'title' => $this->title, 'blocks' => $this->blocks, 'insertDate' => $this->insertDate), static function ($value) : bool {
            return $value !== null;
        });
    }
}
