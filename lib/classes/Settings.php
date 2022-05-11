<?php

namespace Sl3w\NewsLogs;

use Bitrix\Main\Config\Option;

class Settings
{
    public static function get($name, $default = '')
    {
        return Option::get('sl3w.newslogs', $name, $default);
    }

    public static function set($name, $value)
    {
        Option::set('sl3w.newslogs', $name, $value);
    }
}
