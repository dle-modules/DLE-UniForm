{* 
	Для вывода кнопки открытия формы используем код:
	<span data-uf-open="/engine/ajax/uniform/uniform.php" data-uf-settings='{"formConfig": "test", "fields":{"morefield1": "скрытый текст", "morefield2": "Ещё скрытый текст", "notsend": "Это поле не учитывается т.к. не прописано в конфиге"}}' class="uf-btn">Тестовая форма</span> 
*}
<div class="uf-wrapper">
	<span class="mfp-close">&times;</span>
	<div class="uf-header">
		Заказать звонок
	</div>
	[debug]
		{* 
			Тут отображается служебная информация, если в конфиге указано debug = y
			По умолчанию отображается текущий конфиг.
			При отправле формы добавляется показ данных из переменной $_POST.
			При удачной отправке формы на email показываются поля, которые ушли в шаблон email-сообщения
		 *}
		<div class="uf-content">{debug}</div>
	[/debug]
	[error]
		{* Содержимое отображается, если орма содержит ошибки *}
		<div class="uf-alert uf-alert-error">
			<b>Ошибка</b>
			<ul>
				[uf_token_error]
					{* Это обработка CSRF токена, он генерируется автоматически и служит для защиты от межсайтовой подделки запроса *}
					<li>Ошибка сессии, попробуйте ещё раз.</li>
				[/uf_token_error]

				[uf_error_field1]
					<li>Не заполнено поле 1</li>
				[/uf_error_field1]

				[uf_error_field2]
					<li>Не заполнено поле 2</li>
				[/uf_error_field2]

				[uf_error_email]
					{* Это обработка поля с именем email, не нужно путать с uf_email_error *}
					<li>Вы не указали email</li>
				[/uf_error_email]

				{* uf_email_error — это обработка ввода email-адреса, работает только в том случаи, если это поле указано как обязательное *}
				[uf_email_error]
					<li>Проверьте правильность ввода email</li>
				[/uf_email_error]
			</ul>
		</div>
	[/error]
	[success]
		{* Этот текст будет показан, когда форма удачно отправлена *}
		<div class="uf-content"><b>Форма отправлена!</b></div>
	[/success]
	[form]
		<div class="uf-content">		
			<div class="uf-field">
				<div class="uf-label">
					поле 1
				</div>
				<div class="uf-field-input">
					{* Условие вывода, при котором текст покажется, если значение поля равно 123 *}
					[uf_field_field1="123"]Передано значение: 123[/uf_field_field1]
					<input class="uf-input uf-input-first [uf_error_field1]uf-input-error[/uf_error_field1]" type="text" name="field1" required value="{uf_field_field1}" placeholder="Попробуйте вбить 123">
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					поле 2
				</div>
				<div class="uf-field-input">
					<textarea class="uf-input [uf_error_field2]uf-input-error[/uf_error_field2]" type="text" name="field2">{uf_field_field2}</textarea>
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					email
				</div>
				<div class="uf-field-input">
					<input class="uf-input [uf_error_email]uf-input-error[/uf_error_email]" type="text" name="email" required value="{uf_field_email}">
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Обычный селект
				</div>
				<div class="uf-field-input">
					{* Условия вывода в шаблон информации в зависисмости от значения селекта *}
					[uf_select_select1="val2"]Выбран селект "Значение 2" <br>[/uf_select_select1]
					[uf_select_select1="val3"]Выбран селект "Значение 3" <br>[/uf_select_select1]
					<select name="select1" class="uf-input">
						<option value="val1" [uf_select_select1_val1]selected[/uf_select_select1_val1]>Значение 1</option>
						<option value="val2" [uf_select_select1_val2]selected[/uf_select_select1_val2]>Значение 2</option>
						<option value="val3" [uf_select_select1_val3]selected[/uf_select_select1_val3]>Значение 3</option>
						<option value="val4" [uf_select_select1_val4]selected[/uf_select_select1_val4]>Значение 4</option>
					</select>
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Множественный селект
				</div>
				<div class="uf-field-input">
					{* Условия вывода в шаблон информации в зависисмости от значения селекта *}
					[uf_select_select2="val1"]Выбран селект "Значение 1" <br>[/uf_select_select2]
					[uf_select_select2="val2"]Выбран селект "Значение 2" <br>[/uf_select_select2]
					<select name="select2[]" multiple class="uf-input">
						<option value="val1" [uf_select_select2_val1]selected[/uf_select_select2_val1]>Значение 1</option>
						<option value="val2" [uf_select_select2_val2]selected[/uf_select_select2_val2] [uf_default_value]selected[/uf_default_value]>Значение 2</option>
						<option value="val3" [uf_select_select2_val3]selected[/uf_select_select2_val3]>Значение 3</option>
						<option value="val4" [uf_select_select2_val4]selected[/uf_select_select2_val4]>Значение 4</option>
					</select>
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Чекбокс
				</div>
				<div class="uf-field-input">
					{* Условия вывода в шаблон информации в зависисмости от значения чекбокса *}
					[uf_checkbox_checkbox1="oneCheck"]Чекбокс отмечен <br>[/uf_checkbox_checkbox1]
					<label><input type="checkbox" name="checkbox1" value="oneCheck" [uf_checkbox_checkbox1_oneCheck]checked[/uf_checkbox_checkbox1_oneCheck]> Простой чекбокс</label>
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Чекбоксы
				</div>
				<div class="uf-field-input">
					{* Условия вывода в шаблон информации в зависисмости от значения чекбокса *}
					[uf_checkbox_checkbox2="one"]Отмечен чекбокс 1 <br>[/uf_checkbox_checkbox2]
					[uf_checkbox_checkbox2="two"]Отмечен чекбокс 2 <br>[/uf_checkbox_checkbox2]
					<label><input type="checkbox" name="checkbox2[]" value="one" [uf_checkbox_checkbox2_one]checked[/uf_checkbox_checkbox2_one]> Чекбокс 1</label> <br>
					<label><input type="checkbox" name="checkbox2[]" value="two" [uf_checkbox_checkbox2_two]checked[/uf_checkbox_checkbox2_two] [uf_default_value]checked[/uf_default_value]> Чекбокс 2</label> <br>
					<label><input type="checkbox" name="checkbox2[]" value="tree" [uf_checkbox_checkbox2_tree]checked[/uf_checkbox_checkbox2_tree]> Чекбокс 3</label>
				</div>
			</div>
			<div class="uf-field">
				<div class="uf-label">
					Радиокнопки
				</div>
				<div class="uf-field-input">
					{* Условия вывода в шаблон информации в зависисмости от значения радиокнопки *}
					[uf_radio_radio1="one"]Выбрана Радиокнопка 1 <br>[/uf_radio_radio1]
					[uf_radio_radio1="two"]Выбрана Радиокнопка 2 <br>[/uf_radio_radio1]
					<label><input type="radio" name="radio1[]" value="one" [uf_radio_radio1_one]checked[/uf_radio_radio1_one]> Радиокнопка 1</label> <br>
					<label><input type="radio" name="radio1[]" value="two" [uf_radio_radio1_two]checked[/uf_radio_radio1_two] [uf_default_value]checked[/uf_default_value]> Радиокнопка 2</label> <br>
					<label><input type="radio" name="radio1[]" value="tree" [uf_radio_radio1_tree]checked[/uf_radio_radio1_tree]> Радиокнопка 3</label>
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
