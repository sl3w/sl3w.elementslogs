<?php

namespace Sl3w\ElementsLogs;

class Agents
{
    public static function sendElementsLogs()
    {
        $elementsLogs = \Sl3w\ElementsLogs\ElementsLogsTable::getList([
            'order' => ['ID' => 'ASC'],
            'filter' => ['SENT' => 'N']
        ])->fetchAll();

        if (!empty($elementsLogs)) {

            $countByUser = [];
            $updateElementsByUser = [];

            $addedElements = [];
            $updatedElements = [];
            $removedElements = [];

            foreach ($elementsLogs as $elementLog) {

                switch ($elementLog['DO']) {
                    case SL3W_ELEMENTSLOGS_TABLE_ADD:
                        //добавляем элемент в список добавленных
                        $addedElements[$elementLog['ELEMENT_ID']] = $elementLog['ELEMENT_ID'];
                        //считаем действие пользователя
                        $countByUser[$elementLog['USER_ID']]++;
                        break;

                    case SL3W_ELEMENTSLOGS_TABLE_UPDATE:
                        //не учитываем элементы, которые в этом периоде были ранее добавлены,
                        //а также учитываем изменение одного элемента только один раз за период, кто бы ее ни правил
                        if (!in_array($elementLog['ELEMENT_ID'], $addedElements) && !in_array($elementLog['ELEMENT_ID'], $updatedElements)) {
                            $updatedElements[$elementLog['ELEMENT_ID']] = $elementLog['ELEMENT_ID'];
                        }

                        //отмечаем, что данный элемент правил конкретный пользователь
                        $updateElementsByUser[$elementLog['ELEMENT_ID']][$elementLog['USER_ID']]++;

                        //учитываем действие (изменение) пользователем одного элемента только один раз за период
                        if ($updateElementsByUser[$elementLog['ELEMENT_ID']][$elementLog['USER_ID']] == 1) {
                            $countByUser[$elementLog['USER_ID']]++;
                        }
                        break;

                    case SL3W_ELEMENTSLOGS_TABLE_DELETE:
                        if (in_array($elementLog['ELEMENT_ID'], $addedElements)) {
                            //удаляем элемент из списка добавленных, если он за период был добавлен, а потом удален
                            unset($addedElements[$elementLog['ELEMENT_ID']]);
                        } else {
                            //удаляем элемент из списка измененных, если он за период была изменен, а потом удален
                            unset($updatedElements[$elementLog['ELEMENT_ID']]);
                            //добавляем в список удаленных
                            $removedElements[$elementLog['ELEMENT_ID']] = $elementLog['ELEMENT_ID'];
                        }

                        //считаем действие пользователя
                        $countByUser[$elementLog['USER_ID']]++;
                        break;
                }

                //помечаем лог, как обработанный
                \Sl3w\ElementsLogs\ElementsLogsTable::update($elementLog['ID'], ['SENT' => 'Y']);
            }

            if (!empty($countByUser)) {
                $user = \Bitrix\Main\UserTable::getRow([
                    'filter' => ['ID' => array_keys($countByUser, max($countByUser))[0]],
                    'select' => ['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'LOGIN'],
                ]);
            }

            add_mail_event('ELEMENTS_LOGS', [
                'FIO' => !empty($user) ? (sprintf('%s %s %s', $user['LAST_NAME'], $user['NAME'], $user['SECOND_NAME']) ?: $user['LOGIN']) : '',
                'COUNT_ADD' => count($addedElements),
                'COUNT_UPDATE' => count($updatedElements),
                'COUNT_DELETE' => count($removedElements),
                'EMAIL_TO' => \Sl3w\ElementsLogs\Settings::get('email')
            ]);
        }

        return '\Sl3w\ElementsLogs\Agents::sendElementsLogs();';
    }
}