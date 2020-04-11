<?php

namespace App\Schema\Block;

use App\Schema\BlockAbstract;

/**
 * @Title("Post")
 * @Required({"type"})
 */
class Post extends BlockAbstract
{
    /**
     * @Key("type")
     * @Type("string")
     * @Enum({"post"})
     * @var string
     */
    protected $type;

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

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
        ];
    }
}