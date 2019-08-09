<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 00:25
 */

namespace He110\CommunicationTools;


class MessengerScreen
{
    /** @var array */
    protected $content = [];

    public function addMessage(string $text): self
    {
        return $this;
    }

    public function addButtonText(string $label): self
    {
        return $this;
    }

    public function addButtonLink(string $label, string $url): self
    {
        return $this;
    }

    public function addButtonCallback(string $label, \Closure $closure): self
    {
        return $this;
    }

    public function addImage(string $pathToFile, string $description = ""): self
    {
        return $this;
    }

    public function addDocument(string $pathToFile, string $description = ""): self
    {
        return $this;
    }

    public function addVoice(string $pathToFile): self
    {
        return $this;
    }

    public function getContent(): array
    {
        return $this->content;
    }

    public function resetContent(): self
    {
        $this->content = [];
        return $this;
    }
}