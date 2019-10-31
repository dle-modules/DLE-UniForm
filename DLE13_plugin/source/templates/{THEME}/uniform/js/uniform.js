/*!
 * DLE UniForm — унверсальные формы для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       http://pafnuty.name/
 * @link       https://twitter.com/pafnuty_name
 */

var doc = $(document);

doc
	// ajax-отправка формы + эффекты
	.on('submit', '[data-uf-form]', function () {
		var $this = $(this),
			laddaLoad,
			options = {
				beforeSubmit: ufStart,
				success: ufDone,
			};

		$this.ajaxSubmit(options);

		return false;
	})
	// Открытие ajax-окна с формой
	.on('click', '[data-uf-open]', function (e) {
		var $this = $(this),
			src = $this.data('ufOpen'),
			data = $this.data('ufSettings');

		$.magnificPopup.open({
			items: {
				src: src,
			},
			focus: '.uf-input-first',
			type: 'ajax',
			ajax: {
				settings: {
					data: data
				}
			}
		});
		return false;
	})
	// Убираем класс с из инпута с ошибочным заполнением
	.on('keyup input', '.uf-input-error', function (e) {
		var $this = $(this);
		if (e.type == 'input') {
			doc.off('keyup', '.uf-input-error');
		}
		if ($this.val().length) {
			$this.removeClass('uf-input-error');
		}
	});


// Функция, выполняемая перед отправкой формы
function ufStart(formData, jqForm) {
	laddaLoad = jqForm.find('.ladda-button').ladda();
	laddaLoad.ladda('start');

	return true;
}

// Функция, выполняемая после удачной отправки формы
function ufDone(responseText, statusText, xhr, $form) {

	var $responseText = $(responseText),
		responseResult = ($responseText.is('form')) ? $responseText.html() : responseText;

	if (statusText == 'success') {
		laddaLoad.ladda('stop');
		$form.html(responseResult);
	}
}

jQuery(document).ready(function ($) {
	var $inlineUniform = $('[data-uf-inline]');
	if ($inlineUniform.length) {
		$.each($inlineUniform, function (index, val) {
			var $this = $(this),
				url = $this.data('ufInline'),
				data = $this.data('ufSettings');

			$.ajax({
					url: url,
					data: data,
				})
				.done(function (data) {
					$this.html(data);
				});

		});
	};
});