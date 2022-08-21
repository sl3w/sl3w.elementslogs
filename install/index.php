<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\ModuleManager;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class sl3w_elementslogs extends CModule
{
    var $MODULE_ID = 'sl3w.elementslogs';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_NAME;
    var $PARTNER_URI;
    var $MODULE_DIR;

    public function __construct()
    {
        if (file_exists(__DIR__ . '/version.php')) {

            $arModuleVersion = [];

            include_once(__DIR__ . '/version.php');

            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

            $this->MODULE_NAME = Loc::getMessage('SL3W_ELEMENTSLOGS_MODULE_NAME');
            $this->MODULE_DESCRIPTION = Loc::getMessage('SL3W_ELEMENTSLOGS_MODULE_DESCRIPTION');

            $this->PARTNER_NAME = Loc::getMessage('SL3W_ELEMENTSLOGS_PARTNER_NAME');
            $this->PARTNER_URI = Loc::getMessage('SL3W_ELEMENTSLOGS_PARTNER_URI');

            $this->MODULE_DIR = dirname(__FILE__) . '/../';
        }
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->InstallDB();
        $this->InstallEvents();
        $this->SetOptions();
        $this->InstallMail();
        $this->InstallAgents();
    }

    public function DoUninstall()
    {
        $this->UnInstallAgents();
        $this->UnInstallMail();
        $this->ClearOptions();
        $this->UnInstallEvents();
        $this->UnInstallDB();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallDB()
    {
        include_once(__DIR__ . '/../lib/classes/ElementsLogsTable.php');

        if (!Application::getConnection()->isTableExists(Base::getInstance('\Sl3w\ElementsLogs\ElementsLogsTable')->getDBTableName())) {
            Base::getInstance('\Sl3w\ElementsLogs\ElementsLogsTable')->createDBTable();
        }

        return true;
    }

    public function UnInstallDB()
    {
        include_once(__DIR__ . '/../lib/classes/ElementsLogsTable.php');

        if (Application::getConnection()->isTableExists(Base::getInstance('\Sl3w\ElementsLogs\ElementsLogsTable')->getDBTableName())) {
            Application::getConnection()->dropTable(Base::getInstance('\Sl3w\ElementsLogs\ElementsLogsTable')->getDBTableName());
        }

        return true;
    }

    public function InstallMail()
    {
        $arrCeventType = [
            'LID' => SITE_ID,
            'EVENT_NAME' => 'ELEMENTS_LOGS',
            'NAME' => Loc::getMessage('SL3W_ELEMENTSLOGS_MAIL_TYPE_NAME'),
            'DESCRIPTION' => Loc::getMessage('SL3W_ELEMENTSLOGS_MAIL_TYPE_DESCRIPTION'),
        ];

        $et = new CEventType;
        $res = $et->Add($arrCeventType);

        if ($res) {
            $dbSites = CSite::GetList(($b = ''), ($o = ''), array('ACTIVE' => 'Y'));

            $arSites = [];
            while ($site = $dbSites->Fetch()) {
                $arSites[] = $site['LID'];
            }

            $arr = array(
                'ACTIVE' => 'Y',
                'EVENT_NAME' => 'ELEMENTS_LOGS',
                'LID' => $arSites,
                'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
                'EMAIL_TO' => '#EMAIL_TO#',
                'SUBJECT' => Loc::getMessage('SL3W_ELEMENTSLOGS_MAIL_EVENT_SUBJECT'),
                'BODY_TYPE' => 'html',
                'MESSAGE' => Loc::getMessage('SL3W_ELEMENTSLOGS_MAIL_EVENT_MESSAGE')
            );

            $emess = new CEventMessage;
            $emess->Add($arr);
        }
    }

    public function UnInstallMail()
    {
        global $DB;

        $et = new CEventType;
        $et->Delete('ELEMENTS_LOGS');

        $DB->StartTransaction();

        $emessage = new CEventMessage;
        $rsMess = CEventMessage::GetList($b = 'site_id', $o = 'desc', ['TYPE_ID' => 'ELEMENTS_LOGS']);

        while ($events = $rsMess->GetNext()) {
            $emessage->Delete(intval($events["ID"]));
            $DB->Commit();
        }
    }

    public function InstallAgents()
    {
        \CAgent::AddAgent(
            '\Sl3w\ElementsLogs\Agents::sendElementsLogs();',
            $this->MODULE_ID,
            'N', //�������������
            7 * 24 * 3600, //��������
            '',
            'Y', //����������
            date('d.m.Y H:i:s', strtotime('+1 day')) //��������� ������
        );

        return true;
    }

    public function UnInstallAgents()
    {
        \CAgent::RemoveModuleAgents($this->MODULE_ID);

        return true;
    }

    public function InstallEvents()
    {

        EventManager::getInstance()->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementAdd',
            $this->MODULE_ID,
            'Sl3w\ElementsLogs\Events',
            'OnAfterIBlockElementAdd'
        );

        EventManager::getInstance()->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Sl3w\ElementsLogs\Events',
            'OnAfterIBlockElementUpdate'
        );

        EventManager::getInstance()->registerEventHandler(
            'iblock',
            'OnAfterIBlockElementDelete',
            $this->MODULE_ID,
            'Sl3w\ElementsLogs\Events',
            'OnAfterIBlockElementDelete'
        );

        return true;
    }

    public function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementAdd',
            $this->MODULE_ID,
            'Sl3w\ElementsLogs\Events',
            'OnAfterIBlockElementAdd'
        );

        EventManager::getInstance()->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementUpdate',
            $this->MODULE_ID,
            'Sl3w\ElementsLogs\Events',
            'OnAfterIBlockElementUpdate'
        );

        EventManager::getInstance()->unRegisterEventHandler(
            'iblock',
            'OnAfterIBlockElementDelete',
            $this->MODULE_ID,
            'Sl3w\ElementsLogs\Events',
            'OnAfterIBlockElementDelete'
        );

        return true;
    }

    private function SetOptions()
    {
        Option::set($this->MODULE_ID, 'iblock_id', 1);

        return true;
    }

    private function ClearOptions()
    {
        Option::delete($this->MODULE_ID);

        return true;
    }
}
