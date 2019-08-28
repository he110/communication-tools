<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 17:48
 */

namespace He110\CommunicationToolsTests\Telegram;

use He110\CommunicationTools\Exceptions\AccessTokenException;
use He110\CommunicationTools\Exceptions\AttachmentNotFoundException;
use He110\CommunicationTools\Exceptions\TargetUserException;
use He110\CommunicationTools\MessengerScreen;
use He110\CommunicationTools\ScreenItems\Button;
use He110\CommunicationTools\Telegram\TelegramMessenger;
use He110\CommunicationToolsTests\ScreenItems\FileTest;
use He110\CommunicationToolsTests\ScreenItems\VoiceTest;
use PHPUnit\Framework\TestCase;

class TelegramMessengerTest extends TestCase
{
    /** @var string  */
    const API_KEY = "895440583:AAFMQ2yQrTH3JMgrCZ_fsCmrLhlgaCAQpeQ";

    /** @var int */
    const TARGET_USER = 62847152;

    /** @var TelegramMessenger */
    private $client;

    /** @var string */
    public static $imageUrl = "https://pm1.narvii.com/6958/8caff7ac87a301e39d22a40b82ff03943d32e54dr1-900-720v2_128.jpg";

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::sendMessage()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::setTargetUser()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::getTargetUser()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::generateButtonMarkup()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::buttonToArray()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequestResult()
     */
    public function testSendMessage()
    {
        $this->assertTrue($this->client->sendMessage(__METHOD__));


        $this->assertTrue($this->client->sendMessage(__METHOD__, $this->generateButtons()));

        $this->client->setTargetUser("123456");
        $this->assertEquals("123456", $this->client->getTargetUser());
        $this->assertFalse($this->client->sendMessage(__METHOD__));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendMessage(__METHOD__);
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::setAccessToken()
     */
    public function testCheckRequirements()
    {
        $this->expectException(AccessTokenException::class);
        $this->client->setAccessToken(null);
        $this->client->sendMessage(__METHOD__);
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::sendScreen()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::workWithScreenItem()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::sendScreenHelper()
     */
    public function testSendScreen()
    {
        $screen = new MessengerScreen();
        $screen->addMessage(__METHOD__);
        $screen->addButtonLink("Author", "https://zobenko.ru");
        $screen->addButtonText("Hello, world");
        $screen->addImage(FileTest::IMAGE_PATH, "Description");
        $screen->addVoice(VoiceTest::VOICE_WAV);

        $this->assertTrue($this->client->sendScreen($screen));
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::sendVoice()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequestResult()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::prepareFile()
     */
    public function testSendVoice()
    {
        $this->assertTrue($this->client->sendVoice(__DIR__."/../Assets/voice.ogg"));

        try {
            $this->client->sendVoice(__DIR__ . "/../Assets/not_existed.ogg");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }

        $this->client->setTargetUser("123456");
        $this->assertFalse($this->client->sendVoice(__DIR__."/../Assets/voice.ogg"));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendVoice(__DIR__."/../Assets/voice.ogg");
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::setAccessToken()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::getAccessToken()
     */
    public function testSetAccessToken()
    {
        $this->assertEquals(static::API_KEY, $this->client->getAccessToken());
        $newToken = md5(rand(1,100));

        $this->client->setAccessToken($newToken);
        $this->assertEquals($newToken, $this->client->getAccessToken());
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::sendImage()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequestResult()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::prepareFile()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::generateButtonMarkup()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::buttonToArray()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::workWithAttachment()
     */
    public function testSendImage()
    {
        $this->assertTrue($this->client->sendImage(static::$imageUrl));
        $this->assertTrue($this->client->sendImage(static::$imageUrl, __METHOD__));
        $this->assertTrue($this->client->sendImage(static::$imageUrl, __METHOD__, $this->generateButtons()));

        try {
            $this->client->sendImage("incorrect_url");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }

        $this->client->setTargetUser("123456");
        $this->assertFalse($this->client->sendImage(static::$imageUrl));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendImage(static::$imageUrl);
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::sendDocument()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::checkRequestResult()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::prepareFile()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::generateButtonMarkup()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::buttonToArray()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessenger::workWithAttachment()
     */
    public function testSendDocument()
    {
        $this->assertTrue($this->client->sendDocument(static::$imageUrl));
        $this->assertTrue($this->client->sendDocument(static::$imageUrl, __METHOD__));
        $this->assertTrue($this->client->sendDocument(static::$imageUrl, __METHOD__, $this->generateButtons()));

        try {
            $this->client->sendDocument("invalid_url");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }

        $this->client->setTargetUser("123456");
        $this->assertFalse($this->client->sendDocument(static::$imageUrl));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendDocument(static::$imageUrl);
    }

    /**
     * @return array
     */
    public static function generateButtons(): array
    {
        return [
            Button::create([
                "type" => Button::BUTTON_TYPE_URL,
                "label" => "Link",
                "content" => "https://zobenko.ru"
            ]),
            Button::create([
                "type" => Button::BUTTON_TYPE_TEXT,
                "label" => "Text"
            ]),
            Button::create([
                "type" => Button::BUTTON_TYPE_CALLBACK,
                "label" => "Callback",
                "content" => "callbackFunctionText"
            ])
        ];
    }

    public function setUp(): void
    {
        $this->client = new TelegramMessenger();
        $this->client->setAccessToken(static::API_KEY);
        $this->client->setTargetUser(static::TARGET_USER);
    }

    public function tearDown(): void
    {
        $this->client = null;
        unset($this->client);
    }
}
