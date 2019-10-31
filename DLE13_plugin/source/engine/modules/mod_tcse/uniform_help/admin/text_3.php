<?php
if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
    die('Hacking attempt!');
}
?>


<div class="panel-body">
	 


<h3>Добавлено в v1.2</h3>
<p>
    <b>Улучшения и исправления</b>
</p>
<ul>
    <li> Теперь если в форме есть заполненное поле с `name="header"`, такое поле будет автоматически подставлено в тему письма.<br></li>
    <li> Исправлена ошибка на DLE 10.5+ с невозможностью указать email отправителя из формы.<br></li>
    <li> Исправлена ошибка с экранированием кавычек в письме.<br></li>
    <li> Если пользователь авторизован, то тег <b>{uf_field_email}</b> при открытии формы автоматически заменится на его email.<br></li>
    <li> Убран тег <b>{send_date=D.m.Y}</b> т.к. он оказался нерабочим.<br></li>
    <li> Исправлено некорректное поведение тега <b>{send_date}</b>.<br></li>
    <li> Добавлен новый параметр конфигурации <b>sendAsPlain</b> — Отправлять сообщение как простой текст. Теперь можно отправлять письма как простые текстовые сообщения, без обработки html.<br></li>
    <li> Добавлен новый параметр конфигурации <b>sendToAuthor</b> — Отправить письмо автору новости, если есть поле с <b>name="newsId"</b> и если автор разрешил получение писем с сайта.<br></li>
    <li> Реализована возможность отправлять в письме данные из новости. Для этого необходимо передать в форму поле <b>newsId</b>. Пример шаблона в папке <b>newsauthor</b>.<br></li>
    <li> Реализована возможность отправки сообщения на email автора новости. Для этого необходимо передать в форму поле <b>newsId</b> и в конфиге прописать <b>sendToAuthor = y</b>. Пример шаблона в папке <b>newsauthor</b>.<br></li>
</ul>
<p>
    <b>Новые теги для email-сообщений</b>
</p>
<ul>
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
</ul>
<h3>Добавлено в v1.3</h3>
<ul>
    <li> <b>Новый, удобный установщик модуля.</b><br></li>
    <li> <b>Добавлена возможность пикреплять файлы к сообщению в форме.</b> Для этого необходимо в конфиге указать параметр <b>allowAttachments</b>, а так же параметры <b>maxFileSize</b> (максимальный размер прикрепляемого файла) и <b>allowedFileTypes</b> (доступные типы файлов) при необходимости. Готовый шаблон для прикрепления файлов к сообщению — <b>attachments</b>.<br></li>
    <li> Добавлен новый параметр конфигурации <b>sendToSender</b>. Если этот параметр задан, то письмо будет отправлено на email-адрес, указанный в поле email. Так же проверяется валидность этого адреса.<br></li>
    <li> В шаблон email-сообщения добавлены новые теги, обрабатываемые в сообщении и в поле заголовке письма, когда активирован параметр <b>sendToSender</b>.<br><b>[to_sender] текст для отправителя [/to_sender]</b><br><b>[not_to_sender] текст для других получателей [/not_to_sender]</b><br></li>
    <li> Добавлены почти все поля из профиля пользователя в шаблон email.<br></li>
    <li> Добавлен вывод допполей из профиля пользователя в шаблон email.<br></li>
    <li> Ко всем тегам в шаблонах email-сообщения добавлены противоположные теги <b>[not_X]</b>, выводящие текст между ними, когда соответствующий тег <b>{X}</b> пуст.<br></li>
    <li> Исправлена ошибка, когда пользователю разрешено менять шаблон сайта, а шаблон формы берётся из шаблона, установленного по умолчанию в системе.<br></li>
    <li> Улучшения и оптимизация кода.<br></li>
    <li> Теперь минимально-допустимая версия php - 5.4.<br></li>
</ul>
<h3>Добавлено в v1.4</h3>
<ul>
    <li> Обновлён установщик<br></li>
    <li> Добавлен новый параметр <b>parseSendMail</b>, добавляющий возможность производить манипуляции над данными перед отправкой email. В параметр передаётся путь к php файлу, без расширения, отностительно папки engine/modules. Например можно добавить в шаблон email-сообщения новые теги <b>[footer]{footer}[/footer]</b>. Для этого в конфиге прописываем <b>parseSendMail = addFooter</b>, создаём файл <b>engine/modules/addFooter.php</b> со следующим содержимым:<br><pre><code><span class="preprocessor">&lt;?php</span>
<span class="keyword">if</span> (<span class="variable">$arSendMail</span>[<span class="string">'select1'</span>] === <span class="string">'val1'</span>) {
    <span class="variable">$mailTpl</span>-&gt;set(<span class="string">'[footer]'</span>, <span class="string">''</span>);
    <span class="variable">$mailTpl</span>-&gt;set(<span class="string">'[/footer]'</span>, <span class="string">''</span>);
    <span class="variable">$mailTpl</span>-&gt;set(<span class="string">'{footer}'</span>, <span class="variable">$arSendMail</span>[<span class="string">'select1'</span>]);
} <span class="keyword">else</span> {
    <span class="variable">$mailTpl</span>-&gt;set_block(<span class="string">"'[footer](.*?)[/footer]'si"</span>, <span class="string">''</span>);
}</code></pre><br><br></li>
</ul>
</div>
