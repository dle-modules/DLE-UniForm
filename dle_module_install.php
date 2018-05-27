<?php

if (!defined('DATALIFEENGINE')) {
	define('DATALIFEENGINE', true);
}

include('dle_starter_installer/install.class.php');

/**
 * Название модуля, кторый необходимо установить
 */
$moduleName = (isset($_REQUEST['module'])) ? trim($_REQUEST['module']) : 'dle_starter';


$installer = new dleStarterInstaller($moduleName);

$stepsHeadings = [
	'paste'    => 'Вставить код',
	'find'     => 'Найти код',
	'addBefore' => 'Выше добавить',
	'addAfter' => 'Ниже добавить',
	'replace'  => 'Заменить на',
];

$contacts = '';
$output   = '';

try {
	$installer->checkBeforeInstall();
	$checkInstall = true;

	$licence = $installer->getTextFile('licence');

	$contacts = $installer->getTextFile('contacts');

	$queries = $installer->getTextFile('queries');

	$licenceAccept = ($licence !== '') ? $_POST['accept'] : true;

	if ($licence !== '') {
		if (isset($_POST['notaccept'])) {
			$output = <<<HTML
		<div class="content">
			<div class="col col-mb-12">
				<div class="alert">
					Вы отказались от установки модуля. <br>Не забудьте удалить загруженные файлы.
				</div>
			</div>
		</div>
HTML;
		} elseif (empty($licenceAccept)) {
			$output = <<<HTML
		<form method="post">
			<div class="content">
				<div class="col col-mb-12">
					<h2 class="mt0 ta-center">
						Лицензионное соглашение
					</h2>
					<div class="licence-text">
						{$licence}
					</div>
				</div>
				<div class="col col-mb-12 col-6 col-dt-5 col-dt-left-1 col-margin-top">
					<button type="submit" name="notaccept" value="y" class="btn btn-block btn-red">Не принимаю</button>
				</div>
				<div class="col col-mb-12 col-6 col-dt-5 col-margin-top">
					<button type="submit" name="accept" value="y" class="btn btn-block">Приимаю, продолжить установку</button>
				</div>
			</div>
		</form>
HTML;
		}
	}

	if (isset($licenceAccept)) {
		$steps = $installer->getSteps();
		if (count($steps)) {
			$output .= '<div class="steps"><ol>';

			foreach ($steps as $key => $step) {
				$stepElement = [];

				foreach ($step as $i => $stepItem) {
					$stepItem = $installer->replaceTags($stepItem);
					switch ($i) {
						case 'header':
							$stepElement[] = '<div class="step-element step-header">' . $stepItem . '</div>';
							break;

						case 'text':
							$stepElement[] = '<div class="step-element step-text">' . $stepItem . '</div>';
							break;

						case 'paste':
						case 'find':
						case 'addBefore':
						case 'addAfter':
						case 'replace':
							$stepElement[] = <<<HTML
						<div class="step-element step-code step-{$i}">
							<div class="content">
								<div class="col col-mb-12">
									<div class="step-subheading">
										{$stepsHeadings[$i]}
									</div>
								</div>
								<div class="col col-mb-10">
									<textarea id="clpbrd-{$key}-{$i}" readonly class="code" rows="1">{$stepItem}</textarea>

								</div>
								<div class="col col-mb-2">
									<span class="btn btn-block btn-border btn-clipboard"
										  title="Копировать код"
										  data-clipboard-target="#clpbrd-{$key}-{$i}"
									>
										<svg class="icon icon-copy"><use xlink:href="#icon-copy"></use></svg>
										<svg class="icon icon-like"><use xlink:href="#icon-like"></use></svg>
									</span>
								</div>
							</div>
						</div>
HTML;
							break;
					}
				}

				$stepElementString = '<li class="col-margin-bottom" data-step-text="' . ($key + 1) . '">' . implode($stepElement) . '</li>';

				$output .= $stepElementString;
			}

			$output .= '</ol></div>';

		}

		$queriesTxt = $queriesBtn = '';

		if (is_array($queries) && count($queries)) {
			$arQueries  = [];
			$queriesBtn = '<span id="wtq" class="btn btn-normal btn-border btn-gray">Какие запросы будут выполнены?</span>';
			/** @var array $queries */
			foreach ($queries as $key => $queryItem) {
				$arQueries[] = <<<HTML
					<div class="col col-mb-10 col-margin-bottom">
						<textarea id="mysql-clpbrd-{$key}" readonly class="code" rows="1">{$queryItem}</textarea>
					</div>
					<div class="col col-mb-2 col-margin-bottom ">
						<span class="btn btn-block btn-border btn-clipboard"
							  title="Копировать запрос"
							  data-clipboard-target="#mysql-clpbrd-{$key}"
						>
							<svg class="icon icon-copy"><use xlink:href="#icon-copy"></use></svg>
							<svg class="icon icon-like"><use xlink:href="#icon-like"></use></svg>
						</span>
					</div>
HTML;
				$queriesTxt  = implode('', $arQueries);
			}
		}

		$output .= <<<HTML
			<div class="form-field clearfix">
				<form method="POST">
					<input type="hidden" name="install" value="y">
					<input type="hidden" name="accept" value="y">
					<button class="btn btn-blue" type="submit">Установить модуль</button>
					{$queriesBtn}
				</form>
			</div>
			<div class="queries hide">
				<div class="content col-margin-top">
					{$queriesTxt}
				</div>
			</div>
HTML;
	}

	if (isset($licenceAccept) && isset($_POST['install'])) {
		$installedMessages = [];

		if (is_array($queries) && count($queries)) {
			/** @var array $queries */
			foreach ($queries as $queryItem) {
				$installer->db->query($queryItem);
			}
			$installer->db->free();

			$installedMessages[] = '<li>Запросы успешно выполнены.</li>';
		}

		if ($installer->cfg['installAdmin']) {
			$name  = $installer->cfg['moduleName'];
			$title = $installer->cfg['moduleTitle'];
			$descr = $installer->cfg['moduleDescr'];
			$icon  = $name . '.png';
			$perm  = $installer->cfg['allowGroups'];
			$installer->installAdmin($name, $title, $descr, $icon, $perm);

			$installedMessages[] = '<li>Админка модуля установлена.</li>';
		}

		$installedMessagesText = implode('', $installedMessages);

		$output = <<<HTML
		<h1>Установка завершена</h1>
		<div class="alert">
			<ul>
				{$installedMessagesText}
			</ul>

			Не забудьте удалить с сервера файл <b>dle_module_install.php</b> и папку <b>dle_starter_installer</b>
 		</div>

HTML;
	}


} catch (Exception $e) {
	$checkInstall = false;
	$output       = <<<HTML
	<div class="content col-padding-top">
		<div class="col col-mb-12">
			<div class="alert">
				{$e->getMessage()}
			</div>
		</div>
	</div>
HTML;
}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php echo $installer->dle_config['charset'] ?>">
	<title><?php echo $installer->cfg['moduleTitle'] ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet"
	      href="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/css/normalize.css">
	<link rel="stylesheet"
	      href="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/css/legrid.min.css">
	<link rel="stylesheet"
	      href="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/css/dle_starter.css?v=<?php echo $installer->cfg['moduleTitle'] . '-' . $installer->cfg['moduleVersion'] ?>">
</head>

<body>
	<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1"
	     xmlns="http://www.w3.org/2000/svg"
	     xmlns:xlink="http://www.w3.org/1999/xlink">
		<defs>
			<symbol id="icon-like" viewBox="0 0 32 32">
				<title>like</title>
				<path class="path1"
				      d="M30.62 21.755c0.656-0.836 0.97-1.733 0.93-2.657-0.040-1.017-0.495-1.813-0.87-2.302 0.435-1.084 0.602-2.79-0.85-4.115-1.064-0.97-2.871-1.405-5.373-1.285-1.76 0.080-3.232 0.408-3.292 0.422h-0.007c-0.335 0.060-0.689 0.134-1.051 0.214-0.027-0.428 0.047-1.492 0.836-3.888 0.937-2.851 0.883-5.032-0.174-6.491-1.111-1.532-2.884-1.653-3.406-1.653-0.502 0-0.964 0.207-1.291 0.589-0.743 0.863-0.656 2.456-0.562 3.192-0.883 2.369-3.359 8.177-5.454 9.79-0.040 0.027-0.074 0.060-0.107 0.094-0.616 0.649-1.031 1.352-1.312 1.967-0.395-0.214-0.843-0.335-1.325-0.335h-4.082c-1.539 0-2.784 1.251-2.784 2.784v10.874c0 1.539 1.251 2.784 2.784 2.784h4.082c0.596 0 1.151-0.187 1.606-0.509l1.573 0.187c0.241 0.033 4.524 0.576 8.92 0.488 0.796 0.060 1.546 0.094 2.242 0.094 1.198 0 2.242-0.094 3.112-0.281 2.048-0.435 3.446-1.305 4.156-2.583 0.542-0.977 0.542-1.947 0.455-2.563 1.332-1.205 1.566-2.536 1.519-3.473-0.027-0.542-0.147-1.004-0.274-1.345zM3.23 29.932c-0.542 0-0.977-0.442-0.977-0.977v-10.881c0-0.542 0.442-0.977 0.977-0.977h4.082c0.542 0 0.977 0.442 0.977 0.977v10.874c0 0.542-0.442 0.977-0.977 0.977h-4.082v0.007zM28.907 20.972c-0.281 0.294-0.335 0.743-0.12 1.091 0 0.007 0.274 0.475 0.308 1.118 0.047 0.877-0.375 1.653-1.258 2.315-0.314 0.241-0.442 0.656-0.308 1.031 0 0.007 0.288 0.89-0.181 1.726-0.448 0.803-1.445 1.378-2.958 1.7-1.211 0.261-2.857 0.308-4.878 0.147h-0.094c-4.303 0.094-8.652-0.468-8.699-0.475h-0.007l-0.676-0.080c0.040-0.187 0.060-0.388 0.060-0.589v-10.881c0-0.288-0.047-0.569-0.127-0.83 0.12-0.448 0.455-1.445 1.245-2.295 3.005-2.382 5.942-10.419 6.069-10.767 0.054-0.141 0.067-0.294 0.040-0.448-0.114-0.749-0.074-1.666 0.087-1.941 0.355 0.007 1.312 0.107 1.887 0.903 0.683 0.944 0.656 2.63-0.080 4.865-1.124 3.406-1.218 5.199-0.328 5.989 0.442 0.395 1.031 0.415 1.459 0.261 0.408-0.094 0.796-0.174 1.164-0.234 0.027-0.007 0.060-0.013 0.087-0.020 2.054-0.448 5.735-0.723 7.013 0.442 1.084 0.99 0.314 2.302 0.228 2.442-0.248 0.375-0.174 0.863 0.161 1.164 0.007 0.007 0.709 0.669 0.743 1.559 0.027 0.596-0.254 1.205-0.836 1.807z"></path>
			</symbol>
			<symbol id="icon-copy" viewBox="0 0 32 32">
				<title>copy</title>
				<path class="path1"
				      d="M20.594 5.597h-14.876c-1.396 0-2.53 1.134-2.53 2.53v21.344c0 1.396 1.134 2.53 2.53 2.53h14.876c1.396 0 2.53-1.134 2.53-2.53v-21.344c-0.007-1.396-1.14-2.53-2.53-2.53zM21.348 29.464c0 0.419-0.341 0.76-0.76 0.76h-14.876c-0.419 0-0.76-0.341-0.76-0.76v-21.338c0-0.419 0.341-0.76 0.76-0.76h14.876c0.419 0 0.76 0.341 0.76 0.76v21.338z"></path>
				<path class="path2"
				      d="M26.282 0h-14.876c-1.396 0-2.53 1.134-2.53 2.53 0 0.491 0.393 0.885 0.885 0.885s0.885-0.393 0.885-0.885c0-0.419 0.341-0.76 0.76-0.76h14.876c0.419 0 0.76 0.341 0.76 0.76v21.344c0 0.419-0.341 0.76-0.76 0.76-0.491 0-0.885 0.393-0.885 0.885s0.393 0.885 0.885 0.885c1.396 0 2.53-1.134 2.53-2.53v-21.344c0-1.396-1.134-2.53-2.53-2.53z"></path>
			</symbol>
			<symbol id="icon-man-sprinting" viewBox="0 0 32 32">
				<title>man-sprinting</title>
				<path class="path1"
				      d="M1.319 22.765c-0.881 0.169-1.458 1.019-1.289 1.9 0.148 0.776 0.83 1.316 1.593 1.316 0.101 0 0.206-0.010 0.307-0.030l6.683-1.283c0.381-0.074 0.722-0.28 0.965-0.584l2.869-3.618-0.959-0.489c-0.709-0.358-1.202-1.006-1.37-1.792l-2.707 3.412-6.092 1.168z"></path>
				<path class="path2"
				      d="M28.677 5.1c0 1.965-1.593 3.557-3.557 3.557s-3.557-1.593-3.557-3.557c0-1.965 1.593-3.557 3.557-3.557s3.557 1.593 3.557 3.557z"></path>
				<path class="path3"
				      d="M17.357 4.209c-0.375-0.385-0.722-0.537-1.104-0.537-0.138 0-0.277 0.020-0.425 0.054l-6.106 1.448c-0.844 0.199-1.364 1.046-1.164 1.887 0.172 0.722 0.813 1.208 1.526 1.208 0.122 0 0.243-0.014 0.365-0.044l5.201-1.232c0.338 0.375 2.028 2.207 2.336 2.531-2.15 2.302-4.3 4.6-6.45 6.902-0.034 0.037-0.064 0.074-0.095 0.111-0.628 0.8-0.435 2.055 0.51 2.531l6.592 3.365-3.422 5.545c-0.469 0.763-0.233 1.762 0.53 2.234 0.267 0.165 0.56 0.243 0.851 0.243 0.543 0 1.077-0.273 1.384-0.77l4.347-7.044c0.24-0.388 0.304-0.857 0.179-1.293-0.125-0.439-0.425-0.803-0.834-1.009l-4.465-2.271 4.695-5.022 3.544 3.004c0.294 0.25 0.655 0.371 1.013 0.371 0.385 0 0.77-0.142 1.070-0.419l4.067-3.79c0.635-0.591 0.672-1.583 0.081-2.217-0.311-0.331-0.729-0.5-1.148-0.5-0.381 0-0.766 0.138-1.067 0.419l-3.044 2.832c-0.003 0.003-7.631-7.172-8.964-8.539z"></path>
			</symbol>
		</defs>
	</svg>


	<div class="body-wrapper clearfix">
		<?php if ($checkInstall): ?>

			<header class="container top_nav-container container-blue">
				<div class="content">
					<div class="col col-mb-12 ta-center">
				<span class="logo" title="<?php echo $installer->cfg['moduleTitle'] ?>">
					<svg class="icon icon-man-sprinting">
						<use xlink:href="#icon-man-sprinting"></use>
					</svg>
					<?php echo $installer->cfg['moduleTitle'] ?>
				</span>
					</div>
				</div>
			</header>
			<div class="container pb0">
				<div class="content">
					<div class="col col-mb-12 ta-center">
						<h1><?php echo $installer->cfg['moduleTitle'] ?>
							v.<?php echo $installer->cfg['moduleVersion'] ?>
							от <?php echo $installer->cfg['moduleDate'] ?></h1>
						<div class="text-muted">Установка модуля</div>
						<hr>
					</div>
				</div>
			</div>
			<div class="container">
				<div class="content">
					<div class="col col-mb-12">
						<?php echo $output; ?>
					</div>
				</div>
			</div>
			<?php if ($contacts !== ''): ?>
				<div class="container pt0">
					<div class="content">
						<div class="col col-mb-12">
							<hr class="mt0">
							Контакты для связи и техподдержки:<br>
							<?php echo $contacts ?>
						</div>
					</div>
				</div>
			<?php endif ?>
		<?php else: ?>
			<?php echo $output; ?>
		<?php endif ?>

		<script
				src="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/js/jquery.min.js"></script>
		<script
				src="<?php echo $installer->dle_config['http_home_url'] ?>dle_starter_installer/assets/js/clipboard.min.js"></script>
		<script>
			$(document)
				.on('click', '.code', function () {
					$(this).select();
				})
				.on('click', '#wtq', function () {
					$('.queries').slideToggle(400);
					$(this).toggleClass('active');
				});

			var $clpbrdBtn = $('.btn-clipboard'),
			    clipboard  = new Clipboard('.btn-clipboard'),
			    timeoutId;
			clipboard.on('success', function (e) {
				clearTimeout(timeoutId);
				$clpbrdBtn.removeClass('success');

				$(e.trigger).addClass('success');
				timeoutId = setTimeout(function () {
					$clpbrdBtn.removeClass('success');
				}, 2000);
			});
		</script>
	</div><!-- .body-wrapper clearfix -->
</body>
</html>
