<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 17:20
 */

namespace He110\CommunicationToolsTests\ScreenItems;

use He110\CommunicationTools\ScreenItems\Button;
use PHPUnit\Framework\TestCase;

class ButtonTest extends TestCase
{
    /** @var Button */
    private $button;

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Button::__toString()
     * @covers \He110\CommunicationTools\ScreenItems\Button::getLabel()
     * @covers \He110\CommunicationTools\ScreenItems\Button::setLabel()
     */
    public function test__toString()
    {
        $this->assertEmpty($this->button->getLabel());
        $this->assertEmpty((string)$this->button);
        $this->button->setLabel(__METHOD__);
        $this->assertEquals(__METHOD__, $this->button->getLabel());
        $this->assertEquals((string)$this->button, $this->button->getLabel());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Button::setContent()
     * @covers \He110\CommunicationTools\ScreenItems\Button::getContent()
     */
    public function testGetContent()
    {
        $this->assertEmpty($this->button->getContent());
        $this->button->setContent("empty one");
        $this->assertEquals("empty one", $this->button->getContent());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Button::toArray()
     * @covers \He110\CommunicationTools\ScreenItems\Button::fromArray()
     */
    public function testToArray()
    {
        $this->button->fromArray([
            "label" => __METHOD__,
            "type" => Button::BUTTON_TYPE_URL,
            "content" => "https://ya.ru"
        ]);

        $result = $this->button->toArray();
        $this->assertArrayHasKey("label", $result);
        $this->assertArrayHasKey("type", $result);
        $this->assertArrayHasKey("content", $result);
        $this->assertArrayHasKey("url", $result);

        $this->button->setType(Button::BUTTON_TYPE_TEXT);
        $this->assertArrayNotHasKey("content", $this->button->toArray());
        $this->assertArrayNotHasKey("url", $this->button->toArray());

        $this->button->fromArray([
            "type" => Button::BUTTON_TYPE_CALLBACK,
            "content" => function($a) { echo __METHOD__; }
        ]);

        $this->assertArrayHasKey("content", $this->button->toArray());
        $this->assertArrayHasKey("callback", $this->button->toArray());
        $this->assertIsCallable($this->button->getContent());
    }

    /**
     * @dataProvider fromArrayProvider
     *
     * @covers \He110\CommunicationTools\ScreenItems\Button::fromArray()
     * @covers \He110\CommunicationTools\ScreenItems\Button::getLabel()
     * @covers \He110\CommunicationTools\ScreenItems\Button::getType()
     * @covers \He110\CommunicationTools\ScreenItems\Button::getContent()
     */
    public function testFromArray(string $type, $content)
    {
        $config = [
            "label" => __METHOD__,
            "type" => $type
        ];
        if ($type !== Button::BUTTON_TYPE_TEXT)
            $config["content"] = $content;
        $this->button->fromArray($config);

        $this->assertEquals(__METHOD__, $this->button->getLabel());
        $this->assertEquals($type, $this->button->getType());
        if ($type !== Button::BUTTON_TYPE_TEXT)
            $this->assertEquals($content, $this->button->getContent());

        if ($type === Button::BUTTON_TYPE_CALLBACK && is_callable($content))
            $this->assertIsCallable($this->button->getContent());
    }

    public function fromArrayProvider()
    {
        return array(
            array(Button::BUTTON_TYPE_TEXT, null),
            array(Button::BUTTON_TYPE_URL, "https://ya.ru"),
            array(Button::BUTTON_TYPE_CALLBACK, function($a) { echo __METHOD__; }),
        );
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Button::create()
     */
    public function testCreate()
    {
        $ob = Button::create(["label" => __METHOD__]);
        $this->assertNotEquals($this->button, $ob);
        $this->assertEquals(Button::class, get_class($ob));
        $this->assertEquals(__METHOD__, $ob->getLabel());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Button::setType()
     * @covers \He110\CommunicationTools\ScreenItems\Button::getType()
     */
    public function testSetType()
    {
        $this->assertEquals(Button::BUTTON_TYPE_TEXT, $this->button->getType());
        $this->button->setType(Button::BUTTON_TYPE_URL);
        $this->assertEquals(Button::BUTTON_TYPE_URL, $this->button->getType());
    }

    public function setUp(): void
    {
        $this->button = new Button();
    }

    public function tearDown(): void
    {
        $this->button = null;
        unset($this->button);
    }
}
