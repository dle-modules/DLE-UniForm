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
	'header' => 'Использование',

	// Текст с описанием шага шага
	'text' => '<p>В нужном месте любого шаблона сайта вставить:</p>
	<textarea readonly class="code" rows="4"><span class="uf-btn" 
	data-uf-open="/engine/ajax/uniform/uniform.php" 
	data-uf-settings=\'{"formConfig": "feedback"}\'
>Обратная связь</span></textarea>
<p>где feedback — папка с шаблонами формы. Из этой папки будет взят конфиг для формы.</p>
<p>Так же в комплекте с формой поставляются формы:</p>
<ul>
	<li>attachments</li>
	<li>callback</li>
	<li>customheader</li>
	<li>feedback</li>
	<li>inline</li>
	<li>newsauthor</li>
	<li>test</li>
</ul>
<p>В каждом файле form.tpl вы сможете найти код, который необходимо вставить в шаблон.</p>
<hr>
<p>Для вывода формы непосредственно на старнице, а не во всплывающем окне, необходимо использовать следующий код:</p>
<textarea readonly class="code" rows="3"><div data-uf-inline="/engine/ajax/uniform/uniform.php" data-uf-settings=\'{"formConfig": "inline"}\'>
	<div class="uf-inline-loading"></div>
</div></textarea>
	'
];
