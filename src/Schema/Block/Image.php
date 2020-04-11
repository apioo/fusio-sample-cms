<?php

namespace App\Schema\Block;

use App\Schema\BlockAbstract;

/**
 * @Title("Image")
 * @Required({"type", "content"})
 */
class Image extends BlockAbstract
{
    /**
     * @Key("type")
     * @Type("string")
     * @Enum({"image"})
     * @var string
     */
    protected $type;

    /**
     * @Key("src")
     * @Type("string")
     * @var string
     */
    protected $src;

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
    public function getSrc(): ?string
    {
        return $this->src;
    }

    /**
     * @param string $src
     */
    public function setSrc(?string $src): void
    {
        $this->src = $src;
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'src' => $this->src,
        ];
    }
}