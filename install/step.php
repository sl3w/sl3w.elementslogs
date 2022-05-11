<?php if (!check_bitrix_sessid()) return;

/** @global CMain $APPLICATION */
?>

<?php CAdminMessage::ShowNote('Модуль установлен'); ?>
<form action="<?= $APPLICATION->GetCurPage(); ?>">
    <input type="hidden" name="lang" value="<?= LANG ?>">
    <input type="submit" name="" value="<?= 'Вернуться в список' ?>">
<form>