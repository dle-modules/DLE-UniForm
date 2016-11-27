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
 * @global boolean $is_logged  Является ли посетитель авторизованным пользователем или гостем.
 * @global array   $member_id  Массив с информацией о авторизованном пользователе, включая всю его информацию из профиля.
 * @global object  $db         Класс DLE для работы с базой данных.
 * @global array   $config     Информация обо всех настройках скрипта.
 * @global array   $user_group Информация о всех группах пользователей и их настройках.
 * @global integer $_TIME      Содержит текущее время в UNIX формате с учетом настроек смещения в настройках скрипта.
 */

// Подключаем конфиг модуля
include ENGINE_DIR . '/modules/uniform/cfg.php';

// Подключаем функции модуля
include ENGINE_DIR . '/modules/uniform/functions.php';

// Имя кеша
/** @var array $cfg */
$cacheName = md5(implode('_', $cfg));
// ID сессии
$sessionId = session_id();

$uniform = false;
$hiddenInputs = '';
// Если данные передаются постом — "запомним" это.
$isPost = ($_SERVER['REQUEST_METHOD'] === 'POST') ? true : false;

// Определяемся с шаблоном сайта
// Проверим куку пользователя и налочие параметра skin в реквесте.
$currentSiteSkin = (isset($_COOKIE['dle_skin'])) ? trim(totranslit($_COOKIE['dle_skin'], false, false))
	: ((isset($_REQUEST['skin'])) ? trim(totranslit($_REQUEST['skin'], false, false)) : $config['skin']);

$config['skin'] = ($currentSiteSkin == '') ? $config['skin'] : $currentSiteSkin;

if (!$cfg['nocache'] && !$isPost) {
	// Проверим кеш, если это требуется
	$uniform = dle_cache($cfg['role'], $cacheName . $config['skin'], true);
}
if (!$uniform) {
	if (!defined('TEMPLATE_DIR')) {
		require_once ENGINE_DIR . '/classes/templates.class.php';
		$tpl = new dle_template();
		$tpl->dir = $template_dir;
		define('TEMPLATE_DIR', $tpl->dir);
	}

	if (file_exists(TEMPLATE_DIR . '/uniform/' . $cfg['templateFolder'] . '/form.tpl')) {
		// Если файл шаблона существует — работаем.
		$tpl->result['uniform'] = '';
		$tpl->load_template('uniform/' . $cfg['templateFolder'] . '/form.tpl');

		// Пользовательские скрытые поля
		$arHidden = getArray($cfg['hidden']);

		// Дебаг
		$debug = ($cfg['debug']) ? true : false;
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
		$arSendMail = [];

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
			$parse = new ParseFilter();

			if (!checkToken($_POST['csrfToken'], $cacheName . $config['skin'] . $sessionId)) {
				// Проверяем токен
				$error = true;
				$tpl->set('[uf_token_error]', '');
				$tpl->set('[/uf_token_error]', '');
			} else {
				$tpl->set_block("'\\[uf_token_error\\](.*?)\\[/uf_token_error\\]'si", '');
			}

			// Определяем переменную для передачи в различные функции
			$post = $mailPost = $_POST;
			unset($post['csrfToken'], $post['formConfig'], $mailPost['csrfToken'], $mailPost['formConfig']);

			// Если в посте передано поле newsId, нужно получить информацию из новости
			if (isset($post['newsId']) && (int)$post['newsId'] > 0) {
				$newsItem = $db->super_query('SELECT p.id, p.autor, p.title, u.name, u.email, u.allow_mail FROM ' . PREFIX . '_post p LEFT JOIN ' . USERPREFIX . '_users u ON (p.autor=u.name) WHERE id = ' . (int)$post['newsId']);
				// Если запрос нашёл новость — работаем
				if ($newsItem['id'] > 0) {
					// Если автор новости разрешил отправлять ему письма и в конфиге есть sendToAuthor — добавим ещё одного получателя
					if ($newsItem['allow_mail'] && $cfg['sendToAuthor']) {
						$cfg['emails'] .= ',' . $newsItem['email'];
					}

					// Добавим в массив $_POST данные из новости
					foreach ($newsItem as $key => $value) {
						if ($key == 'title') {
							$value = stripslashes($value);
						}
						$_POST['news_' . $key] = $value;
					}
				}
			}

			// Добавляем данные из конфига DLE для возможности использовать в email сообщении
			$_POST['site_home_title'] = $config['home_title'];
			$_POST['site_http_home_url'] = $config['http_home_url'];
			$_POST['site_short_title'] = $config['short_title'];

			// Получаем массив обязательных полей
			$arRequired = getArray($cfg['required']);

			// Получаем массив, содержащий поля-селекты
			$arSelectFields = getArray($cfg['selectFields']);
			// Проверяем условия для селектов
			$post = setConditions($post, 'select', $arSelectFields, $tpl);

			// Получаем массив, содержащий поля-чекбоксы
			$arCheckboxFields = getArray($cfg['checkboxFields']);
			// Проверяем условия для чекбоксов
			$post = setConditions($post, 'checkbox', $arCheckboxFields, $tpl);

			// Получаем массив, содержащий поля-радиокнопки
			$arRadioFields = getArray($cfg['radioFields']);
			// Проверяем условия для радиокнопок
			$post = setConditions($post, 'radio', $arRadioFields, $tpl);

			// Проверяем условия для простых полей
			$post = setConditions($post, 'field', [], $tpl);

			// Проверяем обязательные поля
			foreach ($_POST as $k => $val) {
				// Поля csrfToken и formConfig нас не интересуют.
				if (in_array($k, ['csrfToken', 'formConfig'])) {
					continue;
				}
				// Остальные поля надо бы обработать
				if (!is_array($val)) {
					$val = convert_unicode($val, $config['charset']);
					$val = $parse->process(trim($val));
					$arSendMail[$k] = $val;
				}

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

				if (count($arSelectFields) > 0) {
					// Назначаем обработку полей селектов и добавляем данные в массив для отправки на почту
					$arSendMail = assignFiedls($k, $val, 'select', $arSelectFields, $parse, $tpl, $arSendMail);
					// setConditions($k, $val, 'select', $arSelectFields, $tpl);
				}

				if (count($arCheckboxFields) > 0) {
					// Назначаем обработку полей чекбоксов и добавляем данные в массив для отправки на почту
					$arSendMail = assignFiedls($k, $val, 'checkbox', $arCheckboxFields, $parse, $tpl, $arSendMail);
				}

				if (count($arRadioFields) > 0) {
					// Назначаем обработку полей чекбоксов и добавляем данные в массив для отправки на почту
					$arSendMail = assignFiedls($k, $val, 'radio', $arRadioFields, $parse, $tpl, $arSendMail);
					// setConditions($k, $val, 'radio', $arRadioFields,  $tpl);
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

			// Массив для прикрепленных файлов
			$arSendAttachments = [];
			// Массив для файлов, не прикреплённых по разным причинам
			$arNotAttachedFiles = [];
			// Массив с разрешенными типами файлов
			$arAllowedTypes = getArray($cfg['allowedFileTypes']);

			// Проверяем вложения
			if (isset($_FILES) && $cfg['allowAttachments']) {
				foreach ($_FILES as $fileItem) {

					if (is_array($fileItem['name'])) {
						// Если массив — пробежимся по нему.
						foreach ($fileItem['name'] as $i => $f) {
							// Определяем тип файла
							$ext = (new SplFileInfo($fileItem['name'][$i]))->getExtension();

							// Если тип файла не в списке — идём дальше 
							if (count($arAllowedTypes) && !in_array($ext, $arAllowedTypes)) {
								$arNotAttachedFiles[] = $fileItem['name'][$i];
								continue;
							}

							// Если есть ошибки — добавим в список имя ошибочного файла
							if ($fileItem['error'][$i] > 0) {
								$arNotAttachedFiles[] = $fileItem['name'][$i];
							} else {
								// Если ошибок не — можно отправлять такой файл
								$arSendAttachments[] = [
									'tmp_name' => $fileItem['tmp_name'][$i],
									'name'     => $fileItem['name'][$i]
								];
							}
						}

					} else {
						// Определяем тип файла
						$ext = (new SplFileInfo($fileItem['name']))->getExtension();
						// Если тип файла не в списке — идём дальше 
						if (count($arAllowedTypes) && !in_array($ext, $arAllowedTypes)) {
							$arNotAttachedFiles[] = $fileItem['name'];
						} else {
							// Если есть ошибки — добавим в список имя ошибочного файла
							if ($fileItem['error'] > 0) {
								$arNotAttachedFiles[] = $fileItem['name'];
							} else {
								// Если ошибок не — можно отправлять такой файл
								$arSendAttachments[] = [
									'tmp_name' => $fileItem['tmp_name'],
									'name'     => $fileItem['name']
								];
							}
						}

					}
				}
			}

			$arSendMail['not_attached_files'] = implode(', ', $arNotAttachedFiles);

			if (count($arNotAttachedFiles)) {
				$tpl->set('[attachments_error]', '');
				$tpl->set('[/attachments_error]', '');
				$tpl->set('{not_attached_files}', $arSendMail['not_attached_files']);
			} else {
				$tpl->set('{not_attached_files}', '');
				$tpl->set_block("'\\[attachments_error\\](.*?)\\[/attachments_error\\]'si", '');
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
			$tpl->copy_template = preg_replace("'\\[uf_default_value\\](.*?)\\[/uf_default_value\\]'is", '', $tpl->copy_template);
			// Добавляем пользовательские скрытые поля
			$hiddenInputs = addHiddenFields($arHidden, $_POST);

		} else {
			// Если в реквесте нет поста — значит форму только что открыли
			$tpl->set_block("'\\[uf_token_error\\](.*?)\\[/uf_token_error\\]'si", '');
			$tpl->set_block("'\\[success\\](.*?)\\[/success\\]'si", '');
			$tpl->set_block("'\\[error\\](.*?)\\[/error\\]'si", '');
			$tpl->set_block("'\\[attachments_error\\](.*?)\\[/attachments_error\\]'si", '');
			$tpl->set('[form]', '');
			$tpl->set('[/form]', '');
			$tpl->set('[uf_default_value]', '');
			$tpl->set('[/uf_default_value]', '');
			$tpl->set('{not_attached_files}', '');

			// Если пользователь авторизован — подставим его email в поле email.
			if ($member_id['user_group'] !== 5) {
				$tpl->copy_template = str_replace('{uf_field_email}', $member_id['email'], $tpl->copy_template);
			}
			$tpl->copy_template = preg_replace("'\\{uf_field_(.*?)\\}'si", '', $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[uf_error_(.*?)\\](.*?)\\[/uf_error_(.*?)\\]'is", '', $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[uf_email_error\\](.*?)\\[/uf_email_error\\]'is", '', $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[uf_select_(.*?)\\](.*?)\\[/uf_select_(.*?)\\]'is", '', $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[uf_checkbox_(.*?)\\](.*?)\\[/uf_checkbox_(.*?)\\]'is", '', $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[uf_radio_(.*?)\\](.*?)\\[/uf_radio_(.*?)\\]'is", '', $tpl->copy_template);
			$tpl->copy_template = preg_replace("'\\[uf_field_(.*?)\\](.*?)\\[/uf_field_(.*?)\\]'is", '', $tpl->copy_template);

			// Добавляем пользовательские скрытые поля
			$hiddenInputs = addHiddenFields($arHidden, $_REQUEST['fields']);
		}

		$tpl->set('{debug}', $debugTag);
		// Обрабатываем комментарии в шаблоне, которые не должны попасть в вывод
		$tpl->copy_template = preg_replace("'\\{\\*(.*?)\\*\\}'si", '', $tpl->copy_template);

		// Компилим шаблон и выводим результат
		$tpl->compile('uniform');
		$uniform = $tpl->result['uniform'];
		// Костыль, но подругому никак, нельзя выловить то, чего нет.
		// Поэтому приходится пробегать по тегам, оставшимся от работы функции setConditions
		$uniform = preg_replace("'\\[uf_field_(.*?)\\](.*?)\\[/uf_field_(.*?)\\]'is", '', $uniform);
		$uniform = preg_replace("'\\[uf_select_(.*?)\\](.*?)\\[/uf_select_(.*?)\\]'is", '', $uniform);
		$uniform = preg_replace("'\\[uf_checkbox_(.*?)\\](.*?)\\[/uf_checkbox_(.*?)\\]'is", '', $uniform);
		$uniform = preg_replace("'\\[uf_radio_(.*?)\\](.*?)\\[/uf_radio_(.*?)\\]'is", '', $uniform);

		if (!$cfg['nocache'] && !$isPost) {
			// Если нужно — создаём кеш
			create_cache($cfg['role'], $uniform, $cacheName . $config['skin'], true);
		}
		$tpl->clear();
	} else {
		$uniform = '<b style="color:red">Отсутствует файл шаблона: ' . $config['skin'] . '/uniform/' . $cfg['templateFolder'] . '/form.tpl</b>';
	}
}
// Добавляем спцальный атрибут для корректной загрузки файлов
$multipart = ($cfg['allowAttachments']) ? 'enctype="multipart/form-data"' : '';

// Добавляем инпут с указанием максимально возможного веса файла
$cfg['maxFileSize'] = (int)$cfg['maxFileSize'];
$maxFileSize = ($cfg['maxFileSize'] > 0) ? $cfg['maxFileSize'] : 0;


$form = '
	<form action="/engine/ajax/uniform/uniform.php" data-uf-form method="POST" ' . $multipart . '>
	<input type="hidden" name="csrfToken" value="' . getToken($cacheName . $config['skin'] . $sessionId) . '">
	<input type="hidden" name="formConfig" value="' . $cfg['formConfig'] . '">
';
$form .= ($cfg['allowAttachments'] && $maxFileSize > 0)
	? '<input type="hidden" name="MAX_FILE_SIZE" value="' . $maxFileSize * 1024 . '" />' : '';
$form .= $hiddenInputs;
$form .= $uniform;
$form .= '</form>';
echo $form;
