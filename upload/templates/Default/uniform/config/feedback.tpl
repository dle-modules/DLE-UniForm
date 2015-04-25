// -----------------
// Настройки UniForm
// -----------------

// -------------------------------------------
// Доступные параметры (значение по умолчанию)
// template     — Шаблон формы (uniform/feedback)
// nocache      — Отключение кеширования модуля (false)
// debug        — Дебаг (false)
// required     — Обязательные поля (false)
// hidden       — Разрешенные скрытые поля (false). Такие поля передаются из data-* атрибута кнопки открытия формы
// sendmail     — Отправлять email при заполнения формы? (false)
// mailTemplate — Шаблон email-письма (feedback)
// emails       — Адреса почты, на которые необходимо отправлять уведомление (false)
// -------------------------------------------


template = uniform/feedback
// nocache = y
// debug = y
required = textarea, email
// hidden = newsid,user
sendmail = y
// mailTemplate = feedback
// emails = pafnuty10@gmail.com