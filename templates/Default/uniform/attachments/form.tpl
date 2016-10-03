{* 
	Для вывода кнопки открытия формы используем код:
	<span data-uf-open="/engine/ajax/uniform/uniform.php" data-uf-settings='{"formConfig": "attachments"}' class="uf-btn">Отправить файлы</span> 
*}
<div class="uf-wrapper">
	<span class="mfp-close">&times;</span>
	<div class="uf-header">
		Отправить файлы
	</div>
	[debug]<div class="uf-content">{debug}</div>[/debug]
	[error]
		<div class="uf-alert uf-alert-error">
			<b>Ошибка</b>
			<ul>
				[uf_token_error]
					<li>Ошибка сессии, попробуйте ещё раз.</li>
				[/uf_token_error]
			</ul>
		</div>
	[/error]
	[attachments_error]
		<div class="uf-alert uf-alert-info">
			<b>Внимание</b> <br>
			Некоторые файлы ({notAttachedFiles}) не были отправлены, возможно они имеют слишком большой размер или некорректный формат. 
		</div>
	[/attachments_error]
	[success]
		<div class="uf-content"><b>Спасибо за файлы!</b> <br> Мы обязательно посмотрим Ваши картинки и удалим их в ближайшее время.</div>
	[/success]
	[form]
		<div class="uf-content">		
			<div class="uf-field">
				<div class="uf-label">
					Файлы
				</div>
				<div class="uf-field-input">
					<!-- {* Для  просмотра списка типов данных можно воспользоваться ссылкой: http://htmlbook.ru/html/value/mime *} -->
					<input class="uf-input" name="images[]" type="file" accept="image/jpeg,image/png,image/gif" multiple>
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
