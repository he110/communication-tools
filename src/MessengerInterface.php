<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 00:20
 */

namespace He110\CommunicationTools;


interface MessengerInterface
{
    /**
     * Allows you to set target user ID.
     * It can be Telegram Client ID, or Viber User Token and other.
     *
     * @param string|null $userId
     * @return mixed
     */
    public function setTargetUser(?string $userId);

    /**
     * Get target user ID.
     *
     * @return string
     */
    public function getTargetUser(): ?string;

    /**
     * Method, which sends a simple text messages.
     * It can also send buttons
     *
     * @param string $text
     * @param array $buttons
     * @return bool
     */
    public function sendMessage(string $text, array $buttons = []): bool;

    /**
     * Sends picture with caption (empty by default).
     * It can also send buttons
     *
     * @param string $pathToFile - Static path to target file
     * @param string|null $description - Caption text
     * @param array $buttons
     * @return bool
     */
    public function sendImage(string $pathToFile, string $description = null, array $buttons = []): bool;

    /**
     * Sends document with caption (empty by default).
     * It can also send buttons
     *
     * @param string $pathToFile - Static path to target file
     * @param string|null $description - Caption text
     * @param array $buttons
     * @return bool
     */
    public function sendDocument(string $pathToFile, string $description = null, array $buttons = []): bool;

    /**
     * Tools for voice message sending
     *
     * @param string $pathToFile - Static path to target file
     * @return bool
     */
    public function sendVoice(string $pathToFile): bool;

    /**
     * Main method, which builds full request to send all messenger content
     *
     * @param MessengerScreen $screen
     * @return bool
     */
    public function sendScreen(MessengerScreen $screen): bool;
}