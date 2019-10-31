
 <?php
/*
 * uniform_help — справочная информация для DLE-Uniform
 *
 * @author     Talik <talik@tcse-cms.com>
 */

if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
	die('Hacking attempt!');
}
/**
 * @global $member_id
 */

define('MODULE_DIR', ENGINE_DIR . '/modules/mod_tcse/uniform_help');

echoheader('uniform_help', 'справочная информация для DLE-Uniform');

include MODULE_DIR . '/admin/main.php';

include MODULE_DIR . '/admin/footer.php';

echofooter();

