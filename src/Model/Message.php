<?php

declare(strict_types = 1);

namespace App\Model;

/**
 * @Description("Contains a message whether the operation was successful or not")
 */
class Message implements \JsonSerializable
{
    /**
     * @var bool|null
     */
    protected $success;
    /**
     * @var string|null
     */
    protected $message;
    /**
     * @var int|null
     */
    protected $id;
    /**
     * @param bool|null $success
     */
    public function setSuccess(?bool $success) : void
    {
        $this->success = $success;
    }
    /**
     * @return bool|null
     */
    public function getSuccess() : ?bool
    {
        return $this->success;
    }
    /**
     * @param string|null $message
     */
    public function setMessage(?string $message) : void
    {
        $this->message = $message;
    }
    /**
     * @return string|null
     */
    public function getMessage() : ?string
    {
        return $this->message;
    }
    /**
     * @param int|null $id
     */
    public function setId(?int $id) : void
    {
        $this->id = $id;
    }
    /**
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->id;
    }
    public function jsonSerialize()
    {
        return (object) array_filter(array('success' => $this->success, 'message' => $this->message, 'id' => $this->id), static function ($value) : bool {
            return $value !== null;
        });
    }
}
