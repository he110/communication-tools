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
     */
    public function setAccessToken(?string $token);

    /**
     * @return string|null
     */
    public function getAccessToken(): ?string;
}