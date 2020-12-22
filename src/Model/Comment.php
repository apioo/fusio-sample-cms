<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * @Description("A specific comment")
 */
class Comment implements \JsonSerializable
{
    /**
     * @var int|null
     */
    protected $refId;
    /**
     * @var string|null
     */
    protected $content;
    /**
     * @var \DateTime|null
     */
    protected $insertDate;
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
        return (object) array_filter(array('refId' => $this->refId, 'content' => $this->content, 'insertDate' => $this->insertDate), static function ($value) : bool {
            return $value !== null;
        });
    }
}
