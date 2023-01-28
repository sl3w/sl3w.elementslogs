<?php

namespace Sl3w\ElementsLogs;

class Events
{
    public static function OnAfterIBlockElementAdd($arFields)
    {
        self::OnAfterIBlockElementAddUpdateDelete($arFields, SL3W_ELEMENTSLOGS_TABLE_ADD);
    }

    public static function OnAfterIBlockElementUpdate($arFields)
    {
        self::OnAfterIBlockElementAddUpdateDelete($arFields, SL3W_ELEMENTSLOGS_TABLE_UPDATE);
    }

    public static function OnAfterIBlockElementDelete($arFields)
    {
        self::OnAfterIBlockElementAddUpdateDelete($arFields, SL3W_ELEMENTSLOGS_TABLE_DELETE);
    }

    public static function OnAfterIBlockElementAddUpdateDelete($arFields, $do)
    {
        if ($arFields['IBLOCK_ID'] != \Sl3w\ElementsLogs\Settings::get('iblock_id', 1)) {
            return;
        }

        global $USER;

        \Sl3w\ElementsLogs\ElementsLogsTable::add(['USER_ID' => $USER->GetID(), 'DO' => $do, 'ELEMENT_ID' => $arFields['ID']]);
    }
}