<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * @Required({"type", "content"})
 */
class Block_Paragraph implements \JsonSerializable
{
    /**
     * @var string|null
     */
    protected $type = 'paragraph';
    /**
     * @var string|null
     */
    protected $content;
    /**
     * @param string|null $type
     */
    public function setType(?string $type) : void
    {
        $this->type = $type;
    }
    /**
     * @return string|null
     */
    public function getType() : ?string
    {
        return $this->type;
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
        return (object) array_filter(array('type' => $this->type, 'content' => $this->content), static function ($value) : bool {
            return $value !== null;
        });
    }
}
