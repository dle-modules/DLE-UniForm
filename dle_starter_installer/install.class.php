<?php
/*
 * DLE-StarterKit
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       https://git.io/vPLpe
 */


/**
 * Class dleStarterInstaller
 */
class dleStarterInstaller {
	/**
	 * @var array
	 */
	public $dle_config = [];

	/**
	 * @var array
	 */
	public $cfg = [];

	/**
	 * @var string
	 */
	public $engineDir = '';

	/**
	 * @var string
	 */
	public $moduleDir = '';

	/**
	 * @var mixed
	 */
	public $db;

	/**
	 * dleStarterInstaller constructor.
	 *
	 * @param $moduleName
	 */
	function __construct($moduleName) {
		// Определяем пути к папкам
		$this->engineDir = dirname(__DIR__) . '/engine';
		$this->moduleDir = $this->engineDir . '/modules/' . $moduleName;

		// Определяем конфиги
		$this->dle_config = $this->getDleConfig();
		$this->cfg = $this->getConfig();
		$this->db = $this->getDb();

	}

	/**
	 * @return array
	 */
	private function getDleConfig() {
		include_once $this->engineDir . '/data/config.php';

		/** @var array $config */
		return $config;
	}


	/**
	 * @return array
	 */
	private function getConfig() {
		$configFile = $this->moduleDir . '/install/config.php';
		if (!file_exists($configFile)) {
			return [];
		} else {
			return include_once $configFile;
		}
	}

	/**
	 * @return mixed
	 */
	private function getDb() {
		include_once $this->engineDir . '/classes/mysql.php';
		include_once $this->engineDir . '/data/dbconfig.php';

		/** @var object $db */
		return $db;
	}

	/**
	 * @param string $fileName
	 *
	 * @return string
	 */
	public function getTextFile($fileName = 'licence') {
		$configFile = $this->moduleDir . '/install/' . $fileName . '.php';
		if (!file_exists($configFile)) {
			return '';
		} else {
			return include_once $configFile;
		}
	}

	/**
	 * @throws Exception
	 */
	public function checkBeforeInstall() {
		// @TODO перетащить текст в языковые файлы
		if (isset($this->cfg['minVersion'])) {
			if ($this->dle_config['version_id'] < $this->cfg['minVersion']) {
				throw new Exception('Установленная версия DLE слишком старая. Необходимо установить DLE не ниже ' . $this->cfg['minVersion']);
			}

			if ($this->cfg['maxVersion'] !== '' && $this->dle_config['version_id'] > $this->cfg['maxVersion']) {
				throw new Exception('Установленная версия DLE слишком новая. Необходимо установить DLE не выше ' . $this->cfg['maxVersion']);
			}
		} else {
			throw new Exception('Файл с конфигурацией установки модуля не найден, возмжно установочные файлы модуля не скопированы. <br>Возможно вы не вписали параметр <b>?module=module_name</b> в конце URL. (module_name — это идентификатор модуля. Если Вы его не знаете, то можете спросить у автора модуля).');
		}
	}

	/**
	 * @return array
	 */
	public function getSteps() {
		$files = [];

		foreach (glob($this->moduleDir . '/install/steps/*.php') as $file) {
			$files[] = include_once($file);
		}
		return $files;
	}

	/**
	 * Установка административной части модуля
	 *
	 * @param $name  string название модуля, а именно файла .php находящегося в папке engine/inc/,
	 *               но без расширения файла
	 * @param $title string заголовок модуля
	 * @param $descr string описание модуля
	 * @param $icon  string имя иконки для модуля, без указания пути.
	 *               Иконка обязательно при этом должна находится в папке engine/skins/images/
	 * @param $perm  string информация о группах которым разрешен показ данного модуля.
	 *               Данное поле может принимать следующие значения: all или ID групп через запятую.
	 *               Например: 1,2,3. если указано значение all то модуль будет показываться всем
	 *               пользователям имеющим доступ в админпанель
	 *
	 * @return bool - true если успешно установлено и false если нет
	 */
	public function installAdmin($name, $title, $descr, $icon, $perm = '1') {
		$name = $this->db->safesql($name);
		$title = $this->db->safesql($title);
		$descr = $this->db->safesql($descr);
		$icon = $this->db->safesql($icon);
		$perm = $this->db->safesql($perm);
		// Для начала проверяем наличие модуля
		$this->db->query("SELECT name FROM`" . PREFIX . "_admin_sections` where name = '$name'");
		if ($this->db->num_rows() > 0) {
			// Модуль есть, обновляем данные
			$this->db->query("UPDATE `" . PREFIX . "_admin_sections` set title = '$title', descr = '$descr', icon = '$icon', allow_groups = '$perm' where name = '$name'");
			return true;
		} else {
			// Модуля нет, добавляем
			$this->db->query("INSERT INTO `" . PREFIX . "_admin_sections` (`name`, `title`, `descr`, `icon`, `allow_groups`) VALUES ('$name', '$title', '$descr', '$icon', '$perm')");
			return true;
		}
	}

	/**
	 * Удаление административной части модуля
	 *
	 * @param $name string - название модуля
	 *
	 * @return null
	 */
	public function uninstallAdmin($name) {
		$name = $this->db->safesql($name);
		$this->db->query("DELETE FROM `" . PREFIX . "_admin_sections` where name = '$name'");
	}

	/**
	 * Заменяем некоторые теги на их представления по аналогии с DLE
	 *
	 * @param string $text
	 * @param string $openBrace
	 * @param string $closeBrace
	 *
	 * @return string
	 */
	public function replaceTags($text = '', $openBrace = '%', $closeBrace = '%') {
		// Заменяем %THEME%
		$text = str_replace($openBrace . 'THEME' . $closeBrace, 'templates/' . $this->dle_config['skin'], $text);

		return $text;

	}

}
