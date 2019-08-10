<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 16:51
 */

namespace He110\CommunicationToolsTests\ScreenItems;

use He110\CommunicationTools\ScreenItems\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @var Message */
    private $message;

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Message::setText()
     * @covers \He110\CommunicationTools\ScreenItems\Message::getText()
     */
    public function testSetText()
    {
        $this->assertEmpty($this->message->getText());
        $this->message->setText(__METHOD__);
        $this->assertEquals(__METHOD__, $this->message->getText());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Message::fromArray()
     * @covers \He110\CommunicationTools\ScreenItems\Message::getText()
     */
    public function testFromArray()
    {
        $this->assertEmpty($this->message->getText());
        $this->message->fromArray(["text" => __METHOD__]);
        $this->assertEquals(__METHOD__, $this->message->getText());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Message::create()
     */
    public function testCreate()
    {
        $ob = Message::create(["text" => __METHOD__]);
        $this->assertNotEquals($this->message, $ob);
        $this->assertEquals(Message::class, get_class($ob));
        $this->assertEquals(__METHOD__, $ob->getText());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Message::__toString()
     */
    public function test__toString()
    {
        $this->assertEmpty((string)$this->message);
        $this->message->setText(__METHOD__);
        $this->assertEquals(__METHOD__, (string)$this->message);
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Message::toArray()
     */
    public function testToArray()
    {
        $this->message->setText(__METHOD__);
        $this->assertArrayHasKey("text", $this->message->toArray());
        $this->assertEquals(__METHOD__, $this->message->toArray()["text"]);
    }

    public function setUp(): void
    {
        $this->message = new Message();
    }

    public function tearDown(): void
    {
        $this->message = null;
        unset($this->message);
    }
}
