<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 16:38
 */

namespace He110\CommunicationTools\ScreenItems;


class Message implements ScreenItemInterface
{
    /** @var string */
    private $text;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text ?? "";
    }

    /**
     * @param string $text
     * @return Message
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getText();
    }

    /**
     * {@inheritdoc}
     */
    public function toArray(): array
    {
        return [
            "text" => $this->getText()
        ];
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
    static public function create(array $config)
    {
        $o = new Message();
        return $o->fromArray($config);
    }


}