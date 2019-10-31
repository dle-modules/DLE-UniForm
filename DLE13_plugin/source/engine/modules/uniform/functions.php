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
 * Различные функции, используемые в файлах модуля.
 */

/**
 * Получение массиа из строки конфига
 *
 * @param  string $string    Строка конфига
 * @param  string $delimiter Разделитель массива
 *
 * @return array             Массив
 */
function getArray($string, $delimiter = ',') {
	$arr = explode($delimiter, $string);
	foreach ($arr as $k => $v) {
		$arr[$k] = trim($v);
	}

	return array_filter($arr);
}

/**
 * Получение зашифрованного CSRF-токена
 *
 * @param  string $string Токен
 *
 * @return string         Зашифрованный токен
 */
function getToken($string) {
	return base64_encode($string);
}

/**
 * Валидация CSRF-токена
 *
 * @param  string $first  Первый токен
 * @param  string $second Второй токен
 *
 * @return bool           true|false
 */
function checkToken($first, $second) {
	if (getToken($second) == $first) {
		return true;
	}

	return false;
}

/**
 * Валидация email-адреса
 *
 * @param  string $email Email-адрес
 *
 * @return bool          true|false
 */
function validEmain($email) {
	$re = "/(.+)@(.+)\\.(.+)/i";

	return preg_match($re, $email, $matches);
}

/**
 * Добавляем скрытые поля в форму
 *
 * @param  array $arFields     Поля, разрешенные в конфиге
 * @param  array $requestArray поля из реквеста
 *
 * @return string Поля, добавляемые в форму
 */
function addHiddenFields($arFields = [], $requestArray = []) {
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

/**
 * Функция для назначения тегов, обрабатывающих селекты, чекбоксы и радиокнопки.
 * Код вынесен в функцию для избежания дублирования.
 *
 * @param  string  $k          Имя поля
 * @param  string  $val        Значение поля
 * @param  string  $fieldType  Тип поля
 * @param  array   $arFields   Массив, содержащий разрешенные ключи
 * @param  boolean $parse      Объект класса parse.class.php
 * @param  boolean $tpl        Объект шаблонизатора
 * @param  array   $arSendMail Массив, отправляемый обработчику email
 *
 * @return array Массив для отправки по email
 */
function assignFiedls(
	$k = '',
	$val = '',
	$fieldType = '',
	$arFields = [],
	$parse = false,
	$tpl = false,
	$arSendMail = []
) {

	if (in_array($k, $arFields)) {
		// Если поле содержится в нужном массиве
		$arSendMailTmp = [];
		if (is_array($val)) {
			// Если поле множественное (множественный селект к примеру)
			foreach ($val as $valvalue) {
				// Нужно обработать поля, т.к. мы не обрабатывали массивы.
				$valvalue = convert_unicode($valvalue, $config['charset']);
				$valvalue = $parse->process(trim($valvalue));
				// Добавим значение в массив для последующей отправки на email в нормальном виде.
				$arSendMailTmp[] = $valvalue;
				$tpl->copy_template = str_replace("[uf_{$fieldType}_{$k}_{$valvalue}]", '', $tpl->copy_template);
				$tpl->copy_template = str_replace("[/uf_{$fieldType}_{$k}_{$valvalue}]", '', $tpl->copy_template);
			}
			// Добавляем данные из мультиселекта в массив для отправки в обработчик email т.к. ранее мы добавляли только обычные поля.
			$arSendMail[$k] = implode(', ', $arSendMailTmp);
		} else {
			$tpl->copy_template = str_replace("[uf_{$fieldType}_{$k}_{$val}]", '', $tpl->copy_template);
			$tpl->copy_template = str_replace("[/uf_{$fieldType}_{$k}_{$val}]", '', $tpl->copy_template);
		}

		$tpl->copy_template = preg_replace("'\\[uf_{$fieldType}_{$k}_(.*?)\\](.*?)\\[/uf_{$fieldType}_{$k}_(.*?)\\]'is", '', $tpl->copy_template);
	} else {
		// Удалем теги, которые не должны показываться
		$tpl->copy_template = preg_replace("'\\[uf_{$fieldType}_{$k}_(.*?)\\](.*?)\\[/uf_{$fieldType}_{$k}_(.*?)\\]'is", '', $tpl->copy_template);
	}

	return $arSendMail;
}

/**
 * Обрабатываем теги с условиями вывода
 *
 * @param array  $data      массив с данными
 * @param string $fieldType Тип поля
 * @param array  $arFields  массив с полями, относящимися к выбранному типу поля
 * @param obgect $tpl       Объект шаблонизатора
 *
 * @return array Массив с удалёнными обработанными данными
 */
function setConditions($data, $fieldType = '', $arFields = [], $tpl = false) {

	if (count($data) > 0) {
		// Если есть данные — работаем
		foreach ($data as $k => $val) {
			if (in_array($k, $arFields) || $fieldType == 'field') {
				// Если поле находится в массиве разрешенных полей или является текстовым — работаем
				if (preg_match_all("#\\[uf_{$fieldType}_{$k}=['\"](.+?)['\"]\\](.+?)\\[/uf_{$fieldType}_{$k}\\]#si", $tpl->copy_template, $matches)) {
					// Проверем совпадения
					foreach ($matches[1] as $key => $match) {
						if (is_array($val)) {
							// Если поле множественное
							foreach ($val as $kk => $valvalue) {
								if ($match == $valvalue) {
									if (isset($matches[0][$key])) {
										$tpl->set($matches[0][$key], $matches[2][$key]);
									}
									// Удаляем ненужное, иначе будут глюки.
									unset($matches[0][$key]);
								} else {
									$tpl->set($matches[0][$key], '');
								}
							}
						} else {
							if ($match == $val) {
								$tpl->set($matches[0][$key], $matches[2][$key]);
							} else {
								$tpl->set($matches[0][$key], '');
							}
						}
					}
				}
				if ($fieldType == 'field' && $val != '') {
					$tpl->set("[uf_{$fieldType}_{$k}]", '');
					$tpl->set("[/uf_{$fieldType}_{$k}]", '');
				}
				// Удаляем обработанный элемент массива
				unset($data[$k]);
			}
		}
	}

	return $data;
}