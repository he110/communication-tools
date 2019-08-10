<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 16:59
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\MessengerScreen;
use He110\CommunicationTools\ScreenItems\Button;
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

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addButtonText()
     * @covers \He110\CommunicationTools\MessengerScreen::getContent()
     */
    public function testAddButtonLink()
    {
        $url = "https://ya.ru";
        $this->assertEmpty($this->screen->getContent());
        $this->screen->addButtonLink(__METHOD__, $url);
        $this->assertCount(1, $this->screen->getContent());
        list($item) = $this->screen->getContent();
        $this->assertEquals(__METHOD__, $item["label"]);
        $this->assertEquals(Button::BUTTON_TYPE_URL, $item["type"]);
        $this->assertEquals($url, $item["url"]);
    }

    public function testAddVoice()
    {

    }

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addButtonText()
     * @covers \He110\CommunicationTools\MessengerScreen::getContent()
     */
    public function testAddButtonText()
    {
        $this->assertEmpty($this->screen->getContent());
        $this->screen->addButtonText(__METHOD__);
        $this->assertCount(1, $this->screen->getContent());
        list($item) = $this->screen->getContent();
        $this->assertEquals(__METHOD__, $item["label"]);
        $this->assertEquals(Button::BUTTON_TYPE_TEXT, $item["type"]);
    }

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addButtonCallback()
     * @covers \He110\CommunicationTools\MessengerScreen::getContent()
     */
    public function testAddButtonCallback()
    {
        $this->assertEmpty($this->screen->getContent());
        $this->screen->addButtonCallback(__METHOD__, function ($a) { echo __METHOD__; });
        $this->assertCount(1, $this->screen->getContent());
        list($item) = $this->screen->getContent();
        $this->assertEquals(__METHOD__, $item["label"]);
        $this->assertEquals(Button::BUTTON_TYPE_CALLBACK, $item["type"]);
        $this->assertIsCallable($item["content"]);
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
