[header]
	Новый заказ звонка с сайта
[/header]
<h2 style="margin: 0; padding: 20px; color: #ffffff; background: #4b9fc5;">Новый заказ звонка с сайта</h2><div style="background: #fafafa; padding: 20px; color: #333333;">
	<b>Телефон: </b> <a href="tel:{phone}">{phone}</a> <br>
	{* Имя — необязательное поле, поэтому заключим его в теги, выводящие текст только если поле заполнено *}
	[uf_field_name]<b>Имя: </b> {name} <br>[/uf_field_name]

	<b>Удобное время для звонка: </b>
	{* В зависисмости от того, какое значение передано, выведем сообщение в почту. *}
	[uf_select_calltime="anytime"]в любое время[/uf_select_calltime]
	[uf_select_calltime="9-12"]с 9 утра до 12 дня[/uf_select_calltime]
	[uf_select_calltime="12-15"]с 12:00 до 15:00[/uf_select_calltime]
	[uf_select_calltime="15-18"]с 15:00 до 18:00[/uf_select_calltime]
	<br>
</div>