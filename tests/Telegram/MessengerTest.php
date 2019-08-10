<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 17:48
 */

namespace He110\CommunicationToolsTests\Telegram;

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
     */
    public function testSendMessage()
    {
        $this->assertTrue($this->client->sendMessage(__METHOD__));

        $this->client->setTargetUser("123456");
        $this->assertFalse($this->client->sendMessage(__METHOD__));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendMessage(__METHOD__);
    }

    public function testSendScreen()
    {

    }

    public function testGetTargetUser()
    {

    }

    public function testSetTargetUser()
    {

    }

    public function testSendVoice()
    {

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

    public function testSendDocument()
    {

    }

    public function testSendImage()
    {

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
