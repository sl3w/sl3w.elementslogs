<?php

CModule::AddAutoloadClasses(
    'sl3w.elementslogs',
    [
        'Sl3w\ElementsLogs\Settings' => 'lib/classes/Settings.php',
        'Sl3w\ElementsLogs\Events' => 'lib/classes/Events.php',
        'Sl3w\ElementsLogs\Agents' => 'lib/classes/Agents.php',
        'Sl3w\ElementsLogs\ElementsLogsTable' => 'lib/classes/ElementsLogsTable.php',
    ]
);