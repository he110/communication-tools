<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 00:25
 */

namespace He110\CommunicationTools;


use He110\CommunicationTools\Exceptions\AttachmentNotFoundException;
use He110\CommunicationTools\ScreenItems\Button;
use He110\CommunicationTools\ScreenItems\File;
use He110\CommunicationTools\ScreenItems\Message;
use He110\CommunicationTools\ScreenItems\ScreenItemInterface;
use He110\CommunicationTools\ScreenItems\Voice;

class MessengerScreen
{
    /** @var ScreenItemInterface[] */
    protected $content = [];

    /**
     * @param string $text
     * @return MessengerScreen
     */
    public function addMessage(string $text): self
    {
        $this->content[] = Message::create(["text" => $text]);
        return $this;
    }

    /**
     * @param string $label
     * @return MessengerScreen
     */
    public function addButtonText(string $label): self
    {
        $this->content[] = Button::create([
            "label" => $label,
            "type" => Button::BUTTON_TYPE_TEXT
        ]);
        return $this;
    }

    /**
     * @param string $label
     * @param string $url
     * @return MessengerScreen
     */
    public function addButtonLink(string $label, string $url): self
    {
        $this->content[] = Button::create([
            "label" => $label,
            "type" => Button::BUTTON_TYPE_URL,
            "content" => $url
        ]);
        return $this;
    }

    /**
     * @param string $label
     * @param \Closure $closure
     * @return MessengerScreen
     */
    public function addButtonCallback(string $label, \Closure $closure): self
    {
        $this->content[] = Button::create([
            "label" => $label,
            "type" => Button::BUTTON_TYPE_CALLBACK,
            "content" => $closure
        ]);
        return $this;
    }

    /**
     * @param string $fileUrl
     * @param string $description
     * @return MessengerScreen
     * @throws AttachmentNotFoundException
     */
    public function addImage(string $fileUrl, string $description = ""): self
    {
        $this->checkFile($fileUrl);
        $file = File::create([
            "path" => $fileUrl,
            "description" => $description,
            "type" => File::FILE_TYPE_IMAGE
        ]);
        $this->content[] = $file;
        return $this;
    }

    /**
     * @param string $fileUrl
     * @param string $description
     * @return MessengerScreen
     * @throws AttachmentNotFoundException
     */
    public function addDocument(string $fileUrl, string $description = ""): self
    {
        $this->checkFile($fileUrl);
        $file = File::create([
            "path" => $fileUrl,
            "description" => $description,
            "type" => File::FILE_TYPE_DOCUMENT
        ]);
        $this->content[] = $file;
        return $this;
    }

    /**
     * @param string $pathToFile
     * @return MessengerScreen
     * @throws AttachmentNotFoundException
     */
    public function addVoice(string $pathToFile): self
    {
        $this->checkFile($pathToFile);
        $file = Voice::create([
            "path" => $pathToFile
        ]);
        $this->content[] = $file;
        return $this;
    }

    /**
     * @param bool $asArray
     * @return array
     */
    public function getContent(bool $asArray = true): array
    {
        $array = [];
        foreach($this->content as $item) {
            $array[] = $asArray ? $item->toArray() : $item;
        }
        return $array;
    }

    /**
     * @return MessengerScreen
     */
    public function resetContent(): self
    {
        $this->content = [];
        return $this;
    }

    /**
     * @param string $filePath - It can be static path to file or url
     * @throws AttachmentNotFoundException
     */
    private function checkFile(string $filePath): void
    {
        if (file_exists($filePath))
            return;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $filePath);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if ($result === FALSE) {
            throw new AttachmentNotFoundException("File not found");
        }
    }

    /**
     * @return array
     */
    public function fixItemsOrder(): array
    {
        $content = $this->getContent(false);
        $buttons = [];
        $lastItem = null;
        $acceptableItem = null;
        $this->fixItemsOrderHelper($content, $buttons, $lastItem, $acceptableItem);

        if ($buttons && $content[$lastItem] instanceof Voice)
            $this->fixItemsOrderSorter($acceptableItem, $lastItem, $content, $buttons);

        return $content;
    }

    /**
     * @param $acceptableItem
     * @param $lastItem
     * @param $content
     * @param $buttons
     */
    private function fixItemsOrderSorter($acceptableItem, $lastItem, &$content, $buttons): void
    {
        if ($acceptableItem === null) {
            Helpers::array_insert($content, $lastItem, [Message::create(["text" => "Use buttons"])]);
            $acceptableItem = $lastItem;
        }
        $content = array_slice($content, 0, count($content) - count($buttons));
        Helpers::array_insert($content, $acceptableItem + 1, $buttons);
    }

    /**
     * @param $content
     * @param $buttons
     * @param $lastItem
     * @param $acceptableItem
     */
    private function fixItemsOrderHelper($content, &$buttons, &$lastItem, &$acceptableItem): void
    {
        foreach ($content as $index => $item) {
            if ($item instanceof Button) {
                $buttons[] = $item;
            } else {
                $lastItem = $index;
                if (!($item instanceof Voice)) {
                    $acceptableItem = $lastItem;
                } else {
                    $buttons = [];
                }
            }
        }
    }
}