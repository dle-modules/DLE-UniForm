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

// Подключаем конфиг модуля
include ENGINE_DIR . '/modules/uniform/cfg.php';

// Имя кеша
$cacheName = md5(implode('_', $cfg));
// ID сессии
$sessionId = session_id();

$uniform = false;
// Если данные передаются постом — "запомним" это.
$isPost = ($_SERVER['REQUEST_METHOD'] === 'POST') ? true : false;

if (!$cfg['nocache'] && !$isPost) {
	// Проверим кеш, если это требуется
	$uniform = dle_cache($cfg['role'], $cacheName . $config['skin'], true);
}
if (!$uniform) {
	if (!defined('TEMPLATE_DIR')) {
		require_once ENGINE_DIR . '/classes/templates.class.php';
		$tpl      = new dle_template();
		$tpl->dir = $template_dir;
		define('TEMPLATE_DIR', $tpl->dir);
	}

	if (file_exists(TEMPLATE_DIR . '/' . $cfg['template'] . '.tpl')) {
		// Если файл шаблона существует — работаем.
		$tpl->result['uniform'] = '';
		$tpl->load_template($cfg['template'] . '.tpl');
		
		// Пользовательские скрытые поля
		$arHidden     = getArray($cfg['hidden']);

		// Дебаг
		$debug    = ($cfg['debug']) ? true : false;
		$debugTag = '';
		if ($debug) {
			$tpl->set('[debug]', '');
			$tpl->set('[/debug]', '');
			$debugTag .= '<pre class="uf-pre"><h4>config:</h4>';
			$debugTag .= print_r($cfg, true);
			$debugTag .= '</pre>';
		} else {
			$tpl->set_block("'\\[debug\\](.*?)\\[/debug\\]'si", '');
		}

		// Массив для отправки в sendmail.php
		$arSendMail = array();

		if ($isPost) {
			// Переменная-индикатор ошибок.
			$error = false;
			if ($debug) {
				$debugTag .= '<pre class="uf-pre"><h4>POST:</h4>';
				$debugTag .= print_r($_POST, true);
				$debugTag .= '</pre>';
			}

			// Если данные передаются постом — надо бы их обработать
			require_once ENGINE_DIR . '/classes/parse.class.php';
			$parse            = new ParseFilter();
			// $parse->safe_mode = true;

			if (!checkToken($_POST['csrfToken'], $cacheName . $config['skin'] . $sessionId)) {
				// Проверяем токен
				$error = true;
				$tpl->set('[uf_token_error]', '');
				$tpl->set('[/uf_token_error]', '');
			} else {
				$tpl->set_block("'\\[uf_token_error\\](.*?)\\[/uf_token_error\\]'si", '');
			}

			// Получаем массив обязательных полей
			$arRequired = getArray($cfg['required']);


			// Проверяем обязательные поля
			foreach ($_POST as $k => $val) {
				// Поля csrfToken и formConfig нас не интересуют.
				if (in_array($k, array('csrfToken', 'formConfig'))) {
					continue;
				}
				// Остальные поля надо бы обработать
				$val = convert_unicode($val, $config['charset']);
				$val = $parse->process(trim($val));

				$arSendMail[$k] = $val;

				if (in_array($k, $arRequired) && ($val == '' || !isset($val))) {
					// Если поле обязательное, но значение не установлено — значит произошла ошибка заполнения формы
					$error = true;
					// Показываем то, что заключено в теги ошибок.
					$tpl->copy_template = str_replace("[uf_error_{$k}]", '', $tpl->copy_template);
					$tpl->copy_template = str_replace("[/uf_error_{$k}]", '', $tpl->copy_template);
				} else {
					// Удалем теги, которые не должны показываться
					$tpl->copy_template = preg_replace("'\\[uf_error_{$k}\\](.*?)\\[/uf_error_{$k}\\]'is", '', $tpl->copy_template);
				}
				if ($k == 'email') {
					if (in_array($k, $arRequired) && !validEmain($val)) {
						// Проверем email, если это требуется (в настройках задано обязательное поле с именем email) 
						$error = true;
						$tpl->set('[uf_email_error]', '');
						$tpl->set('[/uf_email_error]', '');
					} else {
						$tpl->set_block("'\\[uf_email_error\\](.*?)\\[/uf_email_error\\]'si", '');
					}
				}



				// Заполняем поля нужными данными
				$tpl->copy_template = str_replace("{uf_field_{$k}}", $val, $tpl->copy_template);
			}

			if ($error == false) {
				// Если нет ошибок — значит форма отправлена удачно, нужно об этом сообщить
				$tpl->copy_template = preg_replace("'\\{uf_field_(.*?)\\}'si", '', $tpl->copy_template);
				$tpl->copy_template = preg_replace("'\\[uf_error_(.*?)\\](.*?)\\[/uf_error_(.*?)\\]'is", '', $tpl->copy_template);

				$tpl->set_block("'\\[form\\](.*?)\\[/form\\]'si", '');
				$tpl->set_block("'\\[error\\](.*?)\\[/error\\]'si", '');
				$tpl->set('[success]', '');
				$tpl->set('[/success]', '');

				$arMails = getArray($cfg['emails']);
				if ($cfg['sendmail'] && count($arMails) > 0) {
					include ENGINE_DIR . '/modules/uniform/sendmail.php';

					if ($debug) {
						$debugTag .= '<pre class="uf-pre"><h4>arSendMail:</h4>';
						$debugTag .= print_r($arSendMail, true);
						$debugTag .= '</pre>';
					}
				}
				unset($arSendMail);

			} else {
				// Нсли есть ошибки — выводим форму и ошибки
				$tpl->set_block("'\\[success\\](.*?)\\[/success\\]'si", '');
				$tpl->set('[form]', '');
				$tpl->set('[/form]', '');
				$tpl->set('[error]', '');
				$tpl->set('[/error]', '');
			}
			// Добавляем пользовательские скрытые поля			
			$hiddenInputs = addHiddenFields($arHidden, $_POST);

		} else {
			// Если в реквесте нет поста — значит форму только что открыли
			$tpl->set_block("'\\[uf_token_error\\](.*?)\\[/uf_token_error\\]'si", '');
			$tpl->set_block("'\\[success\\](.*?)\\[/success\\]'si", '');
			$tpl->set_block("'\\[error\\](.*?)\\[/error\\]'si", '');
			$tpl->set('[form]', '');
			$tpl->set('[/form]', '');

			$tpl->copy_template = preg_replace("'\\{uf_field_(.*?)\\}'si", '', $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[uf_error_(.*?)\\](.*?)\\[/uf_error_(.*?)\\]'is", '', $tpl->copy_template);
			
			// Добавляем пользовательские скрытые поля			
			$hiddenInputs = addHiddenFields($arHidden, $_REQUEST['fields']);
		}


		$tpl->set('{debug}', $debugTag);

		// Компилим шаблон и выводим результат
		$tpl->compile('uniform');
		$uniform = $tpl->result['uniform'];

		if (!$cfg['nocache'] && !$isPost) {
			// Если нужно — создаём кеш
			create_cache($cfg['role'], $uniform, $cacheName . $config['skin'], true);
		}
		$tpl->clear();
	} else {
		$uniform = '<b style="color:red">Отсутствует файл шаблона: ' . $config['skin'] . '/' . $cfg['template'] . '.tpl</b>';
	}
}
$form = '
	<form action="/engine/ajax/uniform/uniform.php" data-uf-form method="POST">
	<input type="hidden" name="csrfToken" value="' . getToken($cacheName . $config['skin'] . $sessionId) . '">
	<input type="hidden" name="formConfig" value="' . $cfg['formConfig'] . '">
';
$form .= $hiddenInputs;
$form .= $uniform;
$form .= '</form>';
echo $form;

// Разные полезные функции
function getArray($string, $delimiter = ',') {
	$arr = explode($delimiter, $string);
	foreach ($arr as $k => $v) {
		$arr[$k] = trim($v);
	}
	return array_filter($arr);
}

function getToken($string) {
	return base64_encode($string);
}

function checkToken($first, $second) {
	if (getToken($second) == $first) {
		return true;
	}
	return false;
}

function validEmain($email) {
	$re = "/(.+)@(.+)\\.(.+)/i";
	return preg_match($re, $email, $matches);
}


function addHiddenFields($arFields, $requestArray) {
	global $db;
	$hiddenInputs = '';
	if (count($arFields) > 0 && is_array($requestArray)) {
		foreach ($arFields as $field) {
			if (array_key_exists($field, $requestArray)) {
				// @TODO - проработать вопрос безопасности такой конструкции.
				$hiddenInputs .= '<input type="hidden" name="' . $field . '" value="' . $db->safesql($requestArray[$field]) . '">';
				$hiddenInputs .= "\n";
			}
		}
	}
	return $hiddenInputs;
}