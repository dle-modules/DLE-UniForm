<?php
/*
 * DLE UniForm — унверсальные формы для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       http://pafnuty.name/
 * @link       https://twitter.com/pafnuty_name
 */

if (!defined('DATALIFEENGINE')) {
	die('Что то пошло не так');
}

/**
 * Информация из DLE, доступная в модуле
 *
 * @global boolean $is_logged Является ли посетитель авторизованным пользователем или гостем.
 * @global array $member_id Массив с информацией о авторизованном пользователе, включая всю его информацию из
 *         профиля.
 * @global object $db Класс DLE для работы с базой данных.
 * @global array $config Информация обо всех настройках скрипта.
 * @global array $user_group Информация о всех группах пользователей и их настройках.
 * @global integer $_TIME Содержит текущее время в UNIX формате с учетом настроек смещения в настройках скрипта.
 */

$user_group = get_vars('usergroup');

if (!$user_group) {
	$user_group = [];

	$db->query("SELECT * FROM ".USERPREFIX."_usergroups ORDER BY id ASC");

	while ($row = $db->get_row()) {

		$user_group[$row['id']] = [];

		foreach ($row as $key => $value) {
			$user_group[$row['id']][$key] = stripslashes($value);
		}

	}
	set_vars('usergroup', $user_group);
	$db->free();
}


$mailTpl                        = new dle_template();
$mailTpl->dir                   = TEMPLATE_DIR;
$mailTpl->result['uniformMail'] = '';
/** @var array $cfg */
$mailTpl->load_template('/uniform/'.$cfg['templateFolder'].'/email.tpl');

// Собираем все теги шаблона в массив
$arTplTags     = [];
$allMailFields = '';

// Проверяем условия для селектов
/** @var array $mailPost */
$mailPost = setConditions($mailPost, 'select', $arSelectFields, $mailTpl);
// // Проверяем условия для чекбоксов
$mailPost = setConditions($mailPost, 'checkbox', $arCheckboxFields, $mailTpl);
// // Проверяем условия для радиокнопок
$mailPost = setConditions($mailPost, 'radio', $arRadioFields, $mailTpl);
// // Проверяем условия для простых полей
$mailPost = setConditions($mailPost, 'field', [], $mailTpl);

/** @var array $member_id */
if ($member_id['user_group'] == 5) {
	$arSendMail['user_email']      = '';
	$arSendMail['user_name']       = '';
	$arSendMail['user_news_num']   = '';
	$arSendMail['user_comm_num']   = '';
	$arSendMail['user_group']      = 5;
	$arSendMail['user_lastdate']   = '';
	$arSendMail['user_reg_date']   = '';
	$arSendMail['user_banned']     = '';
	$arSendMail['user_allow_mail'] = '';
	$arSendMail['user_info']       = '';
	$arSendMail['user_signature']  = '';
	$arSendMail['user_foto']       = '';
	$arSendMail['user_fullname']   = '';
	$arSendMail['user_land']       = '';
	$arSendMail['user_favorites']  = '';
	$arSendMail['user_pm_all']     = '';
	$arSendMail['user_pm_unread']  = '';
	$arSendMail['user_time_limit'] = '';
	$arSendMail['user_logged_ip']  = '';
	$arSendMail['user_timezone']   = '';
} else {
	$userRow = $db->super_query("SELECT * FROM ".USERPREFIX."_users WHERE name = '{$member_id['name']}'");

	if ($userRow['user_id'] > 0) {

		if (count(explode('@', $userRow['foto'])) == 2) {
			$userPhoto = '//www.gravatar.com/avatar/'.md5(trim($userRow['foto'])).'?s='
				.intval($user_group[$userRow['user_group']]['max_foto']);
		} else {
			if ($userRow['foto']) {
				if (strpos($userRow['foto'], '//') === 0) {
					$avatar = 'http:'.$userRow['foto'];
				} else {
					$avatar = $userRow['foto'];
				}
				$avatar = @parse_url($avatar);
				if ($avatar['host']) {
					$userPhoto = $userRow['foto'];
				} else {
					$userPhoto = $config['http_home_url'].'uploads/fotos/'.$userRow['foto'];
				}
			} else {
				$userPhoto = $config['http_home_url'].'templates/'.$config['skin'].'/dleimages/noavatar.png';
			}
		}

		$arSendMail['user_email']      = $userRow['email'];
		$arSendMail['user_name']       = $userRow['name'];
		$arSendMail['user_news_num']   = $userRow['news_num'];
		$arSendMail['user_comm_num']   = $userRow['comm_num'];
		$arSendMail['user_group']      = $userRow['group'];
		$arSendMail['user_lastdate']   = $userRow['lastdate'];
		$arSendMail['user_reg_date']   = $userRow['reg_date'];
		$arSendMail['user_banned']     = $userRow['banned'];
		$arSendMail['user_allow_mail'] = $userRow['allow_mail'];
		$arSendMail['user_info']       = stripslashes($userRow['info']);
		$arSendMail['user_signature']  = stripslashes($userRow['signature']);
		$arSendMail['user_foto']       = $userPhoto;
		$arSendMail['user_fullname']   = stripslashes($userRow['fullname']);
		$arSendMail['user_land']       = stripslashes($userRow['land']);
		$arSendMail['user_favorites']  = $userRow['favorites'];
		$arSendMail['user_pm_all']     = $userRow['pm_all'];
		$arSendMail['user_pm_unread']  = $userRow['pm_unread'];
		$arSendMail['user_time_limit'] = $userRow['time_limit'];
		$arSendMail['user_logged_ip']  = $userRow['logged_ip'];
		$arSendMail['user_timezone']   = $userRow['timezone'];


		// Выводим данные из допполей пользователя
		if (strpos($mailTpl->copy_template, 'user_xfield_') !== false
			|| strpos($mailTpl->copy_template, 'all_mail_fields') !== false
		) {

			$xfields     = xfieldsload(true);
			$xfieldsdata = xfieldsdataload($userRow['xfields']);

			foreach ($xfields as $value) {
				$userXfName = preg_quote($value[0], "'");

				if ($value[5] != 1) {
					if (empty($xfieldsdata[$value[0]])) {
						$arSendMail['user_xfield_'.$userXfName] = '';
					} else {
						$arSendMail['user_xfield_'.$userXfName] = $xfieldsdata[$value[0]];
					}
				} else {
					$arSendMail['user_xfield_'.$userXfName] = '';
				}
			}
		}
	}

}


/** @var array $arSendMail */
foreach ($arSendMail as $tag => $value) {
	if (!$cfg['sendAsPlain']) {
		$value = str_replace(["\r", "\n"], ['', '<br>'], $value);
	}
	$arTplTags['{'.$tag.'}'] = $value;

	if (isset($value) && $value != '') {
		$mailTpl->set('['.$tag.']', '');
		$mailTpl->set('[/'.$tag.']', '');
		$mailTpl->set_block("'\\[not_{$tag}\\](.*?)\\[/not_{$tag}\\]'si", '');
	} else {
		$mailTpl->set('[not_'.$tag.']', '');
		$mailTpl->set('[/not_'.$tag.']', '');
		$mailTpl->set_block("'\\[{$tag}\\](.*?)\\[/{$tag}\\]'si", '');
	}
	$allMailFields .= '['.$tag.']{'.$tag.'}[/'.$tag.']<br>';
	$allMailFields .= '[not_'.$tag.'] '.$tag.' is empty [/not_'.$tag.']<br>';
}


/** @var array $config */
$mailTpl->set('{send_date}', date($config['timestamp_active'], time()));


// Страница, с которой был вызван модуль
$mailTpl->set('{current_page}', $_SERVER['HTTP_REFERER']);

// Определяем заголовок письма
preg_match("'\\[header\\](.*?)\\[\\/header\\]'si", $mailTpl->copy_template, $mailHeader);
// Если передано поле header — подставим его в header :)
$emailHeader = (isset($arSendMail['header']) && $arSendMail['header'] != '') ? trim($arSendMail['header'])
	: trim($mailHeader[1]);
$emailHeader = stripslashes($emailHeader);

// Обрабатываем теги шаблона
$mailTpl->set('', $arTplTags);
// Тег всех полей, пришедщших из формы
$mailTpl->set('{all_mail_fields}', $allMailFields);

// Подключаем дополнительный модуль, если это указано в конфиге
if ($cfg['parseSendMail']) {
	include(DLEPlugins::Check(ENGINE_DIR.'/modules/'.$cfg['parseSendMail'].'.php'));
}

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
	$message = str_replace(["\r", "\n"], '', $message);
}
$message = stripslashes($message);

// Подключаем класс для отправки почты.
include_once(DLEPlugins::Check(ENGINE_DIR.'/classes/mail.class.php'));
$asHtml = ($cfg['sendAsPlain']) ? false : true;
$mail   = new dle_mail($config, $asHtml);

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

// Добавляем вложения
/** @var array $arSendAttachments */
if (count($arSendAttachments)) {
	foreach ($arSendAttachments as $attach) {
		$mail->addAttachment($attach['tmp_name'], $attach['name']);
	}
}

if ($cfg['sendToSender'] && (isset($arSendMail['email']) && validEmain($arSendMail['email']))) {
	$arMails[] = $arSendMail['email'];
}

// Отправляем почту на указанные адреса
foreach ($arMails as $email) {
	// Обрабатываем ситуацию, когда разрешена отправка отправителю
	if ($cfg['sendToSender']) {
		$parsedMessage = $message;
		$parsedHeader  = $emailHeader;

		if ($email == $arSendMail['email']) {
			$parsedMessage = preg_replace("'\\[not_to_sender\\](.*?)\\[/not_to_sender\\]'is", '', $parsedMessage);
			$parsedMessage = str_replace('[to_sender]', '', $parsedMessage);
			$parsedMessage = str_replace('[/to_sender]', '', $parsedMessage);

			$parsedHeader = preg_replace("'\\[not_to_sender\\](.*?)\\[/not_to_sender\\]'is", '', $parsedHeader);
			$parsedHeader = str_replace('[to_sender]', '', $parsedHeader);
			$parsedHeader = str_replace('[/to_sender]', '', $parsedHeader);

		} else {

			$parsedMessage = preg_replace("'\\[to_sender\\](.*?)\\[/to_sender\\]'is", '', $parsedMessage);
			$parsedMessage = str_replace('[not_to_sender]', '', $parsedMessage);
			$parsedMessage = str_replace('[/not_to_sender]', '', $parsedMessage);

			$parsedHeader = preg_replace("'\\[to_sender\\](.*?)\\[/to_sender\\]'is", '', $parsedHeader);
			$parsedHeader = str_replace('[not_to_sender]', '', $parsedHeader);
			$parsedHeader = str_replace('[/not_to_sender]', '', $parsedHeader);
		}

		$mail->send($email, $parsedHeader, $parsedMessage);
	} else {
		$mail->send($email, $emailHeader, $message);
	}

}
