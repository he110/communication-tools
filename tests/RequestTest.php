<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 16/08/2019
 * Time: 02:27
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\MessengerUser;
use He110\CommunicationTools\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    /** @var Request */
    private $request;

    /**
     * @covers \He110\CommunicationTools\Request::setPath()
     * @covers \He110\CommunicationTools\Request::getPath()
     */
    public function testSetPath()
    {
        $this->assertNull($this->request->getPath());
        $this->request->setPath(__METHOD__);
        $this->assertEquals(__METHOD__, $this->request->getPath());
    }

    /**
     * @covers \He110\CommunicationTools\Request::setUser()
     * @covers \He110\CommunicationTools\Request::getUser()
     */
    public function testSetUser()
    {
        $this->assertNull($this->request->getUser());
        $user = new MessengerUser();
        $user->setFirstName("Ivan");
        $user->setLastName("Ivanov");
        $this->request->setUser($user);
        $this->assertEquals($user, $this->request->getUser());
        $this->assertEquals("Ivan Ivanov", (string)$this->request->getUser());
    }

    /**
     * @covers \He110\CommunicationTools\Request::setType()
     * @covers \He110\CommunicationTools\Request::getType()
     */
    public function testSetType()
    {
        $this->assertNull($this->request->getType());
        $this->request->setType(__METHOD__);
        $this->assertEquals(__METHOD__, $this->request->getType());
    }

    /**
     * @covers \He110\CommunicationTools\Request::setMessage()
     * @covers \He110\CommunicationTools\Request::getMessage()
     */
    public function testSetMessage()
    {
        $this->assertEmpty($this->request->getMessage());
        $this->request->setMessage(__METHOD__);
        $this->assertEquals(__METHOD__, $this->request->getMessage());
    }

    public function setUp(): void
    {
        $this->request = new Request();
    }

    public function tearDown(): void
    {
        $this->request = null;
        unset($this->request);
    }
}
