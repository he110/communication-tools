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
     * @param string $pathToFile
     * @param string $description
     * @return MessengerScreen
     * @throws AttachmentNotFoundException
     */
    public function addImage(string $pathToFile, string $description = ""): self
    {
        if ($file = File::create([
            "path" => $pathToFile,
            "description" => $description,
            "type" => File::FILE_TYPE_IMAGE
        ])) {
            $this->content[] = $file;
            return $this;
        }
        throw new AttachmentNotFoundException("File not found");
    }

    /**
     * @param string $pathToFile
     * @param string $description
     * @return MessengerScreen
     * @throws AttachmentNotFoundException
     */
    public function addDocument(string $pathToFile, string $description = ""): self
    {
        if ($file = File::create([
            "path" => $pathToFile,
            "description" => $description,
            "type" => File::FILE_TYPE_DOCUMENT
        ])) {
            $this->content[] = $file;
            return $this;
        }
        throw new AttachmentNotFoundException("File not found");
    }

    public function addVoice(string $pathToFile): self
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getContent(): array
    {
        $array = [];
        foreach($this->content as $item)
            $array[] = $item->toArray();
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
}