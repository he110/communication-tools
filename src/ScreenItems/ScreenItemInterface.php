<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 10/08/2019
 * Time: 16:42
 */

namespace He110\CommunicationTools\ScreenItems;


interface ScreenItemInterface
{
    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param array $data
     * @return mixed
     */
    public function fromArray(array $data);

    static public function create(array $config);
}