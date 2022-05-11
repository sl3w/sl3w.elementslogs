<?php

$MESS ['SL3W_NEWSLOGS_MODULE_NAME'] = 'Логгер новостей';
$MESS ['SL3W_NEWSLOGS_MODULE_DESCRIPTION'] = 'Модуль логирует действия с элементами инфоблока и отправляет информацию на email';

$MESS ['SL3W_NEWSLOGS_MAIL_TYPE_NAME'] = 'Логи новостей';
$MESS ['SL3W_NEWSLOGS_MAIL_TYPE_DESCRIPTION'] = '
#EMAIL_TO# E-mail получателя
#COUNT_ADD# Количество добавленных новостей
#COUNT_UPDATE# Количество измененных новостей
#COUNT_DELETE# Количество удаленных новостей
#FIO# ФИО пользователя с наибольшим числом правок';

$MESS ['SL3W_NEWSLOGS_MAIL_EVENT_SUBJECT'] = 'Логи новостей';
$MESS ['SL3W_NEWSLOGS_MAIL_EVENT_MESSAGE'] = '
Количество добавленных новостей: #COUNT_ADD#<br>
Количество измененных новостей: #COUNT_UPDATE#<br>
Количество удаленных новостей: #COUNT_DELETE#<br><br>
Больше всего правок внёc пользователь: #FIO#';