<?php
/*
=============================================================================
UniForm - унверсальные формы для DLE
=============================================================================
Автор:   ПафНутиЙ
URL:     http://pafnuty.name/
twitter: https://twitter.com/pafnuty_name
google+: http://gplus.to/pafnuty
email:   pafnuty10@gmail.com
=============================================================================
 */
$cfg = array(
	'formConfig'   => !empty($formConfig) ? $formConfig : (isset($arConf['formConfig'])) ? $arConf['formConfig'] : 'feedback',
	'role'         => !empty($role) ? $role : (isset($arConf['role'])) ? $arConf['role'] : 'feedback',
	'template'     => !empty($template) ? $template : (isset($arConf['template'])) ? $arConf['template'] : 'uniform/feedback',
	'nocache'      => !empty($nocache) ? $nocache : (isset($arConf['nocache'])) ? $arConf['nocache'] : false,
	'debug'        => !empty($debug) ? $debug : (isset($arConf['debug'])) ? $arConf['debug'] : false,
	'required'     => !empty($required) ? $required : (isset($arConf['required'])) ? $arConf['required'] : false,
	'hidden'       => !empty($hidden) ? $hidden : (isset($arConf['hidden'])) ? $arConf['hidden'] : false,
	'sendmail'     => !empty($sendmail) ? $sendmail : (isset($arConf['sendmail'])) ? $arConf['sendmail'] : false,
	'mailTemplate' => !empty($mailTemplate) ? $mailTemplate : (isset($arConf['mailTemplate'])) ? $arConf['mailTemplate'] : 'feedback',
	'emails'       => !empty($emails) ? $emails : (isset($arConf['emails'])) ? $arConf['emails'] : false,
	'version'      => '0.1',
	'releaseDate'  => '25.04.2015',
);
