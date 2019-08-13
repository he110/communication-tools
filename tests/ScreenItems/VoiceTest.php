<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 13/08/2019
 * Time: 15:07
 */

namespace He110\CommunicationToolsTests\ScreenItems;

use He110\CommunicationTools\Exceptions\AttachmentNotFoundException;
use He110\CommunicationTools\ScreenItems\Voice;
use PHPUnit\Framework\TestCase;

class VoiceTest extends TestCase
{
    /** @var Voice */
    private $voice;

    /** @var string  */
    const VOICE_OGG = __DIR__."/../Assets/voice.ogg";

    /** @var string  */
    const VOICE_WAV = __DIR__."/../Assets/voice.wav";

    /** @var string  */
    const RECOGNIZED_TEXT = "recognized";

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Voice::getPath()
     * @covers \He110\CommunicationTools\ScreenItems\Voice::setPath()
     */
    public function testGetPath()
    {
        $this->assertNull($this->voice->getPath());
        $this->voice->setPath(static::VOICE_OGG);
        $this->assertEquals(static::VOICE_OGG, $this->voice->getPath());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Voice::toArray()
     * @covers \He110\CommunicationTools\ScreenItems\Voice::setPath()
     */
    public function testToArray()
    {
        $this->voice->setPath(static::VOICE_OGG);
        $this->assertCount(2, $this->voice->toArray());
        $this->assertArrayHasKey("path", $this->voice->toArray());
        $this->assertArrayHasKey("text", $this->voice->toArray());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Voice::create()
     * @covers \He110\CommunicationTools\ScreenItems\Voice::fromArray()
     */
    public function testCreate()
    {
        $ob = Voice::create(["path" => static::VOICE_OGG]);
        $this->assertNotEquals($this->voice, $ob);
        $this->assertEquals(Voice::class, get_class($ob));
        $this->assertEquals(static::VOICE_OGG, $ob->getPath());
        $this->assertEquals(static::RECOGNIZED_TEXT, $ob->getText());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\Voice::__toString()
     * @covers \He110\CommunicationTools\ScreenItems\Voice::getText()
     * @covers \He110\CommunicationTools\ScreenItems\Voice::setText()
     * @covers \He110\CommunicationTools\ScreenItems\Voice::recognize()
     */
    public function testGetText()
    {
        $this->assertEmpty($this->voice->getText());
        try {
            $this->voice->getText();
        } catch (\Exception $e) {
            $this->assertEquals(AttachmentNotFoundException::class, get_class($e));
        }
        $this->voice->setPath(static::VOICE_OGG);
        $this->assertEquals(static::RECOGNIZED_TEXT, $this->voice->getText());
        $this->assertEquals((string)$this->voice, $this->voice->getText());
    }

    public function setUp(): void
    {
        $this->voice = new Voice();
    }

    public function tearDown(): void
    {
        $this->voice = null;
        unset($this->voice);
    }
}
