{* 
	Шаблон для демонстрации возможности написать свой заголовок.
	Для уcтановки нестандартного заголовка достаточно прописать инпут с name="header".

	Для вывода кнопки открытия формы используем код:
	<span class="uf-btn" data-uf-open="/engine/ajax/uniform/uniform.php" data-uf-settings='{"formConfig": "customheader"}'>Написать на почту</span> 
*}
<div class="uf-wrapper">
	<span class="mfp-close">&times;</span>
	<div class="uf-header">
		Написать на почту
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
		<div class="uf-content"><b>Спасибо за письмо!</b> <br> Если оно потребует ответа — мы свяжемся с вами</div>
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
					Тема сообщения
				</div>
				<div class="uf-field-input">
					<input class="uf-input [uf_error_header]uf-input-error[/uf_error_header]" type="text" name="header" value="{uf_field_header}">
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Сообщение
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
