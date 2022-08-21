<?php

namespace Sl3w\ElementsLogs;

use Bitrix\Main\Config\Option;

class Settings
{
    public static function get($name, $default = '')
    {
        return Option::get('sl3w.elementslogs', $name, $default);
    }

    public static function set($name, $value)
    {
        Option::set('sl3w.elementslogs', $name, $value);
    }
}
