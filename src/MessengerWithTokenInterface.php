<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 00:22
 */

namespace He110\CommunicationTools;


interface MessengerWithTokenInterface
{
    /**
     * @param string $token
     * @return MessengerWithTokenInterface
     */
    public function setAccessToken(string $token): self;

    /**
     * @return string
     */
    public function getAccessToken(): string;
}