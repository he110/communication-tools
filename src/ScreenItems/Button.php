<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 17:10
 */

namespace He110\CommunicationTools\ScreenItems;


class Button implements ScreenItemInterface
{
    const BUTTON_TYPE_TEXT = "text";
    const BUTTON_TYPE_URL = "url";
    const BUTTON_TYPE_CALLBACK = "callback";

    /** @var string */
    private $label;

    /** @var string|\Closure */
    private $content;

    /** @var string */
    private $type;

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Button
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return \Closure|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \Closure|string $content
     * @return Button
     */
    public function setContent($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Button
     */
    public function setType(string $type): self
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        switch ($this->getType()) {
            case static::BUTTON_TYPE_CALLBACK:
                $key = "callback";
                break;
            case static::BUTTON_TYPE_URL:
                $key = "url";
                break;
            default:
                $key = null;
                break;
        }
        $result = [
            "type" => $this->getType(),
            "label" => $this->getLabel()
        ];
        if (!is_null($key))
            $result[$key] = $result["content"] = $this->getContent();
        return $result;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    static public function create(array $config): self
    {
        $ob = new Button();
        return $ob->fromArray($config);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getLabel();
    }

}