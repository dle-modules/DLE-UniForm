<?php
if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
    die('Hacking attempt!');
}
?>


<div class="panel-body">
	 
<h3>Установка</h3>
<p>
	Если вы читаете данный текст, значит админка и файлы модуля были загружены на ваш сайт. <br>
	Теперь необходимо в ваш шаблон <b>main.tpl</b> добавить стили и скрипты для работы DLE-Uniform.
</p>
<p>
	Найдите тег:
</p>
<code> &lt;/head> </code>
<p>
	и перед ним вставьте:
</p>
<pre><code>
<!-- DLE UniForm -->
	&lt;link rel="stylesheet" href="/engine/classes/min/index.php?charset=utf-8&amp;f={THEME}/uniform/css/uniform.css&amp;02" />
	&lt;script type="text/javascript" src="/engine/classes/min/index.php?charset=utf-8&amp;f={THEME}/uniform/js/jquery.magnificpopup.min.js,{THEME}/uniform/js/jquery.ladda.min.js,{THEME}/uniform/js/jquery.form.min.js,{THEME}/uniform/js/uniform.js&amp;02">&lt;/script>
	<!-- /DLE UniForm --></code></pre>

<p>
	Далее в удобном месте шаблона можете вставить код кнопки для запуска формы.
</p>

<pre><code>&lt;span data-uf-open="/engine/ajax/uniform/uniform.php" data-uf-settings='{"formConfig": "_feedback"}' class="uf-btn">Обратная связь&lt;/span> </code></pre>



<h3>Настройка</h3>
<ul>
    <li>Описание и примеры конфигурации вызова модуля можно найти в файлах, с именем <b>config.tpl</b>.</li>
    <li>Описание и примеры параметров формы можно найти в файле <b>{THEME}/uniform/test/form.tpl</b>.</li>
    <li>Описание и примеры параметров email-сообщения можно найти в файле <b>{THEME}/uniform/_callback/email.tpl</b>.</li>
</ul>

<h3>Теги шаблонов</h3><br><b>Список тегов, поддерживаемых в шаблоне form.tpl.</b><br><i>Вместо <b>X</b> следует прописывать имя инпута, селекта, чекбокса или радиокнопки. Имена не должны дублироваться.</i><br>
<ul>
    <li> <b>{* текст *}</b> — Служебный комментарий. Текст, заключенный в такие теги, не будет выведен в браузер.<br></li>
    <li> <b>[error]текст[/error]</b> — выводит текст, если форма содержит ошибки заполнения.<br></li>
    <li> <b>[success]текст[/success]</b> — выводит текст, если форма удачно отправлена.<br></li>
    <li> <b>[form]текст[/form]</b> — выводит текст, если форма только что открыта, или отправлена с ошибками.<br></li>
    <li> <b>[debug]{debug}[/debug]</b> — выводит дебаг.<br></li>
    <li> <b>[uf_token_error]текст[/uf_token_error]</b> — выводит текст, если происходит попытка межсайтовой подделки запроса (CSRF Attack) или если конфиг формы изменился во время заполнения формы.<br></li>
    <li> <b>[uf_email_error]текст[/uf_email_error]</b> — выводит текст, если поле имеет имя email, является обязательным и не проходит валидацию (наличие символа @ и точки).<br></li>
    <li> <b>[uf_default_value]текст[/uf_default_value]</b> — выводит текст, если форма открыта впервые.<br></li>
    <li> <b>{uf_filed_X}</b> — выводит данные, переданные в форму из текстовых полей в случаи ошибочного заполнения.<br></li>
    <li> <b>[uf_field_X="Y"]текст[/uf_field_X]</b> — Выводит текст, если в текстовое поле X передано значение Y.<br></li>
    <li> <b>[uf_error_X]текст[/uf_error_X]</b> — выводит текст, если текстовое поле содержит ошибку.<br></li>
    <li> <b>[uf_select_X="Y"]текст[/uf_select_X]</b> — Выводит текст, если в селекте X отмечен пункт со значением Y.<br></li>
    <li> <b>[uf_select_X_Y]selected[/uf_select_X_Y]</b> — Выводит текст, если в селекте X отмечен пункт со значением Y. Является вариацией предыдущего тега.<br></li>
    <li> <b>[uf_checkbox_X="Y"]текст[/uf_checkbox_X]</b> — Выводит текст, если отмечен чекбокс с именем X, содержащий значение Y.<br></li>
    <li> <b>[uf_checkbox_X_Y]checked[/uf_checkbox_X_Y]</b> — Выводит текст, если отмечен чекбокс с именем X, содержащий значение Y. Является вариацией предыдущего тега.<br></li>
    <li> <b>[uf_radio_X="Y"]текст[/uf_radio_X]</b> — Выводит текст, если отмечена радиокнопка с именем X, содержащая значение Y.<br></li>
    <li> <b>[uf_radio_X_Y]checked[/uf_radio_X_Y]</b> — Выводит текст, если отмечена радиокнопка с именем X, содержащая значение Y. Является вариацией предыдущего тега.<br></li>
</ul><br><br><b>Список тегов, поддерживаемых в шаблоне email.tpl.</b><br><i>Вместо <b>X</b> следует прописывать имя инпута, селекта, чекбокса или радиокнопки. Имена не должны дублироваться.</i><br>
<ul>
    <li> <b>{* текст *}</b> — Служебный комментарий. Текст, заключенный в такие теги, не будет выведен в сообщении.<br></li>
    <li> <b>[header]текст[/header]</b> — Тема письма.<br></li>
    <li> <b>{all_mail_fields}</b> — выводит все поля, переданные из формы в удобном, для последующей вставке в шаблон, виде.<br></li>
    <li> <b>[uf_field_X]текст[/uf_field_X]</b> — выводит текст, если текстовое пол X заполнено.<br></li>
    <li> <b>{X}</b> — выводит данные, переданные в форму из текстового поля X.<br></li>
    <li> <b>[not_X]текст[/not_X]</b> — Выводит текст, если в текстовое поле X пустое.<br></li>
    <li> <b>[uf_field_X="Y"]текст[/uf_field_X]</b> — Выводит текст, если в текстовое поле X передано значение Y.<br></li>
    <li> <b>[uf_select_X="Y"]текст[/uf_select_X]</b> — Выводит текст, если в селекте X отмечен пункт со значением Y.<br></li>
    <li> <b>[uf_checkbox_X="Y"]текст[/uf_checkbox_X]</b> — Выводит текст, если отмечен чекбокс с именем X, содержащий значение Y.<br></li>
    <li> <b>[uf_radio_X="Y"]текст[/uf_radio_X]</b> — Выводит текст, если отмечена радиокнопка с именем X, содержащая значение Y.<br></li>
    <li> <b>{send_date}</b> — выводит дату отправки сообщения из формы, отформатированную в соответсвии с настройками DLE.<br></li>
    <li> <b>{current_page}</b> — выводит URL страницы, с которой было отправлено сообщение.<br></li>
    <li> <b>[news_id]{news_id}[/news_id]</b> — ID новости (если есть поле с name="newsId").<br></li>
    <li> <b>[news_autor]{news_autor}[/news_autor]</b> — Логин автора новости (если есть поле с name="newsId").<br></li>
    <li> <b>[news_title]{news_title}[/news_title]</b> — Заголовок новости (если есть поле с name="newsId").<br></li>
    <li> <b>[news_email]{news_email}[/news_email]</b> — Email автора новости (если есть поле с name="newsId").<br></li>
    <li> <b>{site_home_title}</b> — Выводит название сайта из настроек DLE.<br></li>
    <li> <b>{site_http_home_url}</b> — Выводит адрес сайта из настроек DLE.<br></li>
    <li> <b>{site_short_title}</b> — Выводит краткое название сайта из настроек DLE.<br></li>
    <li> <b>[user_name]{user_name}[/user_name]</b> — Выводит логин пользователя, отправившего сообщение.<br></li>
    <li> <b>[user_fullname]{user_fullname}[/user_fullname]</b> — Выводит полное имя пользователя, отправившего сообщение.<br></li>
    <li> <b>[user_email]{user_email}[/user_email]</b> — Выводит email пользователя, отправившего сообщение.<br></li>
    <li> <b>[user_foto]{user_foto}[/user_foto]</b> — Выводит ссылку на аватар пользователя, отправившего сообщение.<br></li>
    <li> <b>[user_land]{user_land}[/user_land]</b> — Выводит то, что пользователь, отправивший сообщение, написал в поле "Место жительства".<br></li>
    <li> <b>[to_sender] текст [/to_sender]</b> — Выводит текст для отправителя, если указан параметр <b>sendToSender</b><br></li>
    <li> <b>[not_to_sender] текст [/not_to_sender]</b> — Выводит текст для получателей письма, отличных от отправителя, если указан параметр <b>sendToSender</b><br></li>
</ul><br>











</div>
