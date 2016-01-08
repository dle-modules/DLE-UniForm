<?php
/*
=============================================================================
UniForm - унверсальные формы для DLE
=============================================================================
Автор:   ПафНутиЙ
URL:     http://pafnuty.name/
twitter: https://twitter.com/pafnuty_name
google+: http://gplus.to/pafnuty
email:   pafnuty10@gmail.com
=============================================================================
 */

if (!defined('DATALIFEENGINE')) {
	die("Go fuck yourself!");
}


$mailTpl                        = new dle_template();
$mailTpl->dir                   = TEMPLATE_DIR;
$mailTpl->result['uniformMail'] = '';
$mailTpl->load_template('/uniform/' . $cfg['templateFolder'] . '/email.tpl');

// Собираем все теги шаблона в массив
$arTplTags = array();
$allMailFields = '';

// Проверяем условия для селектов
$mailPost = setConditions($mailPost, 'select', $arSelectFields, $mailTpl);
// // Проверяем условия для чекбоксов
$mailPost = setConditions($mailPost, 'checkbox', $arCheckboxFields, $mailTpl);
// // Проверяем условия для радиокнопок
$mailPost = setConditions($mailPost, 'radio', $arRadioFields, $mailTpl);
// // Проверяем условия для простых полей
$mailPost = setConditions($mailPost, 'field', array(), $mailTpl);

foreach ($arSendMail as $tag => $value) {
	if (!$cfg['sendAsPlain']) {
		$value = str_replace(array("\r", "\n"), array('', '<br>'), $value);
	}
	$arTplTags['{' . $tag . '}'] = $value;

	if (isset($value) && $value != '') {
		$mailTpl->set('[' . $tag . ']', '');
		$mailTpl->set('[/' . $tag . ']', '');
	} else {
		$mailTpl->set_block("'\\[{$tag}\\](.*?)\\[/{$tag}\\]'si", '');
	}
	$allMailFields .= '[' . $tag . ']{' . $tag . '}[/' . $tag . '] : ' .  $value . '<br>';
}

$mailTpl->set('{send_date}', langdate($config['timestamp_active']));
$mailTpl->copy_template = preg_replace_callback("#\{send_date=(.+?)\}#i", "formdate", $mailTpl->copy_template);

// Страница, с которой был вызван модуль
$mailTpl->set('{current_page}', $_SERVER['HTTP_REFERER']);

// Определяем заголовок письма
preg_match("'\\[header\\](.*?)\\[\\/header\\]'si", $mailTpl->copy_template, $mailHeader);
// Если передано поле header — подставим его в header :)
$emailHeader = (isset($arSendMail['header']) && $arSendMail['header'] != '') ? trim($arSendMail['header']) : trim($mailHeader[1]);
$emailHeader = stripslashes($emailHeader);

// Обрабатываем теги шаблона
$mailTpl->set('', $arTplTags);
// Тег всех полей, пришедщших из формы
$mailTpl->set('{all_mail_fields}', $allMailFields);

// Компилим шаблон
$mailTpl->compile('uniformMail');
$message = $mailTpl->result['uniformMail'];
// Удаляем из шаблона лишнее
$message = str_replace($mailHeader[0], '', $message);
$message = preg_replace("'\\[uf_field_(.*?)\\](.*?)\\[/uf_field_(.*?)\\]'is", '', $message);
$message = preg_replace("'\\[uf_select_(.*?)\\](.*?)\\[/uf_select_(.*?)\\]'is", '', $message);
$message = preg_replace("'\\[uf_checkbox_(.*?)\\](.*?)\\[/uf_checkbox_(.*?)\\]'is", '', $message);
$message = preg_replace("'\\[uf_radio_(.*?)\\](.*?)\\[/uf_radio_(.*?)\\]'is", '', $message);
$message = preg_replace("'\\{\\*(.*?)\\*\\}'si", '', $message);


$message = trim($message);
if (!$cfg['sendAsPlain']) {
	$message = preg_replace(array("'\r'", "'\n'"), '', $message);
}
$message = stripslashes($message);

// Подключаем класс для отправки почты.
include_once ENGINE_DIR . '/classes/mail.class.php';
$asHtml = ($cfg['sendAsPlain']) ? false : true;
$mail = new dle_mail($config, $asHtml);

// Определяем параметры отправки письма
if ($config['use_admin_mail'] && $config['version_id'] < 10.5) {
	$mail->from = $config['admin_mail'];
} else {
	if ($arSendMail['email'] != '' && in_array('email', $arRequired)) {
		$mail->from = $arSendMail['email'];
	} else {
		$mail->from = $config['admin_mail'];
	}
}

// Отправляем почту на указанные адреса
foreach ($arMails as $k => $email) {
	$k = $mail->send($email, $emailHeader, $message);
}