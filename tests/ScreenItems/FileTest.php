<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 12/08/2019
 * Time: 22:56
 */

namespace He110\CommunicationToolsTests\ScreenItems;

use He110\CommunicationTools\ScreenItems\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    /** @var File */
    private $file;

    /** @var string  */
    const IMAGE_PATH = __DIR__."/../Assets/image.jpg";

    /** @var string  */
    const DOCUMENT_PATH = __DIR__."/../Assets/document.txt";

    /**
     * @covers \He110\CommunicationTools\ScreenItems\File::setType()
     * @covers \He110\CommunicationTools\ScreenItems\File::setPath()
     * @covers \He110\CommunicationTools\ScreenItems\File::setName()
     * @covers \He110\CommunicationTools\ScreenItems\File::setSize()
     * @covers \He110\CommunicationTools\ScreenItems\File::getPath()
     * @covers \He110\CommunicationTools\ScreenItems\File::getType()
     * @covers \He110\CommunicationTools\ScreenItems\File::isImage()
     */
    public function testSetPath()
    {
        $this->file->setPath(static::IMAGE_PATH);
        $this->assertEquals(static::IMAGE_PATH, $this->file->getPath());
        $this->assertEquals("image.jpg", $this->file->getName());
        $this->assertEquals(File::FILE_TYPE_IMAGE, $this->file->getType());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\File::fromArray()
     * @covers \He110\CommunicationTools\ScreenItems\File::toArray()
     */
    public function testToArray()
    {
        $conf = [
            "path" => static::IMAGE_PATH,
            "name" => __METHOD__
        ];
        $this->file->fromArray($conf);
        $this->assertEquals($conf["path"], $this->file->getPath());
        $this->assertEquals($conf["name"], $this->file->getName());

        $new = $this->file->toArray();
        $this->assertArrayHasKey("path", $new);
        $this->assertArrayHasKey("name", $new);
        $this->assertArrayHasKey("size", $new);
        $this->assertArrayHasKey("type", $new);
        $this->assertArrayHasKey("description", $new);

        $this->assertEmpty($new["description"]);

        $this->assertEquals($conf["path"], $new["path"]);
        $this->assertEquals($conf["name"], $new["name"]);
        $this->assertEquals(File::FILE_TYPE_IMAGE, $new["type"]);
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\File::getDescription()
     * @covers \He110\CommunicationTools\ScreenItems\File::setDescription()
     */
    public function testGetDescription()
    {
        $description = "TEST";
        $this->file->setDescription($description);
        $this->assertEquals($description, $this->file->getDescription());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\File::create()
     */
    public function testCreate()
    {
        $ob = File::create(["path" => static::IMAGE_PATH, "name" => __METHOD__]);
        $this->assertNotEquals($this->file, $ob);
        $this->assertEquals(File::class, get_class($ob));
        $this->assertEquals(__METHOD__, $ob->getName());
        $this->assertEquals(static::IMAGE_PATH, $ob->getPath());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\File::__toString()
     */
    public function test__toString()
    {
        $this->file->setName(__METHOD__);
        $this->assertEquals((string)$this->file, __METHOD__);
    }

    /**
     * @dataProvider getSizeProvider
     *
     * @covers \He110\CommunicationTools\ScreenItems\File::getSize()
     * @covers \He110\CommunicationTools\ScreenItems\File::setPath()
     * @covers \He110\CommunicationTools\ScreenItems\File::setSize()
     * @covers \He110\CommunicationTools\ScreenItems\File::isImage()
     */
    public function testGetSize(string $file, int $size, string $type)
    {
        $this->file->setPath($file);
        $this->assertEquals($size, $this->file->getSize());
        $this->assertEquals($type, $this->file->getType());
    }

    /**
     * @return array
     */
    public function getSizeProvider(): array
    {
        return array(
            array(static::IMAGE_PATH, 39481, File::FILE_TYPE_IMAGE),
            array(static::DOCUMENT_PATH, 12, File::FILE_TYPE_DOCUMENT)
        );
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\File::getName()
     * @covers \He110\CommunicationTools\ScreenItems\File::setName()
     */
    public function testSetName()
    {
        $this->assertNull($this->file->getName());
        $this->file->setName(__METHOD__);
        $this->assertEquals(__METHOD__, $this->file->getName());
    }

    /**
     * @covers \He110\CommunicationTools\ScreenItems\File::getType()
     * @covers \He110\CommunicationTools\ScreenItems\File::setType()
     */
    public function testSetType()
    {
        $this->assertNull($this->file->getType());
        $this->file->setType(File::FILE_TYPE_DOCUMENT);
        $this->assertEquals(File::FILE_TYPE_DOCUMENT, $this->file->getType());
    }

    public function setUp(): void
    {
        $this->file = new File();
    }

    public function tearDown(): void
    {
        $this->file = null;
        unset($this->file);
    }
}
