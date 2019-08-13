<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 16:59
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\Exceptions\AttachmentNotFoundException;
use He110\CommunicationTools\MessengerScreen;
use He110\CommunicationTools\ScreenItems\Button;
use He110\CommunicationTools\ScreenItems\Message;
use He110\CommunicationTools\ScreenItems\Voice;
use He110\CommunicationToolsTests\ScreenItems\FileTest;
use He110\CommunicationToolsTests\ScreenItems\VoiceTest;
use PHPUnit\Framework\TestCase;

class MessengerScreenTest extends TestCase
{
    /** @var MessengerScreen */
    private $screen;

    const DESCRIPTION = "Test description";

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addDocument()
     * @covers \He110\CommunicationTools\MessengerScreen::checkFile()
     */
    public function testAddDocument()
    {
        $this->assertEmpty($this->screen->getContent());
        $this->screen->addDocument(FileTest::DOCUMENT_PATH, static::DESCRIPTION);
        $this->assertCount(1, $this->screen->getContent());
        list($ob) = $this->screen->getContent();
        $this->assertEquals(FileTest::DOCUMENT_PATH, $ob["path"]);
        $this->assertEquals(static::DESCRIPTION, $ob["description"]);
        $this->screen->resetContent();
        $this->assertEmpty($this->screen->getContent());

        try {
            $this->screen->addDocument("not_existed.file");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }
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

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addImage()
     * @covers \He110\CommunicationTools\MessengerScreen::checkFile()
     */
    public function testAddImage()
    {
        $this->assertEmpty($this->screen->getContent());
        $this->screen->addImage(FileTest::IMAGE_PATH);
        $this->assertCount(1, $this->screen->getContent());
        list($ob) = $this->screen->getContent();
        $this->assertEquals(FileTest::IMAGE_PATH, $ob["path"]);
        $this->screen->resetContent();
        $this->assertEmpty($this->screen->getContent());

        try {
            $this->screen->addImage("not_existed.file");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }
    }

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addButtonLink()
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

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::addVoice()
     * @covers \He110\CommunicationTools\MessengerScreen::getContent()
     * @covers \He110\CommunicationTools\MessengerScreen::checkFile()
     */
    public function testAddVoice()
    {
        $this->assertEmpty($this->screen->getContent());
        $this->screen->addVoice(VoiceTest::VOICE_OGG);
        $this->assertCount(1, $this->screen->getContent());
        list($ob) = $this->screen->getContent();
        $this->assertEquals(VoiceTest::VOICE_OGG, $ob["path"]);
        $this->screen->resetContent();
        $this->assertEmpty($this->screen->getContent());

        try {
            $this->screen->addVoice("not_existed.file");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }
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

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::fixItemsOrder()
     */
    public function testFixContentOrder()
    {
        $this->screen->addMessage(__METHOD__);
        $this->screen->addButtonText("Text button");

        $this->assertEquals($this->screen->getContent(false), $this->screen->fixItemsOrder());

        $this->screen->addVoice(VoiceTest::VOICE_OGG);

        $this->assertEquals($this->screen->getContent(false), $this->screen->fixItemsOrder());

        $this->screen->addButtonText("After voice");

        $fixed = $this->screen->fixItemsOrder();

        $this->assertNotEquals($this->screen->getContent(false), $fixed);

        $this->assertCount(4, $fixed);
        $this->assertInstanceOf(Message::class, $fixed[0]);
        $this->assertInstanceOf(Button::class, $fixed[1]);
        $this->assertInstanceOf(Button::class, $fixed[2]);
        $this->assertInstanceOf(Voice::class, $fixed[3]);

        $this->screen->resetContent();

        $this->screen->addVoice(VoiceTest::VOICE_OGG);
        $this->screen->addButtonText("Single button after voice");

        $fixed = $this->screen->fixItemsOrder();

        $this->assertCount(3, $fixed);
        $this->assertInstanceOf(Message::class, $fixed[0]);
        $this->assertInstanceOf(Button::class, $fixed[1]);
        $this->assertInstanceOf(Voice::class, $fixed[2]);
    }

    /**
     * @covers \He110\CommunicationTools\MessengerScreen::getContent()
     */
    public function testGetContent()
    {
        $this->screen->addMessage(__METHOD__);
        $this->assertCount(1, $this->screen->getContent());
        list($message) = $this->screen->getContent();
        $this->assertIsArray($message);

        list($message) = $this->screen->getContent(false);
        $this->assertInstanceOf(Message::class, $message);
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
