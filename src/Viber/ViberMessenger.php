<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2019-08-24
 * Time: 19:50
 */

namespace He110\CommunicationTools\Viber;


use He110\CommunicationTools\Exceptions\AccessTokenException;
use He110\CommunicationTools\Exceptions\TargetUserException;
use He110\CommunicationTools\MessengerInterface;
use He110\CommunicationTools\MessengerScreen;
use He110\CommunicationTools\MessengerWithTokenInterface;
use He110\CommunicationTools\ScreenItems\Button;
use He110\CommunicationTools\ScreenItems\File;
use Viber\Api\Keyboard;
use Viber\Api\Message\Text;
use Viber\Api\Response;
use Viber\Bot;

class ViberMessenger implements MessengerInterface, MessengerWithTokenInterface
{
    /** @var Bot|null */
    private $client;

    /** @var string|null */
    private $accessToken;

    /** @var string|null */
    private $targetUser;

    /**
     * {@inheritdoc}
     */
    public function setTargetUser(?string $userId)
    {
       $this->targetUser = $userId;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetUser(): ?string
    {
        return $this->targetUser;
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
            $message = (new Text())->setText($text)->setReceiver($this->getTargetUser());

            if (!empty($buttons))
                $message->setKeyboard($this->createViberKeyboard($buttons));


            $result = $this->client->getClient()->sendMessage($message);

            return $this->checkRequestResult($result);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $fileUrl
     * @param string|null $description
     * @param array $buttons
     * @return bool
     * @throws AccessTokenException
     * @throws TargetUserException
     */
    public function sendImage(string $fileUrl, string $description = null, array $buttons = []): bool
    {
        $this->checkRequirements();
        try {
            $message = (new \Viber\Api\Message\Picture())
                ->setReceiver($this->getTargetUser())
                ->setMedia($fileUrl)
                ->setText($description);

            if (!empty($buttons))
                $message->setKeyboard($this->createViberKeyboard($buttons));


            $result = $this->client->getClient()->sendMessage($message);

            return $this->checkRequestResult($result);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param string $fileUrl
     * @param string|null $description
     * @param array $buttons
     * @return bool
     * @throws AccessTokenException
     * @throws TargetUserException
     * @throws \He110\CommunicationTools\Exceptions\AttachmentNotFoundException
     */
    public function sendDocument(string $fileUrl, string $description = null, array $buttons = []): bool
    {
        $this->checkRequirements();
        $file = (new File())->setUrlPath($fileUrl);
        try {
            $message = (new \Viber\Api\Message\File())
                ->setReceiver($this->getTargetUser())
                ->setSize($file->getSize())
                ->setFileName($file->getName())
                ->setMedia($fileUrl);

            if (!empty($buttons))
                $message->setKeyboard($this->createViberKeyboard($buttons));


            $result = $this->client->getClient()->sendMessage($message);
            $additional = ($description == null || $description != null && $this->sendMessage($description));
            return $this->checkRequestResult($result) && $additional;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public function sendVoice(string $pathToFile): bool
    {
        // TODO: Implement sendVoice() method.
    }

    public function sendScreen(MessengerScreen $screen): bool
    {
        // TODO: Implement sendScreen() method.
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(?string $token)
    {
        $this->accessToken = $token;
        $this->client = new Bot(["token" => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * @throws AccessTokenException
     * @throws TargetUserException
     */
    private function checkRequirements()
    {
        if (is_null($this->client) || !$this->getAccessToken()) {
            throw new AccessTokenException("Viber access token required");
        }

        if (empty($this->getTargetUser())) {
            throw new TargetUserException("Target Client ID required");
        }
    }

    /**
     * @param Button $button
     * @param int $columns
     * @param int $rows
     * @return Keyboard\Button
     */
    private function convertButton(Button $button, int $columns = 6, int $rows = 1): Keyboard\Button
    {
        $newButton = $this->createViberButton($button->getLabel(), $columns, $rows);
        switch ($button->getType()) {
            case Button::BUTTON_TYPE_URL:
                $newButton->setActionType("open-url");
                $newButton->setActionBody($button->getContent());
                break;
            default:
                $newButton->setActionType("reply");
                if ($button->getType() == Button::BUTTON_TYPE_TEXT)
                    $newButton->setActionBody("text=".$button->getLabel());
                else
                    $newButton->setActionBody("clb=".$button->getContent());
                break;
        }
        return $newButton;
    }

    /**
     * @param string $label
     * @param int $columns
     * @param int $rows
     * @return Keyboard\Button
     */
    private function createViberButton(string $label, int $columns = 6, int $rows = 1): Keyboard\Button
    {
        $newButton = new Keyboard\Button();
        $newButton->setText($label);
        $newButton->setRows($rows);
        $newButton->setColumns($columns);
        $newButton->setBgColor("#ffffff");
        return $newButton;
    }

    /**
     * @param array $buttons
     * @return Keyboard
     */
    private function createViberKeyboard(array $buttons): Keyboard
    {
        $keyboard = new Keyboard();
        $keyboard->setBgColor("#665CAC");
        $converted = [];
        foreach ($buttons as $button) {
            $converted[] = $this->convertButton($button);
        }
        $keyboard->setButtons($converted);
        return $keyboard;
    }

    /**
     * @param $result
     * @return bool
     */
    private function checkRequestResult($result): bool
    {
        return $result instanceof Response && $result->getData()["status_message"] === "ok";
    }
}
