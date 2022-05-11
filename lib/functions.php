<?php

function add_mail_event($eventName, $fields, $siteId = SITE_ID)
{
    return \Bitrix\Main\Mail\Event::send([
        'EVENT_NAME' => $eventName,
        'LID' => $siteId,
        'C_FIELDS' => $fields,
    ]);
}