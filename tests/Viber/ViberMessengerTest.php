<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 2019-08-24
 * Time: 20:06
 */

namespace He110\CommunicationToolsTests\Viber;

use He110\CommunicationTools\Exceptions\TargetUserException;
use He110\CommunicationTools\Viber\ViberMessenger;
use He110\CommunicationToolsTests\Telegram\TelegramMessengerTest;
use PHPUnit\Framework\TestCase;

class ViberMessengerTest extends TestCase
{
    /** @var ViberMessenger */
    private $client;

    /** @var string */
    private $apiKey = "495d19d8f2a7d07f-b8ed66b8db10f49f-25409b15de4b0b64";

    /** @var string */
    private $targetUser = "F2gYDOM9yuutTYK3BWWChA==";

    /**
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::setTargetUser
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::getTargetUser
     */
    public function testSetTargetUser()
    {
        $this->assertEquals($this->targetUser, $this->client->getTargetUser());
        $this->client->setTargetUser(__METHOD__);
        $this->assertEquals(__METHOD__, $this->client->getTargetUser());
    }

    /**
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::sendMessage
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::checkRequirements
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::setTargetUser
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::getTargetUser
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::convertButton
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::createViberKeyboard
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::createViberButton
     */
    public function testSendMessage()
    {
        $this->assertTrue($this->client->sendMessage(__METHOD__));

        $this->assertTrue($this->client->sendMessage(__METHOD__, TelegramMessengerTest::generateButtons()));

        $this->client->setTargetUser("123456");
        $this->assertEquals("123456", $this->client->getTargetUser());
        $this->assertFalse($this->client->sendMessage(__METHOD__));

        $this->client->setTargetUser(null);
        $this->expectException(TargetUserException::class);
        $this->client->sendMessage(__METHOD__);
    }

    /**
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::setAccessToken
     * @covers \He110\CommunicationTools\Viber\ViberMessenger::getAccessToken
     */
    public function testSetAccessToken()
    {
        $this->assertEquals($this->apiKey, $this->client->getAccessToken());
        $this->client->setAccessToken(__METHOD__);
        $this->assertEquals(__METHOD__, $this->client->getAccessToken());
    }

    public function setUp(): void
    {
        $this->client = new ViberMessenger();
        $this->client->setAccessToken($this->apiKey);
        $this->client->setTargetUser($this->targetUser);
    }

    public function tearDown(): void
    {
        $this->client = null;
        unset($this->client);
    }
}
