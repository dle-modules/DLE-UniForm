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
    'formConfig'     => !empty($formConfig) ? $formConfig : (isset($_REQUEST['formConfig'])) ? $_REQUEST['formConfig'] : 'feedback',
    'role'           => !empty($role) ? $role : (isset($arConf['role'])) ? $arConf['role'] : 'feedback',
    'templateFolder' => !empty($templateFolder) ? $templateFolder : (isset($arConf['templateFolder'])) ? $arConf['templateFolder'] : 'feedback',
    'nocache'        => !empty($nocache) ? $nocache : (isset($arConf['nocache'])) ? $arConf['nocache'] : false,
    'debug'          => !empty($debug) ? $debug : (isset($arConf['debug'])) ? $arConf['debug'] : false,
    'required'       => !empty($required) ? $required : (isset($arConf['required'])) ? $arConf['required'] : false,
    'hidden'         => !empty($hidden) ? $hidden : (isset($arConf['hidden'])) ? $arConf['hidden'] : false,
    'sendmail'       => !empty($sendmail) ? $sendmail : (isset($arConf['sendmail'])) ? $arConf['sendmail'] : false,
    'emails'         => !empty($emails) ? $emails : (isset($arConf['emails'])) ? $arConf['emails'] : false,
    'selectFields'   => !empty($selectFields) ? $selectFields : (isset($arConf['selectFields'])) ? $arConf['selectFields'] : false,
    'checkboxFields' => !empty($checkboxFields) ? $checkboxFields : (isset($arConf['checkboxFields'])) ? $arConf['checkboxFields'] : false,
    'radioFields'    => !empty($radioFields) ? $radioFields : (isset($arConf['radioFields'])) ? $arConf['radioFields'] : false,
    'sendAsPlain'    => !empty($sendAsPlain) ? true : (isset($arConf['sendAsPlain'])) ? true : false,
    'version'        => '1.2',
    'releaseDate'    => '09.01.2016',
);
