<?php

$MESS['SL3W_ELEMENTSLOGS_MODULE_NAME'] = 'Логгер действий с элементами инфоблока с уведомлением на Email';
$MESS['SL3W_ELEMENTSLOGS_MODULE_DESCRIPTION'] = 'Модуль логирует действия с элементами инфоблока и отправляет информацию на Email';
$MESS ['SL3W_ELEMENTSLOGS_PARTNER_NAME'] = 'SL3W';
$MESS ['SL3W_ELEMENTSLOGS_PARTNER_URI'] = 'https://github.com/sl3w/sl3w.elementslogs';

$MESS['SL3W_ELEMENTSLOGS_MAIL_TYPE_NAME'] = 'Логи действий с элементами инфоблока';
$MESS['SL3W_ELEMENTSLOGS_MAIL_TYPE_DESCRIPTION'] = '
#EMAIL_TO# E-mail получателя
#COUNT_ADD# Количество добавленных элементов
#COUNT_UPDATE# Количество измененных элементов
#COUNT_DELETE# Количество удаленных элементов
#FIO# ФИО пользователя с наибольшим числом правок';

$MESS['SL3W_ELEMENTSLOGS_MAIL_EVENT_SUBJECT'] = 'Логи действий с элементами инфоблока';
$MESS['SL3W_ELEMENTSLOGS_MAIL_EVENT_MESSAGE'] = '
Количество добавленных элементов: #COUNT_ADD#<br>
Количество измененных элементов: #COUNT_UPDATE#<br>
Количество удаленных элементов: #COUNT_DELETE#<br><br>
Больше всего правок внёс пользователь: #FIO#';