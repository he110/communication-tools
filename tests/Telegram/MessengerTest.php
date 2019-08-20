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
use He110\CommunicationTools\MessengerUser;
use He110\CommunicationTools\Request;
use He110\CommunicationTools\ScreenItems\Button;
use He110\CommunicationTools\Telegram\Messenger;
use He110\CommunicationToolsTests\ScreenItems\FileTest;
use He110\CommunicationToolsTests\ScreenItems\VoiceTest;
use PHPUnit\Framework\TestCase;

class MessengerTest extends TestCase
{
    /** @var string  */
    private $apiKey = "895440583:AAFMQ2yQrTH3JMgrCZ_fsCmrLhlgaCAQpeQ";

    /** @var int */
    private $targetUser = 62847152;

    /** @var Messenger */
    private $client;

    /** @var array */
    private $from = [
        "firstName" => "Ivan",
        "lastName" => "Ivanov",
        "username" => "IvanTest"
    ];

    /**
     * @covers \He110\CommunicationTools\Telegram\Messenger::sendMessage()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\Messenger::setTargetUser()
     * @covers \He110\CommunicationTools\Telegram\Messenger::getTargetUser()
     * @covers \He110\CommunicationTools\Telegram\Messenger::generateButtonMarkup()
     * @covers \He110\CommunicationTools\Telegram\Messenger::buttonToArray()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequestResult()
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
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\Messenger::setAccessToken()
     */
    public function testCheckRequirements()
    {
        $this->expectException(AccessTokenException::class);
        $this->client->setAccessToken(null);
        $this->client->sendMessage(__METHOD__);
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\Messenger::sendScreen()
     * @covers \He110\CommunicationTools\Telegram\Messenger::workWithScreenItem()
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
     * @covers \He110\CommunicationTools\Telegram\Messenger::sendVoice()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequirements()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequestResult()
     * @covers \He110\CommunicationTools\Telegram\Messenger::prepareFile()
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
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequestResult()
     * @covers \He110\CommunicationTools\Telegram\Messenger::prepareFile()
     * @covers \He110\CommunicationTools\Telegram\Messenger::generateButtonMarkup()
     * @covers \He110\CommunicationTools\Telegram\Messenger::buttonToArray()
     */
    public function testSendImage()
    {
        $this->assertTrue($this->client->sendImage(__DIR__."/../Assets/image.jpg"));
        $this->assertTrue($this->client->sendImage(__DIR__."/../Assets/image.jpg", __METHOD__));
        $this->assertTrue($this->client->sendImage(__DIR__."/../Assets/image.jpg", __METHOD__, $this->generateButtons()));

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
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkRequestResult()
     * @covers \He110\CommunicationTools\Telegram\Messenger::prepareFile()
     * @covers \He110\CommunicationTools\Telegram\Messenger::generateButtonMarkup()
     * @covers \He110\CommunicationTools\Telegram\Messenger::buttonToArray()
     */
    public function testSendDocument()
    {
        $this->assertTrue($this->client->sendDocument(__DIR__."/../Assets/image.jpg"));
        $this->assertTrue($this->client->sendDocument(__DIR__."/../Assets/image.jpg", __METHOD__));
        $this->assertTrue($this->client->sendDocument(__DIR__."/../Assets/image.jpg", __METHOD__, $this->generateButtons()));

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

    /**
     * @covers \He110\CommunicationTools\Telegram\Messenger::onMessage()
     * @covers \He110\CommunicationTools\Telegram\Messenger::getRequest()
     * @covers \He110\CommunicationTools\Telegram\Messenger::checkEvents()
     */
    public function testOnMessage()
    {
        $var = "before";
        $text = "Here is some text for test";

        $client = new MessengerDoubler();
        $client->setAccessToken($this->apiKey);
        $client->setTargetUser($this->targetUser);
        $client->setDataForInput($this->getTelegramRequestMockForMessage($text));
        $client->onMessage(function($request) use (&$var, $text) {
            /** @var Request $request */
            $var = "after";
            $this->assertEquals($this->from["firstName"], $request->getUser()->getFirstName());
            $this->assertEquals($this->from["lastName"], $request->getUser()->getLastName());
            $this->assertEquals($this->from["username"], $request->getUser()->getUsername());
            $this->assertEquals($text, $request->getMessage());
            $this->assertEquals(Request::REQUEST_TYPE_MESSAGE, $request->getType());
        });

        $this->assertEquals("before", $var);
        $client->checkEvents();
        $this->assertEquals("after", $var);
    }

    public function getTelegramRequestMockForMessage(string $text = "text"): string
    {
        $updateId = rand(0, 500);
        $messageId = rand(0, 500);
        $from = $this->from;
        $targetUser = $this->targetUser;
        $time = time();
        $text = addslashes($text);

        return <<<AOL
{
	"update_id": $updateId,
	"message": {
		"message_id": $messageId,
		"from": {
			"id": $targetUser,
			"is_bot": false,
			"first_name": "{$from["firstName"]}",
			"last_name": "{$from["lastName"]}",
			"username": "{$from["username"]}",
			"language_code": "ru"
		},
		"chat": {
			"id": $targetUser,
			"first_name": "{$from["firstName"]}",
			"last_name": "{$from["lastName"]}",
			"username": "{$from["username"]}",
			"type": "private"
		},
		"date": $time,
		"text": "$text"
	}
}
AOL;
    }

    /**
     * @return array
     */
    public function generateButtons(): array
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
