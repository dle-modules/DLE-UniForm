<?php
/*
 * DLE UniForm — унверсальные формы для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       http://pafnuty.name/
 * @link       https://twitter.com/pafnuty_name
 */

if (!defined('DATALIFEENGINE')) {
	header('HTTP/1.1 403 Forbidden');
	header('Location: ../../');
	die('Hacking attempt!');
}


$banned_info = get_vars('banned');

if (!is_array($banned_info)) {
	$banned_info = [];

	$db->query("SELECT * FROM ".USERPREFIX."_banned");
	while ($row = $db->get_row()) {

		if ($row['users_id']) {

			$banned_info['users_id'][$row['users_id']] = [
				'users_id' => $row['users_id'],
				'descr'    => stripslashes($row['descr']),
				'date'     => $row['date'],
			];

		} else {

			if (count(explode(".", $row['ip'])) == 4) {
				$banned_info['ip'][$row['ip']] = [
					'ip'    => $row['ip'],
					'descr' => stripslashes($row['descr']),
					'date'  => $row['date'],
				];
			} elseif (strpos($row['ip'], "@") !== false) {
				$banned_info['email'][$row['ip']] = [
					'email' => $row['ip'],
					'descr' => stripslashes($row['descr']),
					'date'  => $row['date'],
				];
			} else {
				$banned_info['name'][$row['ip']] = [
					'name'  => $row['ip'],
					'descr' => stripslashes($row['descr']),
					'date'  => $row['date'],
				];
			}

		}

	}
	set_vars('banned', $banned_info);
	$db->free();
}

if (check_ip($banned_info['ip'])) {
	die('error_ban');
}

if ($is_logged AND $member_id['banned'] == 'yes') {
	die('error_ban');
}

if (!$config['allow_registration']) {
	$dle_login_hash = sha1(SECURE_AUTH_KEY.$_IP);
}

$template_dir = ROOT_DIR.'/templates/'.$config['skin'];

// Пытаемся получить даные из шаблона с настройками
if (isset($_REQUEST['formConfig']) && file_exists($template_dir.'/uniform/'.$_REQUEST['formConfig'].'/config.tpl')) {
	// Если файл существует - берём из него контент с настройками
	$preset = file_get_contents($template_dir.'/uniform/'.$_REQUEST['formConfig'].'/config.tpl');
	$arConf = [];
} else {
	echo '<pre class="dle-pre">';
	print_r($template_dir);
	echo '</pre>';
	die('config file not found');
}
// Разбиваем полученные из файла нестройки по строкам
$preset = explode("\n", $preset);

// Пробегаем по массиву и формируем список настроек
foreach ($preset as $v) {
	$_v = explode('=', $v);
	if (isset($_v[1])) {
		$arConf[trim($_v[0])] = trim($_v[1]);
	}
}

// Подключаем основной модуль
include(DLEPlugins::Check(ENGINE_DIR.'/modules/uniform/uniform.php'));

