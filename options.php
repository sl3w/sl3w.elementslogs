<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$module_id = htmlspecialcharsbx($request['mid'] != '' ? $request['mid'] : $request['id']);

Loader::includeModule($module_id);

if (!CModule::IncludeModule('iblock')) {
    ShowMessage(Loc::getMessage('SL3W_ELEMENTSLOGS_OPTIONS_IBLOCK_ERROR'));
    return false;
}

$dbIBlocks = CIBlock::GetList(['SORT' => 'ASC'], ['ACTIVE' => 'Y']);

while ($arIBlock = $dbIBlocks->GetNext()) {
    $selectIBlocks[$arIBlock['ID']] = '[' . $arIBlock['ID'] . '] ' . $arIBlock['NAME'];
}

$aTabs = array(
    [
        'DIV' => 'edit',
        'TAB' => Loc::getMessage('SL3W_ELEMENTSLOGS_OPTIONS_TAB_NAME'),
        'TITLE' => Loc::getMessage('SL3W_ELEMENTSLOGS_OPTIONS_TAB_NAME'),
        'OPTIONS' => [
            [
                'iblock_id',
                Loc::getMessage('SL3W_ELEMENTSLOGS_OPTIONS_IBLOCK_ID'),
                1,
                ['selectbox', $selectIBlocks]
            ],
            [
                'email',
                Loc::getMessage('SL3W_ELEMENTSLOGS_OPTIONS_EMAIL'),
                '',
                ['text', 30]
            ],
        ]
    ]
);

$tabControl = new CAdminTabControl(
    'tabControl',
    $aTabs
);

$tabControl->Begin();
?>

    <form action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= $module_id ?>&lang=<?= LANG ?>"
          method="post">

        <?php
        foreach ($aTabs as $aTab) {

            if ($aTab['OPTIONS']) {

                $tabControl->BeginNextTab();

                __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
            }
        }

        $tabControl->Buttons();
        ?>

        <input type="submit" name="apply" value="<?= Loc::GetMessage('SL3W_ELEMENTSLOGS_BUTTON_APPLY') ?>"
               class="adm-btn-save"/>
        <input type="submit" name="default" value="<?= Loc::GetMessage('SL3W_ELEMENTSLOGS_BUTTON_DEFAULT') ?>"/>

        <?= bitrix_sessid_post() ?>

    </form>

<?php
$tabControl->End();

if ($request->isPost() && check_bitrix_sessid()) {

    foreach ($aTabs as $aTab) {

        foreach ($aTab['OPTIONS'] as $arOption) {

            if (!is_array($arOption) || $arOption['note']) {
                continue;
            }

            if ($request['apply']) {

                $optionValue = $request->getPost($arOption[0]);

                if ($arOption[3][0] == 'checkbox' && $optionValue == '') {
                    $optionValue = 'N';
                }

                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(',', $optionValue) : $optionValue);

            } elseif ($request['default']) {

                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    LocalRedirect($APPLICATION->GetCurPage() . '?mid=' . $module_id . '&lang=' . LANG);
}