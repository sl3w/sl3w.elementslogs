<?php

namespace Sl3w\ElementsLogs;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\DateTime;

class ElementsLogsTable extends DataManager
{
    public static function getTableName()
    {
        return 'sl3w_elements_logs';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            new IntegerField('USER_ID', [
                'required' => true,
            ]),
            new IntegerField('ELEMENT_ID', [
                'required' => true,
            ]),
            new StringField('DO', [
                'required' => true,
            ]),
            new BooleanField('SENT', [
                'data_type' => 'boolean',
                'values' => ['N', 'Y'],
                'default_value' => 'N',
            ]),
            new DatetimeField('DATE_UPDATE', [
                'default_value' => function () {
                    return new DateTime();
                }
            ]),
        ];
    }
}