<?php

CModule::AddAutoloadClasses(
    'sl3w.newslogs',
    [
        'Sl3w\NewsLogs\Settings' => 'lib/classes/Settings.php',
        'Sl3w\NewsLogs\Events' => 'lib/classes/Events.php',
        'Sl3w\NewsLogs\Agents' => 'lib/classes/Agents.php',
        'Sl3w\NewsLogs\NewsLogsTable' => 'lib/classes/NewsLogsTable.php',
    ]
);