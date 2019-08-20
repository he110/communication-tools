<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 20/08/2019
 * Time: 23:00
 */

namespace He110\CommunicationToolsTests\Telegram;


use He110\CommunicationTools\Telegram\Messenger;

class MessengerDoubler extends Messenger
{
    /** @var string */
    private $data;

    /**
     * @param string $data
     * @return MessengerDoubler
     */
    public function setDataForInput(string $data): self
    {
        $this->data = $data;
        return $this;
    }

    protected function getPhpInput(): string
    {
        return $this->data;
    }

}