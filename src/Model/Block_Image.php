<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * @Required({"type", "src"})
 */
class Block_Image implements \JsonSerializable
{
    /**
     * @var string|null
     */
    protected $type = 'image';
    /**
     * @var string|null
     */
    protected $src;
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
     * @param string|null $src
     */
    public function setSrc(?string $src) : void
    {
        $this->src = $src;
    }
    /**
     * @return string|null
     */
    public function getSrc() : ?string
    {
        return $this->src;
    }
    public function jsonSerialize()
    {
        return (object) array_filter(array('type' => $this->type, 'src' => $this->src), static function ($value) : bool {
            return $value !== null;
        });
    }
}
