<?php

class Unit
{
    // 去除转义字符
    static function stripslashesArray(&$array) {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                $array[$k] = self::stripslashesArray($v);
            }
        } else if (is_string($array)) {
            $array = stripslashes($array);
        }
        return $array;
    }
}