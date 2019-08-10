<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 02:26
 */

namespace He110\CommunicationTools\Telegram;


use He110\CommunicationTools\Exceptions\AccessTokenException;
use He110\CommunicationTools\Exceptions\AttachmentNotFoundException;
use He110\CommunicationTools\Exceptions\TargetUserException;
use He110\CommunicationTools\MessengerInterface;
use He110\CommunicationTools\MessengerScreen;
use He110\CommunicationTools\MessengerWithTokenInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

class Messenger implements MessengerInterface, MessengerWithTokenInterface
{
    /** @var BotApi */
    private $client = null;

    /** @var string */
    private $accessToken;

    /** @var string */
    protected $userId;

    /**
     * {@inheritdoc}
     */
    public function setTargetUser(?string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetUser(): ?string
    {
        return $this->userId;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $text
     * @param array $buttons
     * @return bool
     * @throws AccessTokenException
     * @throws TargetUserException
     */
    public function sendMessage(string $text, array $buttons = []): bool
    {
        $this->checkRequirements();

        try {
            $result = $this->client->sendMessage($this->getTargetUser(), $text);

            //TODO Сделать работу с кнопками

            return method_exists($result, "getMessageId") && $result->getMessageId();
        } catch (\Exception $exception) {
            return false;
        }

    }

    /**
     * {@inheritdoc}
     *
     * @param string $pathToFile
     * @param string|null $description
     * @param array $buttons
     * @return bool
     * @throws AccessTokenException
     * @throws AttachmentNotFoundException
     * @throws TargetUserException
     */
    public function sendImage(string $pathToFile, string $description = null, array $buttons = []): bool
    {
        $this->checkRequirements();
        $document = $this->prepareFile($pathToFile);
        try {
            //TODO Сделать работу с кнопками
            return $this->checkRequestResult($this->client->sendPhoto($this->getTargetUser(), $document, $description));
        } catch (\Exception $exception) {
            return false;
        }

    }

    /**
     * {@inheritdoc}
     *
     * @param string $pathToFile
     * @param string|null $description
     * @param array $buttons
     * @return bool
     * @throws AccessTokenException
     * @throws AttachmentNotFoundException
     * @throws TargetUserException
     */
    public function sendDocument(string $pathToFile, string $description = null, array $buttons = []): bool
    {
        $this->checkRequirements();
        $document = $this->prepareFile($pathToFile);
        try {
            //TODO Сделать работу с кнопками
            return $this->checkRequestResult($this->client->sendDocument($this->getTargetUser(), $document, $description));
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $pathToFile
     * @return bool
     * @throws AccessTokenException
     * @throws AttachmentNotFoundException
     * @throws TargetUserException
     */
    public function sendVoice(string $pathToFile): bool
    {
        $this->checkRequirements();
        $document = $this->prepareFile($pathToFile);
        try {
            //TODO Сделать работу с кнопками
            return $this->checkRequestResult($this->client->sendVoice($this->getTargetUser(), $document));
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function sendScreen(MessengerScreen $screen): bool
    {
        // TODO: Implement sendScreen() method.
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(string $token)
    {
        $this->accessToken = $token;
        $this->client = new BotApi($token);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @throws AccessTokenException
     * @throws TargetUserException
     */
    private function checkRequirements()
    {
        if (is_null($this->client) || is_null($this->getAccessToken())) {
            throw new AccessTokenException("Telegram access token required");
        }

        if (empty($this->getTargetUser())) {
            throw new TargetUserException("Target Client ID required");
        }

    }

    /**
     * @param string $pathToFile
     * @return \CURLFile
     * @throws AttachmentNotFoundException
     */
    private function prepareFile(string $pathToFile): \CURLFile
    {
        if (!file_exists($pathToFile))
            throw new AttachmentNotFoundException("Attachment not found");
        return new \CURLFile($pathToFile);
    }

    private function checkRequestResult(Message $message): bool
    {
        return method_exists($message, "getMessageId") && $message->getMessageId();
    }
}