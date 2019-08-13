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
use He110\CommunicationTools\ScreenItems\Button;
use He110\CommunicationTools\ScreenItems\File;
use He110\CommunicationTools\ScreenItems\Voice;
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
            $keyboard = $this->generateButtonMarkup($buttons);
            $result = $this->client->sendMessage(
                $this->getTargetUser(),
                $text,
                null,
                false,
                null,
                $keyboard
            );

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
            $keyboard = $this->generateButtonMarkup($buttons);
            $result = $this->client->sendPhoto(
                $this->getTargetUser(),
                $document,
                $description,
                null,
                $keyboard
            );
            return $this->checkRequestResult($result);
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
            $keyboard = $this->generateButtonMarkup($buttons);
            $result = $this->client->sendDocument(
                $this->getTargetUser(),
                $document,
                $description,
                null,
                $keyboard
            );
            return $this->checkRequestResult($result);
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
        $buttons = array();
        $current = null;
        $result = true;
        foreach ($screen->getContent() as $index => $item) {
            if ($item instanceof Button)
                $buttons[] = $item;
            else {
                if ($current === null) {
                    $current = $item;
                } else {
                    if ($current instanceof \He110\CommunicationTools\ScreenItems\Message)
                        $result = $result && $this->sendMessage($current->getText(), $buttons);
                    elseif ($current instanceof File) {
                        if ($current->getType() === File::FILE_TYPE_IMAGE)
                            $result = $result && $this->sendImage($current->getPath(), $current->getDescription());
                        else {
                            $result = $result && $this->sendDocument($current->getPath(), $current->getDescription());
                        }
                    }
                    elseif ($current instanceof Voice)
                        $result = $result && $this->sendVoice($current->getPath());

                    if (!($current instanceof Voice))
                        $buttons = [];
                    $current = $item;
                }
            }
        }
        return $result;
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

    /**
     * @param Message $message
     * @return bool
     */
    private function checkRequestResult(Message $message): bool
    {
        return method_exists($message, "getMessageId") && $message->getMessageId();
    }

    /**
     * @param Button[] $buttons
     * @return null|\TelegramBot\Api\Types\Inline\InlineKeyboardMarkup
     */
    private function generateButtonMarkup(array $buttons)
    {
        if ($buttons && current($buttons) instanceof Button) {
            $content = [];
            foreach ($buttons as $button) {
                if ($button->getType() == Button::BUTTON_TYPE_URL)
                    $content[] = array(
                        array(
                            "text" => $button->getLabel(),
                            "url" => $button->getContent()
                        )
                    );
                elseif ($button->getType() == Button::BUTTON_TYPE_TEXT)
                    $content[] = array(
                        array(
                            "text" => $button->getLabel(),
                            "callback_data" => "text=".$button->getLabel()
                        )
                    );
                elseif ($button->getType() == Button::BUTTON_TYPE_CALLBACK) {
                    $content[] = array(
                        array(
                            "text" => $button->getLabel(),
                            "callback_data" => "clb=".$button->getContent()
                        )
                    );
                }
            }
            return new \TelegramBot\Api\Types\Inline\InlineKeyboardMarkup($content);
        }
        return null;
    }
}