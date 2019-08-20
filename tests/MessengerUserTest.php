<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 20/08/2019
 * Time: 22:40
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\MessengerUser;
use PHPUnit\Framework\TestCase;

class MessengerUserTest extends TestCase
{
    /** @var MessengerUser */
    private $user;

    /**
     * @covers \He110\CommunicationTools\MessengerUser::getUserId()
     * @covers \He110\CommunicationTools\MessengerUser::setUserId()
     */
    public function testSetUserId()
    {
        $this->assertNull($this->user->getUserId());
        $this->user->setUserId(__METHOD__);
        $this->assertEquals(__METHOD__, $this->user->getUserId());
    }

    /**
     * @covers \He110\CommunicationTools\MessengerUser::setLanguageCode()
     * @covers \He110\CommunicationTools\MessengerUser::getLanguageCode()
     */
    public function testSetLanguageCode()
    {
        $this->assertNull($this->user->getLanguageCode());
        $this->user->setLanguageCode("en");
        $this->assertEquals("en", $this->user->getLanguageCode());
    }

    /**
     * @covers \He110\CommunicationTools\MessengerUser::getFirstName()
     * @covers \He110\CommunicationTools\MessengerUser::setFirstName()
     */
    public function testSetFirstName()
    {
        $this->assertNull($this->user->getFirstName());
        $this->user->setFirstName(__METHOD__);
        $this->assertEquals(__METHOD__, $this->user->getFirstName());
    }

    /**
     * @covers \He110\CommunicationTools\MessengerUser::setLastName()
     * @covers \He110\CommunicationTools\MessengerUser::getLastName()
     */
    public function testSetLastName()
    {
        $this->assertNull($this->user->getLastName());
        $this->user->setLastName(__METHOD__);
        $this->assertEquals(__METHOD__, $this->user->getLastName());
    }

    /**
     * @covers \He110\CommunicationTools\MessengerUser::setUsername()
     * @covers \He110\CommunicationTools\MessengerUser::getUsername()
     */
    public function testSetUsername()
    {
        $this->assertNull($this->user->getUsername());
        $this->user->setUsername(__METHOD__);
        $this->assertEquals(__METHOD__, $this->user->getUsername());
    }

    /**
     * @covers \He110\CommunicationTools\MessengerUser::getFullName()
     * @covers \He110\CommunicationTools\MessengerUser::getFirstName()
     * @covers \He110\CommunicationTools\MessengerUser::getLastName()
     * @covers \He110\CommunicationTools\MessengerUser::setFirstName()
     * @covers \He110\CommunicationTools\MessengerUser::setLastName()
     * @covers \He110\CommunicationTools\MessengerUser::__toString()
     */
    public function testGetFullName()
    {
        $this->user->setFirstName("Ivan");
        $this->user->setLastName("Ivanov");
        $this->assertEquals("Ivan Ivanov", (string)$this->user);
    }

    public function setUp(): void
    {
        $this->user = new MessengerUser();
    }

    public function tearDown(): void
    {
        $this->user = null;
        unset($this->user);
    }
}
