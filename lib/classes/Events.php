<?php

namespace Sl3w\NewsLogs;

class Events
{
    public function OnAfterNewsElementAdd($arFields)
    {
        self::OnAfterNewsElementAddUpdateDelete($arFields, SL3W_NEWSLOGS_TABLE_ADD);
    }

    public function OnAfterNewsElementUpdate($arFields)
    {
        self::OnAfterNewsElementAddUpdateDelete($arFields, SL3W_NEWSLOGS_TABLE_UPDATE);
    }

    public function OnAfterNewsElementDelete($arFields)
    {
        self::OnAfterNewsElementAddUpdateDelete($arFields, SL3W_NEWSLOGS_TABLE_DELETE);
    }

    public static function OnAfterNewsElementAddUpdateDelete($arFields, $do)
    {
        if ($arFields['IBLOCK_ID'] != \Sl3w\NewsLogs\Settings::get('iblock_id', 1)) {
            return;
        }

        global $USER;

        \Sl3w\NewsLogs\NewslogsTable::add(['USER_ID' => $USER->GetID(), 'DO' => $do, 'NEWS_ID' => $arFields['ID']]);
    }
}