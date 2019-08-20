<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 13/08/2019
 * Time: 15:01
 */

namespace He110\CommunicationTools\ScreenItems;

class Voice implements ScreenItemInterface
{
    /** @var string */
    private $text;

    /** @var string */
    private $path;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text ?? "";
    }

    /**
     * @param string $text
     * @return Voice
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPath(): ?string
    {
        return $this->path ?? null;
    }

    /**
     * @param string $path
     * @return Voice
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array(
            "path" => $this->getPath(),
            "text" => $this->getText()
        );
    }

    /**
     * @param array $data
     * @return $this
     */
    public function fromArray(array $data)
    {
        foreach($data as $key=>$value) {
            $methodName = "set".ucfirst($key);
            if (method_exists($this, $methodName) && is_callable([$this, $methodName]))
                $this->{$methodName}($value);
        }
        return $this;
    }

    /**
     * @param array $config
     * @return Voice
     */
    static public function create(array $config)
    {
        return (new Voice())->fromArray($config);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getText();
    }

}