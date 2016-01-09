[header]
	Сообщение автору новости
[/header]
<h2 style="margin: 0; padding: 20px; color: #ffffff; background: #4b9fc5;">Новое сообщение автору новости</h2>
<p>
	Здравствуйте <b>{news_autor}</b>!
</p>
<p>
	На сайте "{site_short_title}" к вашей новости <a href="{site_http_home_url}?newsid={news_id}" target="_blank">{news_title}</a> было отправлено сообщение через форму обратной связи.
</p>
<div style="background: #fafafa; padding: 20px; color: #333333;">
	<b>Дата отправки: </b> {send_date} <br>
	<b>Email отправителя: </b> {email} <br>
	<b>Текст сообщения: </b> <br>
	{textarea}
</div>
<p>Сообщение отправлено со страницы: {current_page}</p>
<p>Вы можете связаться с автором сообщения самостоятельно по указанному email-адресу.</p>