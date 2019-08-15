<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 16/08/2019
 * Time: 01:52
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\EventController;
use PHPUnit\Framework\TestCase;

class EventControllerTest extends TestCase
{
    /**
     * @covers \He110\CommunicationTools\EventController::getInstance()
     */
    public function testGetInstance()
    {
        $eventController = EventController::getInstance();
        $eventController->addEvent("test", function () { echo "TEST"; });
        $another = EventController::getInstance();
        $this->assertEquals($eventController, $another);
    }

    /**
     * @covers \He110\CommunicationTools\EventController::addEvent()
     * @covers \He110\CommunicationTools\EventController::getEvent()
     */
    public function testAddEvent()
    {
        $controller = EventController::getInstance();
        $key = "telegram_onMessage";
        $controller->addEvent($key, function() {
            echo __METHOD__;
        });

        $closure = $controller->getEvent($key);
        $this->assertIsCallable($closure);

        $this->isNull($controller->getEvent("not_existed"));
    }
}
