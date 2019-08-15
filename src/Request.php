<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 16/08/2019
 * Time: 02:08
 */

namespace He110\CommunicationTools;


class Request
{
    /** @var string|null */
    private $userId;

    /** @var string|null */
    private $type;

    /** @var string */
    private $message;

    /** @var string|null*/
    private $path;

    const REQUEST_TYPE_MESSAGE = "message_new";
    const REQUEST_TYPE_MESSAGE_READ = "message_read";
    const REQUEST_TYPE_BUTTON_CLICK = "button_click";

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return Request
     */
    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Request
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message ?? "";
    }

    /**
     * @param string $message
     * @return Request
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Request
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }


}