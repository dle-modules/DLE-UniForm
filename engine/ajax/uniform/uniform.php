<?php
/*
 * DLE UniForm — унверсальные формы для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       http://pafnuty.name/
 * @link       https://twitter.com/pafnuty_name
 */

@error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
@ini_set('display_errors', true);
@ini_set('html_errors', false);
@ini_set('error_reporting', E_ALL ^ E_WARNING ^ E_NOTICE);

define('DATALIFEENGINE', true);
define('ROOT_DIR', substr(dirname(__FILE__), 0, -19));
define('ENGINE_DIR', ROOT_DIR . '/engine');

if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

	include ENGINE_DIR . '/data/config.php';

	date_default_timezone_set($config['date_adjust']);

	if ($config['http_home_url'] == "") {

		$config['http_home_url'] = explode("engine/ajax/uniform/uniform.php", $_SERVER['PHP_SELF']);
		$config['http_home_url'] = reset($config['http_home_url']);
		$config['http_home_url'] = "http://" . $_SERVER['HTTP_HOST'] . $config['http_home_url'];

	}

	require_once ENGINE_DIR . '/classes/mysql.php';
	require_once ENGINE_DIR . '/data/dbconfig.php';
	require_once ENGINE_DIR . '/modules/functions.php';

	if (function_exists('dle_session')) {
		dle_session();
	} else {
		@session_start();
	}

	$user_group = get_vars("usergroup");
	if (!$user_group) {
		$user_group = array();
		$db->query("SELECT * FROM " . USERPREFIX . "_usergroups ORDER BY id ASC");
		while ($row = $db->get_row()) {
			$user_group[$row['id']] = array();
			foreach ($row as $key => $value) {
				$user_group[$row['id']][$key] = stripslashes($value);
			}

		}
		set_vars("usergroup", $user_group);
		$db->free();
	}

	//####################################################################################################################
	//                    Определение забаненных пользователей и IP
	//####################################################################################################################
	$banned_info = get_vars("banned");

	if (!is_array($banned_info)) {
		$banned_info = array();

		$db->query("SELECT * FROM " . USERPREFIX . "_banned");
		while ($row = $db->get_row()) {

			if ($row['users_id']) {

				$banned_info['users_id'][$row['users_id']] = array(
					'users_id' => $row['users_id'],
					'descr'    => stripslashes($row['descr']),
					'date'     => $row['date']);

			} else {

				if (count(explode(".", $row['ip'])) == 4) {
					$banned_info['ip'][$row['ip']] = array(
						'ip'    => $row['ip'],
						'descr' => stripslashes($row['descr']),
						'date'  => $row['date'],
					);
				} elseif (strpos($row['ip'], "@") !== false) {
					$banned_info['email'][$row['ip']] = array(
						'email' => $row['ip'],
						'descr' => stripslashes($row['descr']),
						'date'  => $row['date']);
				} else {
					$banned_info['name'][$row['ip']] = array(
						'name'  => $row['ip'],
						'descr' => stripslashes($row['descr']),
						'date'  => $row['date']);
				}

			}

		}
		set_vars("banned", $banned_info);
		$db->free();
	}

	$config['charset'] = ($lang['charset'] != '') ? $lang['charset'] : $config['charset'];
	$is_logged         = false;
	$member_id         = array();

	if ($config['allow_registration']) {
		require_once ENGINE_DIR . '/modules/sitelogin.php';
	}

	if (!$is_logged) {
		$member_id['user_group'] = 5;
	}

	if (check_ip($banned_info['ip'])) {
		die("error_ip");
	}

	if ($is_logged and $member_id['banned'] == "yes") {
		die("error_ban");
	}

	$template_dir = ROOT_DIR . 'templates/' . $config['skin'];

	// Пытаемся получить даные из шаблона с настройками
	if (isset($_REQUEST['formConfig']) && file_exists($template_dir . '/uniform/' . $_REQUEST['formConfig'] . '/config.tpl')) {
		// Если файл существует - берём из него контент с настройками
		$preset = file_get_contents($template_dir . '/uniform/' . $_REQUEST['formConfig'] . '/config.tpl');
		$arConf = array();
	} else {
		die('error_config');
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
	include ENGINE_DIR . '/modules/uniform/uniform.php';

} else {
	die('error');
}
