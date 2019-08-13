<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 13/08/2019
 * Time: 18:13
 */

namespace He110\CommunicationToolsTests;

use He110\CommunicationTools\Helpers;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{

    /**
     * @covers \He110\CommunicationTools\Helpers::array_insert()
     */
    public function testArray_insert()
    {
        $insert = "between";

        $array = [
            "first",
            "second"
        ];

        Helpers::array_insert($array, 1, [$insert]);

        $this->assertCount(3, $array);
        $this->assertEquals($array[1], $insert);

        $array = [
            "first" => 1,
            "second" => 2
        ];

        Helpers::array_insert($array, "second", [$insert]);

        $this->assertCount(3, $array);
        $this->assertEquals([
            "first" => 1,
            0 => $insert,
            "second" => 2
        ], $array);
    }
}
