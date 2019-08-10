<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 17:48
 */

namespace He110\CommunicationToolsTests\Telegram;

use He110\CommunicationTools\Exceptions\AttachmentNotFoundException;
use He110\CommunicationTools\Exceptions\TargetUserException;
use He110\CommunicationTools\Telegram\Messenger;
use PHPUnit\Framework\TestCase;

class MessengerTest extends TestCase
{
    /** @var string  */
    private $apiKey = "895440583:AAFMQ2yQrTH3JMgrCZ_fsCmrLhlgaCAQpeQ";

    /** @var int */
    private $targetUser = 62847152;

    /** @var Messenger */
    private $client;

    /**
     * @covers \He110\CommunicationTools\Telegram\Messenger::sendMessage()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\Messenger::setTargetUser()
     * @covers \He110\CommunicationTools\Telegram\Messenger::getTargetUser()
     */
    public function testSendMessage()
    {
        $this->assertTrue($this->client->sendMessage(__METHOD__));

        $this->client->setTargetUser("123456");
        $this->assertEquals("123456", $this->client->getTargetUser());
        $this->assertFalse($this->client->sendMessage(__METHOD__));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendMessage(__METHOD__);
    }

    public function testSendScreen()
    {

    }

    /**
     * @covers \He110\CommunicationTools\Telegram\Messenger::sendVoice()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequirements()
     * @covers \He110\CommunicationTools\ScreenItems\Message::checkRequestResult()
     * @covers \He110\CommunicationTools\ScreenItems\Message::prepareFile
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
     * @covers \He110\CommunicationTools\Telegram\Messenger::setAccessToken()
     * @covers \He110\CommunicationTools\Telegram\Messenger::getAccessToken()
     */
    public function testSetAccessToken()
    {
        $this->assertEquals($this->apiKey, $this->client->getAccessToken());
        $newToken = md5(rand(1,100));

        $this->client->setAccessToken($newToken);
        $this->assertEquals($newToken, $this->client->getAccessToken());
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\Messenger::sendImage()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequirements()
     * @covers \He110\CommunicationTools\ScreenItems\Message::checkRequestResult()
     * @covers \He110\CommunicationTools\ScreenItems\Message::prepareFile
     */
    public function testSendImage()
    {
        $this->assertTrue($this->client->sendImage(__DIR__."/../Assets/image.jpg"));
        $this->assertTrue($this->client->sendImage(__DIR__."/../Assets/image.jpg", __METHOD__));

        try {
            $this->client->sendImage(__DIR__ . "/../Assets/not_existed.jpg");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }

        $this->client->setTargetUser("123456");
        $this->assertFalse($this->client->sendImage(__DIR__."/../Assets/image.jpg"));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendImage(__DIR__."/../Assets/image.jpg");
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\Messenger::sendDocument()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequirements()
     * @covers \He110\CommunicationTools\ScreenItems\Message::checkRequestResult()
     * @covers \He110\CommunicationTools\ScreenItems\Message::prepareFile
     */
    public function testSendDocument()
    {
        $this->assertTrue($this->client->sendDocument(__DIR__."/../Assets/image.jpg"));
        $this->assertTrue($this->client->sendDocument(__DIR__."/../Assets/image.jpg", __METHOD__));

        try {
            $this->client->sendDocument(__DIR__ . "/../Assets/not_existed.jpg");
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }

        $this->client->setTargetUser("123456");
        $this->assertFalse($this->client->sendDocument(__DIR__."/../Assets/image.jpg"));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendDocument(__DIR__."/../Assets/image.jpg");
    }

    public function setUp(): void
    {
        $this->client = new Messenger();
        $this->client->setAccessToken($this->apiKey);
        $this->client->setTargetUser($this->targetUser);
    }

    public function tearDown(): void
    {
        $this->client = null;
        unset($this->client);
    }
}
