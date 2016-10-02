{* 
	Шаблон для демонстрации возможности написать автору новости.

	Для вывода кнопки открытия формы используем код:
	Для вставки в шаблон полной или краткой новости:
	<span class="uf-btn" data-uf-open="/engine/ajax/uniform/uniform.php" data-uf-settings='{"formConfig": "newsauthor", "fields": {"newsId": "{news-id}"}}'>Связь с автором новости</span> 

	Для вставки в другие шаблоны {news-id} нужно заменить на ID нужной новости.
*}
<div class="uf-wrapper">
	<span class="mfp-close">&times;</span>
	<div class="uf-header">
		Связь с автором новости
	</div>
	[debug]<div class="uf-content">{debug}</div>[/debug]
	[error]
		<div class="uf-alert uf-alert-error">
			<b>Ошибка</b>
			<ul>
				[uf_token_error]
					<li>Ошибка сессии, попробуйте ещё раз.</li>
				[/uf_token_error]
				[uf_error_textarea]
					<li>Вы не написали сообщение</li>
				[/uf_error_textarea]
				[uf_error_email]
					<li>Вы не указали email</li>
				[/uf_error_email]
				[uf_email_error]
					<li>Проверьте правильность ввода email</li>
				[/uf_email_error]
			</ul>
		</div>
	[/error]
	[success]
		<div class="uf-content"><b>Ваше сообщение успешно отправлено!</b></div>
	[/success]
	[form]
		<div class="uf-content">		
			<div class="uf-field">
				<div class="uf-label">
					Ваш email
				</div>
				<div class="uf-field-input">
					<input class="uf-input uf-input-first [uf_error_email]uf-input-error[/uf_error_email] [uf_email_error]uf-input-error[/uf_email_error]" type="text" name="email" value="{uf_field_email}">
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Сообщение для автора новости
				</div>
				<div class="uf-field-input">
					<textarea class="uf-input [uf_error_textarea]uf-input-error[/uf_error_textarea]" name="textarea" cols="30" rows="10">{uf_field_textarea}</textarea>
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					&nbsp;
				</div>
				<div class="uf-field-input">
					<button class="uf-btn ladda-button" type="submit" data-style="zoom-in"><span class="ladda-label">Отправить</span></button>
				</div>
			</div>
		</div>
	[/form]
</div>
