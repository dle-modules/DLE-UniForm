<?php
/*
 * DLE UniForm — унверсальные формы для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       http://pafnuty.name/
 * @link       https://twitter.com/pafnuty_name
 */

return [

	// Заголовок шага
	'header' => 'Добавление стилей и скриптов модуля',

	// Текст с описанием шага
	'text' => 'В шаблоне <b>%THEME%/main.tpl</b>',

	// Код, который необходимо вставить
	// 'paste' => 'someCode to paste',

	// Код, который необходимо найти
	'find' => '</head>',

	// Код, который необходимо вставить перед найденным
	'addBfore' => '<!-- DLE UniForm -->
<link rel="stylesheet" href="/engine/classes/min/index.php?charset=utf-8&amp;f={THEME}/uniform/css/uniform.css&amp;113" />
<script src="/engine/classes/min/index.php?charset=utf-8&amp;f={THEME}/uniform/js/jquery.magnificpopup.min.js,{THEME}/uniform/js/jquery.ladda.min.js,{THEME}/uniform/js/jquery.form.min.js,{THEME}/uniform/js/uniform.js&amp;113"></script>
<!-- /DLE UniForm -->',

];
