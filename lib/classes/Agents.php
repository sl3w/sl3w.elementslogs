<?php

namespace Sl3w\NewsLogs;

class Agents
{
    public static function sendNewslogs()
    {
        $newsLogs = \Sl3w\NewsLogs\NewslogsTable::getList([
            'order' => ['ID' => 'ASC'],
            'filter' => ['SENT' => 'N']
        ])->fetchAll();

        if (!empty($newsLogs)) {

            $countByUser = [];
            $updateNewsByUser = [];

            $addedNews = [];
            $updatedNews = [];
            $removedNews = [];

            foreach ($newsLogs as $newsLog) {

                switch ($newsLog['DO']) {
                    case SL3W_NEWSLOGS_TABLE_ADD:
                        //добавляем новость в список добавленных
                        $addedNews[$newsLog['NEWS_ID']] = $newsLog['NEWS_ID'];
                        //считаем действие пользователя
                        $countByUser[$newsLog['USER_ID']]++;
                        break;

                    case SL3W_NEWSLOGS_TABLE_UPDATE:
                        //не учитываем новости, которые в этом периоде были ранее добавлены,
                        //а также учитываем изменение одной новости только один раз за период, кто бы ее ни правил
                        if (!in_array($newsLog['NEWS_ID'], $addedNews) && !in_array($newsLog['NEWS_ID'], $updatedNews)) {
                            $updatedNews[$newsLog['NEWS_ID']] = $newsLog['NEWS_ID'];
                        }

                        //отмечаем, что данную новость правил конкретный юзер
                        $updateNewsByUser[$newsLog['NEWS_ID']][$newsLog['USER_ID']]++;

                        //учитываем действие (изменение) юзером одной новости только один раз за период
                        if ($updateNewsByUser[$newsLog['NEWS_ID']][$newsLog['USER_ID']] == 1) {
                            $countByUser[$newsLog['USER_ID']]++;
                        }
                        break;

                    case SL3W_NEWSLOGS_TABLE_DELETE:
                        if (in_array($newsLog['NEWS_ID'], $addedNews)) {
                            //удаляем новость из списка добавленных, если она за период была добавлена, а потом удалена
                            unset($addedNews[$newsLog['NEWS_ID']]);
                        } else {
                            //удаляем новость из списка измененных, если она за период была изменена, а потом удалена
                            unset($updatedNews[$newsLog['NEWS_ID']]);
                            //добавляем в список удаленных
                            $removedNews[$newsLog['NEWS_ID']] = $newsLog['NEWS_ID'];
                        }

                        //считаем действие пользователя
                        $countByUser[$newsLog['USER_ID']]++;
                        break;
                }

                //помечаем лог, как обработанный
                \Sl3w\NewsLogs\NewslogsTable::update($newsLog['ID'], ['SENT' => 'Y']);
            }

            if (!empty($countByUser)) {
                $user = \Bitrix\Main\UserTable::getRow([
                    'filter' => ['ID' => array_keys($countByUser, max($countByUser))[0]],
                    'select' => ['ID', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'LOGIN'],
                ]);
            }

            add_mail_event('NEWS_LOGS', [
                'FIO' => !empty($user) ? (sprintf('%s %s %s', $user['LAST_NAME'], $user['NAME'], $user['SECOND_NAME']) ?: $user['LOGIN']) : '',
                'COUNT_ADD' => count($addedNews),
                'COUNT_UPDATE' => count($updatedNews),
                'COUNT_DELETE' => count($removedNews),
                'EMAIL_TO' => \Sl3w\NewsLogs\Settings::get('email')
            ]);
        }

        return '\Sl3w\NewsLogs\Agents::sendNewslogs();';
    }
}