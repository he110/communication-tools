<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 00:30
 */

namespace He110\CommunicationTools;


class MessengerPool implements MessengerInterface
{
    /** @var MessengerInterface[] */
    protected $messengers = [];

    /**
     * Adds any messenger, which implements MessengerInterface to solid collection
     * It allows you to control them all as a single one
     *
     * @param MessengerInterface $messenger
     * @return MessengerPool
     */
    public function add(MessengerInterface $messenger): self
    {
        $targetUserId = $this->getTargetUser();
        if (in_array($messenger, $this->messengers))
            array_push($this->messengers, $messenger);

        if ($targetUserId !== "")
            $this->setTargetUser($targetUserId);
        elseif ($targetUserId === "" && !empty($messenger->getTargetUser()))
            $this->setTargetUser($messenger->getTargetUser());

        return $this;
    }

    /**
     * Find key of specific messenger
     *
     * @param MessengerInterface $messenger
     * @return int
     */
    public function indexOf(MessengerInterface $messenger): int
    {
        $key = array_search($messenger, $this->messengers);
        if ($key === false)
            $key = -1;
        return $key;
    }

    /**
     * Removes messenger from collection by it's Key
     *
     * @param int $key
     * @return MessengerPool
     */
    public function removeByKey(int $key): self
    {
        unset($this->messengers[$key]);
        return $this;
    }

    /**
     * Removes messenger from collection
     *
     * @param MessengerInterface $messenger
     * @return MessengerPool
     */
    public function remove(MessengerInterface $messenger): self
    {
        if (0 <= $key = $this->indexOf($messenger))
            $this->removeByKey($key);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTargetUser(string $userId)
    {
        foreach ($this->messengers as $messenger)
            $messenger->setTargetUser($userId);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetUser(): string
    {
        $messenger = reset($this->messengers);
        if ($messenger === false)
            return "";
        return $messenger->getTargetUser();
    }

    /**
     * {@inheritdoc}
     */
    public function sendMessage(string $text, array $buttons = []): bool
    {
        $result = true;
        foreach ($this->messengers as $messenger)
            $result = $messenger->sendMessage($text, $buttons) && $result;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function sendImage(string $pathToFile, string $description = null, array $buttons = []): bool
    {
        $result = true;
        foreach ($this->messengers as $messenger)
            $result = $messenger->sendImage($pathToFile, $description, $buttons) && $result;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function sendDocument(string $pathToFile, string $description = null, array $buttons = []): bool
    {
        $result = true;
        foreach ($this->messengers as $messenger)
            $result = $messenger->sendDocument($pathToFile, $description, $buttons) && $result;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function sendVoice(string $pathToFile): bool
    {
        $result = true;
        foreach ($this->messengers as $messenger)
            $result = $messenger->sendVoice($pathToFile) && $result;
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function sendScreen(MessengerScreen $screen): bool
    {
        $result = true;
        foreach ($this->messengers as $messenger)
            $result = $messenger->sendScreen($screen) && $result;
        return $result;
    }
}
