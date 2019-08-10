<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 16:59
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\MessengerScreen;
use PHPUnit\Framework\TestCase;

class MessengerScreenTest extends TestCase
{
    /** @var MessengerScreen */
    private $screen;

    public function testAddDocument()
    {

    }

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addMessage()
     * @covers \He110\CommunicationTools\MessengerScreen::getContent()
     * @covers \He110\CommunicationTools\MessengerScreen::resetContent()
     */
    public function testAddMessage()
    {
        $this->assertEmpty($this->screen->getContent());
        $this->screen->addMessage(__METHOD__);
        $this->assertCount(1, $this->screen->getContent());
        list($message) = $this->screen->getContent();
        $this->assertEquals(__METHOD__, $message["text"]);
        $this->screen->resetContent();
        $this->assertEmpty($this->screen->getContent());
    }

    public function testAddImage()
    {

    }

    public function testAddButtonLink()
    {

    }

    public function testAddVoice()
    {

    }

    public function testAddButtonText()
    {

    }

    public function testAddButtonCallback()
    {

    }

    public function setUp(): void
    {
        $this->screen = new MessengerScreen();
    }

    public function tearDown(): void
    {
        $this->screen = null;
        unset($this->screen);
    }
}
