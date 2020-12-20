<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * @Required({"type"})
 */
class Block_Post implements \JsonSerializable
{
    /**
     * @var string|null
     */
    protected $type = 'post';
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
    public function jsonSerialize()
    {
        return (object) array_filter(array('type' => $this->type), static function ($value) : bool {
            return $value !== null;
        });
    }
}
