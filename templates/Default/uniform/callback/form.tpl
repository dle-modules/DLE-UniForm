{* 
	Для вывода кнопки открытия формы используем код:
	<span data-uf-open="/engine/ajax/uniform/uniform.php" data-uf-settings='{"formConfig": "callback"}' class="uf-btn">Заказать звонок</span> 
*}
<div class="uf-wrapper">
	<span class="mfp-close">&times;</span>
	<div class="uf-header">
		Заказать звонок
	</div>
	[debug]<div class="uf-content">{debug}</div>[/debug]
	[error]
		<div class="uf-alert uf-alert-error">
			<b>Ошибка</b>
			<ul>
				[uf_token_error]
					<li>Ошибка сессии, попробуйте ещё раз.</li>
				[/uf_token_error]
				[uf_error_phone]
					<li>Вы не указали номер телефона</li>
				[/uf_error_phone]
			</ul>
		</div>
	[/error]
	[success]
		<div class="uf-content"><b>Ваша заявка принята!</b> <br> Ждите звонка менеджера в удобное для вас время.</div>
	[/success]
	[form]
		<div class="uf-content">		
			<div class="uf-field">
				<div class="uf-label">
					Ваш телефон
				</div>
				<div class="uf-field-input">
					<input class="uf-input uf-input-first [uf_error_phone]uf-input-error[/uf_error_phone]" type="text" name="phone" value="{uf_field_phone}">
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Ваше имя
				</div>
				<div class="uf-field-input">
					<input class="uf-input [uf_error_name]uf-input-error[/uf_error_name]" type="text" name="name" value="{uf_field_name}">
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Когда звонить
				</div>
				<div class="uf-field-input">
					<select name="calltime" class="uf-input">
						<option value="anytime" [uf_select_calltime_anytime]selected[/uf_select_calltime_anytime]>в любое время</option>
						<option value="9-12" [uf_select_calltime_9-12]selected[/uf_select_calltime_9-12]>c 9:00 до 12:00</option>
						<option value="12-15" [uf_select_calltime_12-15]selected[/uf_select_calltime_12-15]>c 12:00 до 15:00</option>
						<option value="15-18" [uf_select_calltime_15-18]selected[/uf_select_calltime_15-18]>c 15:00 до 18:00</option>
					</select>
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
