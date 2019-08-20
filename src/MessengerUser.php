<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 20/08/2019
 * Time: 22:37
 */

namespace He110\CommunicationTools;


class MessengerUser
{
    /** @var string|null */
    private $userId;

    /** @var string|null */
    private $firstName;

    /** @var string|null */
    private $lastName;

    /** @var string|null */
    private $username;

    /** @var string|null */
    private $languageCode;

    /**
     * @return null|string
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param null|string $userId
     * @return MessengerUser
     */
    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param null|string $firstName
     * @return MessengerUser
     */
    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param null|string $lastName
     * @return MessengerUser
     */
    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param null|string $username
     * @return MessengerUser
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getLanguageCode(): ?string
    {
        return $this->languageCode;
    }

    /**
     * @param null|string $languageCode
     * @return MessengerUser
     */
    public function setLanguageCode(?string $languageCode): self
    {
        $this->languageCode = $languageCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullName(): string
    {
        $firstName = $this->getFirstName() ?? "";
        $lastName = $this->getLastName() ?? "";
        $fullName = trim("$firstName $lastName");
        return str_replace("  ", " ", $fullName);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getFullName();
    }

}