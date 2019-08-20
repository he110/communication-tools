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
    /** @var MessengerUser|null */
    private $user;

    /** @var string|null */
    private $type;

    /** @var string */
    private $message;

    /** @var string|null*/
    private $path;

    /** @var string|null */
    private $payload;

    const REQUEST_TYPE_MESSAGE = "message_new";
    const REQUEST_TYPE_MESSAGE_READ = "message_read";
    const REQUEST_TYPE_BUTTON_CLICK = "button_click";

    /**
     * @return MessengerUser|null
     */
    public function getUser(): ?MessengerUser
    {
        return $this->user;
    }

    /**
     * @param MessengerUser|null $user
     * @return Request
     */
    public function setUser(?MessengerUser $user): self
    {
        $this->user = $user;
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

    /**
     * @return null|string
     */
    public function getPayload(): ?string
    {
        return $this->payload;
    }

    /**
     * @param null|string $payload
     * @return Request
     */
    public function setPayload(?string $payload): self
    {
        $this->payload = $payload;
        return $this;
    }
}
