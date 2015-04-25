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
$mailTpl->load_template('/uniform/mail/' . $cfg['mailTemplate'] . '.tpl');

// Собираем все теги шаблона в массив
$arTplTags = array();

foreach ($arSendMail as $tag => $value) {
	$arTplTags['{' . $tag . '}'] = $value;
}

// Определяем заголовок письма
preg_match("'\\[header\\](.*?)\\[\\/header\\]'si", $mailTpl->copy_template, $mailHeader);
$emailHeader = trim($mailHeader[1]);

// Обрабатываем теги шаблона
$mailTpl->set('', $arTplTags);
// Компилим шаблон
$mailTpl->compile('uniformMail');
$message = $mailTpl->result['uniformMail'];
// Удаляем из шаблона лишнее
$message = str_replace($mailHeader[0], '', $message);
$message = trim($message);
$message = preg_replace(array("'\r'", "'\n'"), array("", "<br />"), $message);

// Подключаем класс для отправки почты.
include_once ENGINE_DIR . '/classes/mail.class.php';
$mail = new dle_mail($config, true);

// Определяем параметры отправки письма
if ($config['use_admin_mail']) {
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