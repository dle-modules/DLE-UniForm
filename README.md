# DLE-UniForm
![Release version](https://img.shields.io/github/v/release/dle-modules/DLE-UniForm?style=flat-square)
![DLE](https://img.shields.io/badge/DLE-13.x-green.svg?style=flat-square "DLE Version")
![License](https://img.shields.io/github/license/dle-modules/DLE-UniForm?style=flat-square)


## Установка
1. Устанавливаем как обычный плагин, файл **uniform_plugin.zip** содержит всё необходимое для автоматической установки.
2. Открыть файл `/templates/Default/main.tpl`
3. Добавить стили: 
```html
<!-- DLE UniForm -->
<link rel="stylesheet" href="/engine/classes/min/index.php?charset=utf-8&amp;f={THEME}/uniform/css/uniform.css&amp;200" />
<!-- /DLE UniForm -->
```
4. Добавить скрипты:
```html
<!-- DLE UniForm -->
<script src="/engine/classes/min/index.php?charset=utf-8&amp;f={THEME}/uniform/js/jquery.magnificpopup.min.js,{THEME}/uniform/js/jquery.ladda.min.js,{THEME}/uniform/js/jquery.form.min.js,{THEME}/uniform/js/uniform.js&amp;200"></script>
<!-- /DLE UniForm -->
```
5. В нужном месте вставить код для вывода формы в модальном окне
```html
<span class="uf-btn" 
	  data-uniform='{"formConfig": "feedback"}'
>
 Обратная связь
</span>
```
или для вывода инлайн-формы
```html
<div data-uniform-inline='{"formConfig": "inline"}'>
	<div class="uf-inline-loading"></div>
</div>
```

## Настройка
Атрибут `data-uniform` должен содержать корректный JSON-объект, содержащий ключ `formConfig`. 

В качестве значения принимается существующее имя папки, содержаще корректно настроенную конфигурацию модуля (смотрите любой из конфигов, идущих в комплекте с модулем). 

### Конфиги, идущие в комплекте с модулем
- attachments
- callback
- customheader
- feedback
- inline
- newsauthor
- test

## Информация о модуле
- [Список изменений](https://github.com/pafnuty/DLE-UniForm/blob/master/CHANGELOG.md)



