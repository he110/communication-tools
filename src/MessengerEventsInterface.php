<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 00:17
 */

namespace He110\CommunicationTools;


interface MessengerEventsInterface
{
    public function onMessage(\Closure $closure);

    public function onMessageRead(\Closure $closure);

    public function onMessageEdit(\Closure $closure);

    public function onMessageDelete(\Closure $closure);

    public function onButtonClick(\Closure $closure);

    static public function eventMessage();

    static public function eventMessageRead();

    static public function eventMessageEdited();

    static public function eventMessageDeleted();

    static public function eventButtonClicked();
}