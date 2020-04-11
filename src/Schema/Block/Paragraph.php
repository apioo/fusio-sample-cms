<?php 

namespace App\Schema\Block;

use App\Schema\BlockAbstract;

/**
 * @Title("Paragraph")
 * @Required({"type", "content"})
 */
class Paragraph extends BlockAbstract
{
    /**
     * @Key("type")
     * @Type("string")
     * @Enum({"paragraph"})
     * @var string
     */
    protected $type;

    /**
     * @Key("content")
     * @Type("string")
     * @var string
     */
    protected $content;

    /**
     * @return mixed
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
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
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'content' => $this->content,
        ];
    }
}