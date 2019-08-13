<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 13/08/2019
 * Time: 18:12
 */

namespace He110\CommunicationTools;


class Helpers
{
    /**
     * @param array      $array
     * @param int|string $position
     * @param mixed      $insert
     */
    static function array_insert(&$array, $position, $insert)
    {
        if (is_int($position)) {
            array_splice($array, $position, 0, $insert);
        } else {
            $pos   = array_search($position, array_keys($array));
            $array = array_merge(
                array_slice($array, 0, $pos),
                $insert,
                array_slice($array, $pos)
            );
        }
    }
}