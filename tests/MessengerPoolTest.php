<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 01:02
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\MessengerInterface;
use He110\CommunicationTools\MessengerPool;
use He110\CommunicationTools\MessengerScreen;
use PHPUnit\Framework\TestCase;

class MessengerPoolTest extends TestCase
{
    /** @var MessengerPool */
    private $pool;

    /**
     * @covers \He110\CommunicationTools\MessengerPool::setTargetUser()
     * @covers \He110\CommunicationTools\MessengerPool::getTargetUser()
     */
    public function testGetTargetUser()
    {
        $pool = new MessengerPool();
        $this->assertEmpty($pool->getTargetUser());
        $pool->add($this->createMessengerMock(1));
        $this->assertEquals(1, $pool->getTargetUser());
        $pool->add($this->createMessengerMock(2));
        $this->assertEquals(1, $pool->getTargetUser());
        $pool->setTargetUser(2);
        $this->assertEquals(2, $pool->getTargetUser());
    }

    /**
     * @covers \He110\CommunicationTools\MessengerPool::add()
     * @covers \He110\CommunicationTools\MessengerPool::getList()
     */
    public function testAdd()
    {
        $list = $this->pool->getList();
        $this->pool->add($this->createMessengerMock(2));
        $this->assertEquals(1, count($list));
        $this->assertEquals(2, count($this->pool->getList()));
    }

    /**
     * @covers \He110\CommunicationTools\MessengerPool::removeByKey()
     */
    public function testRemoveByKey()
    {
        $list = $this->pool->getList();
        list($messenger) = $list;
        $this->assertEquals(1, count($list));
        $messengerKey = $this->pool->indexOf($messenger);
        $this->pool->removeByKey($messengerKey);
        $this->assertEquals(0, count($this->pool->getList()));
    }

    /**
     * @covers \He110\CommunicationTools\MessengerPool::remove()
     */
    public function testRemove()
    {
        $list = $this->pool->getList();
        list($messenger) = $list;
        $this->assertEquals(1, count($list));
        $this->pool->remove($messenger);
        $this->assertEquals(0, count($this->pool->getList()));
    }

    /**
     * @covers \He110\CommunicationTools\MessengerPool::sendScreen()
     */
    public function testSendScreen()
    {
        $screen = $this->getMockBuilder(MessengerScreen::class)->getMock();
        $this->trueAndFalseTest("sendScreen", $screen);
    }

    /**
     * @dataProvider sendTypesProvider
     *
     * @covers \He110\CommunicationTools\MessengerPool::sendMessage()
     * @covers \He110\CommunicationTools\MessengerPool::sendImage()
     * @covers \He110\CommunicationTools\MessengerPool::sendDocument()
     * @covers \He110\CommunicationTools\MessengerPool::sendVoice()
     */
    public function testSendTypes(string $type)
    {
        $this->trueAndFalseTest($type, "test_argument");
    }

    public function sendTypesProvider()
    {
        return array(
            array("sendMessage"),
            array("sendImage"),
            array("sendDocument"),
            array("sendVoice"),
        );
    }

    /**
     * @covers \He110\CommunicationTools\MessengerPool::indexOf()
     */
    public function testIndexOf()
    {
        list($messenger) = $this->pool->getList();
        $this->assertEquals(0, $this->pool->indexOf($messenger));
        $newMessenger = $this->createMessengerMock(10, false);
        $this->assertEquals(-1, $this->pool->indexOf($newMessenger));
    }

    /**
     * @param string $userId
     * @param bool $defaultResult
     * @return \PHPUnit\Framework\MockObject\MockObject|MessengerInterface
     */
    private function createMessengerMock(string $userId, bool $defaultResult = true)
    {
        $userIdMemory = $userId;
        $messenger = $this->getMockBuilder(MessengerInterface::class)->getMock();
        $messenger->method("setTargetUser")->willReturnCallback(function($argument) use (&$userIdMemory){
            $userIdMemory = $argument;
        });
        $messenger->method("getTargetUser")->will($this->returnCallback(function() use (&$userIdMemory){
            return $userIdMemory;
        }));
        $messenger->method("sendMessage")->willReturn($defaultResult);
        $messenger->method("sendImage")->willReturn($defaultResult);
        $messenger->method("sendDocument")->willReturn($defaultResult);
        $messenger->method("sendImage")->willReturn($defaultResult);
        $messenger->method("sendScreen")->willReturn($defaultResult);
        $messenger->method("sendVoice")->willReturn($defaultResult);

        return $messenger;
    }

    private function trueAndFalseTest(string $methodName, $argument)
    {
        $this->assertTrue($this->pool->{$methodName}($argument));
        $this->pool->add($this->createMessengerMock(2, false));
        $this->assertFalse($this->pool->{$methodName}($argument));
    }

    public function setUp(): void
    {
        $this->pool = new MessengerPool();
        $this->pool->add($this->createMessengerMock(1));
    }

    public function tearDown(): void
    {
        $this->pool = null;
        unset($this->pool);
    }
}
