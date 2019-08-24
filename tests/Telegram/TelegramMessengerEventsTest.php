<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 21/08/2019
 * Time: 03:12
 */

namespace He110\CommunicationToolsTests\Telegram;

use He110\CommunicationTools\Request;
use He110\CommunicationTools\Telegram\TelegramMessenger;
use PHPUnit\Framework\TestCase;

class TelegramMessengerEventsTest extends TestCase
{
    /** @var TelegramMessenger */
    private $client;

    /** @var array */
    private $from = [
        "firstName" => "Ivan",
        "lastName" => "Ivanov",
        "username" => "IvanTest"
    ];

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::onMessage()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::getRequest()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::checkEvents()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::addEvent()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::detectPayloadType()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::setUserFromRequest()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::buildMessageRequest()
     */
    public function testOnMessage()
    {
        $var = "before";
        $text = "Here is some text for test";

        $client = new TelegramMessengerDoubler();
        $client->setAccessToken(TelegramMessengerTest::API_KEY);
        $client->setTargetUser(TelegramMessengerTest::TARGET_USER);
        $client->setDataForInput($this->getTelegramRequestMockForMessage($text));
        $client->onMessage(function($request) use (&$var, $text) {
            /** @var Request $request */
            $var = "after";
            $this->checkRequestUser($request);
            $this->assertEquals($text, $request->getMessage());
            $this->assertEquals(Request::REQUEST_TYPE_MESSAGE, $request->getType());
        });

        $this->assertEquals("before", $var);
        $client->checkEvents();
        $this->assertEquals("after", $var);
    }

    /**
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::onButtonClick()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::getRequest()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::checkEvents()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::addEvent()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::detectPayloadType()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::setUserFromRequest()
     * @covers \He110\CommunicationTools\Telegram\TelegramMessengerEvents::buildButtonClickRequest()
     */
    public function testOnButtonClick()
    {
        $var = "before";

        $client = new TelegramMessengerDoubler();
        $client->setAccessToken(TelegramMessengerTest::API_KEY);
        $client->setTargetUser(TelegramMessengerTest::TARGET_USER);
        $client->setDataForInput($this->getTelegramRequestMockForCallback());
        $client->onButtonClick(function($request) use (&$var) {
            /** @var Request $request */
            $var = "after";
            $this->checkRequestUser($request);
            $this->assertEmpty($request->getMessage());
            $this->assertEquals("callbackFunctionText", $request->getPayload());
            $this->assertEquals(Request::REQUEST_TYPE_BUTTON_CLICK, $request->getType());
        });

        $this->assertEquals("before", $var);
        $client->checkEvents();
        $this->assertEquals("after", $var);

        $buttonText = "Message callback";
        $client->setDataForInput($this->getTelegramRequestMockForCallback("text=$buttonText"));
        $client->onButtonClick(function($request) use ($buttonText) {
            /** @var Request $request */
            $this->checkRequestUser($request);
            $this->assertNotEmpty($request->getMessage());
            $this->assertNull($request->getPayload());
            $this->assertEquals(Request::REQUEST_TYPE_BUTTON_CLICK, $request->getType());
            $this->assertEquals($buttonText, $request->getMessage());
        });

        $client->checkEvents();

        $client->setDataForInput($this->getTelegramRequestMockForCallback("invalid callback"));
        $client->getRequest();
    }

    /**
     * @param Request $request
     */
    private function checkRequestUser(Request &$request): void
    {
        $this->assertEquals($this->from["firstName"], $request->getUser()->getFirstName());
        $this->assertEquals($this->from["lastName"], $request->getUser()->getLastName());
        $this->assertEquals($this->from["username"], $request->getUser()->getUsername());
    }

    /**
     * @param string $text
     * @return string
     */
    public function getTelegramRequestMockForMessage(string $text = "text"): string
    {
        $updateId = rand(0, 500);
        $messageId = rand(0, 500);
        $from = $this->from;
        $targetUser = TelegramMessengerTest::TARGET_USER;
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
     * @param string $callback
     * @return string
     */
    public function getTelegramRequestMockForCallback(string $callback = "clb=callbackFunctionText"): string
    {
        $updateId = rand(0, 500);
        $messageId = rand(0, 500);
        $from = $this->from;
        $targetUser = TelegramMessengerTest::TARGET_USER;
        $time = time();
        $callback = addslashes($callback);

        return <<<AOL
{
	"update_id": $updateId,
	"callback_query": {
		"id": "269926465975705956",
		"from": {
			"id": $targetUser,
			"is_bot": false,
			"first_name": "{$from["firstName"]}",
			"last_name": "{$from["lastName"]}",
			"username": "{$from["username"]}",
			"language_code": "ru"
		},
		"message": {
			"message_id": $messageId,
			"from": {
				"id": 11111111,
				"is_bot": true,
				"first_name": "Bot Name",
				"username": "DemoMockBot"
			},
			"chat": {
				"id": $targetUser,
				"first_name": "{$from["firstName"]}",
				"last_name": "{$from["lastName"]}",
				"username": "{$from["username"]}",
				"type": "private"
			},
			"date": $time,
			"document": {
				"file_name": "image.jpg",
				"mime_type": "image/jpeg",
				"thumb": {
					"file_id": "AAQCAAPJAwACMbzpSjEoI32WysFvoBe7DwAEAQAHbQADbbgAAhYE",
					"file_size": 14733,
					"width": 320,
					"height": 320
				},
				"file_id": "BQADAgADyQMAAjG86UoxKCN9lsrBbxYE",
				"file_size": 39481
			},
			"caption": "Document caption",
			"reply_markup": {
				"inline_keyboard": [
					[{
						"text": "Link",
						"url": "https://zobenko.ru/"
					}],
					[{
						"text": "Text",
						"callback_data": "text=Text"
					}],
					[{
						"text": "Callback",
						"callback_data": "clb=callbackFunctionText"
					}]
				]
			}
		},
		"chat_instance": "6200504894528308608",
		"data": "$callback"
	}
}
AOL;
    }

    
    public function setUp(): void
    {
        $this->client = new TelegramMessenger();
        $this->client->setAccessToken(TelegramMessengerTest::API_KEY);
        $this->client->setTargetUser(TelegramMessengerTest::TARGET_USER);
    }

    public function tearDown(): void
    {
        $this->client = null;
        unset($this->client);
    }
}
