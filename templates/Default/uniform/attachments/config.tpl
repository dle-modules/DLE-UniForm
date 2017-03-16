// -----------------
// Настройки UniForm
// -----------------

// -------------------------------------------
// Доступные параметры (значение по умолчанию)
// templateFolder   — Папка с шаблонами формы формы, указывается подпапка, в папке uniform текущего шаблона сайта сайта (feedback), в которой должны лежать файлы config.tpl, form.tpl и email.tpl
// nocache          — Отключение кеширования модуля (false)
// debug            — Дебаг (false)
// required         — Обязательные поля (false)
// hidden           — Разрешенные скрытые поля (false). Такие поля передаются из data-* атрибута кнопки открытия формы
// sendmail         — Отправлять email при заполнения формы? (false)
// emails           — Адреса почты, на которые необходимо отправлять уведомление (false)
// selectFields     — Поля типа select
// checkboxFields   — Поля типа checkbox
// radioFields      — Поля типа radio
// sendAsPlain      — Отправлять сообщение как простой текст
// sendToAuthor     — Отправить письмо автору новости, если есть newsId есть поле с name="newsId" и если автор разрешил получение писем с сайта (false)
// sendToSender     — Отправлять письмо так же на email, указанный в поле email (false) 
// allowAttachments — Разрешить прикрепление файлов (false)
// maxFileSize      — Максимальный размер загружаемого файла (в килобайтах)
// allowedFileTypes — Разрешенные типы файлов, перечисляем расширения через запятую, без точек и пробелов
// parseSendMail    — Путь к php файлу, который будет подключен для обработки данных перед отправкой email
// -------------------------------------------


templateFolder = attachments
sendmail = y
emails = mail@mail.ru
allowAttachments = y
maxFileSize = 150
allowedFileTypes = png,jpg,jpeg,gif