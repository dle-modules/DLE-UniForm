<?php
/*
 * DLE UniForm — унверсальные формы для DLE
 *
 * @author     ПафНутиЙ <pafnuty10@gmail.com>
 * @link       http://pafnuty.name/
 * @link       https://twitter.com/pafnuty_name
 */
$cfg = [
    'formConfig'       => !empty($formConfig) ? $formConfig : (isset($_REQUEST['formConfig'])) ? $_REQUEST['formConfig'] : 'feedback',
    'role'             => !empty($role) ? $role : (isset($arConf['role'])) ? $arConf['role'] : 'feedback',
    'templateFolder'   => !empty($templateFolder) ? $templateFolder : (isset($arConf['templateFolder'])) ? $arConf['templateFolder'] : 'feedback',
    'nocache'          => !empty($nocache) ? $nocache : (isset($arConf['nocache'])) ? $arConf['nocache'] : false,
    'debug'            => !empty($debug) ? $debug : (isset($arConf['debug'])) ? $arConf['debug'] : false,
    'required'         => !empty($required) ? $required : (isset($arConf['required'])) ? $arConf['required'] : false,
    'hidden'           => !empty($hidden) ? $hidden : (isset($arConf['hidden'])) ? $arConf['hidden'] : false,
    'sendmail'         => !empty($sendmail) ? $sendmail : (isset($arConf['sendmail'])) ? $arConf['sendmail'] : false,
    'emails'           => !empty($emails) ? $emails : (isset($arConf['emails'])) ? $arConf['emails'] : false,
    'selectFields'     => !empty($selectFields) ? $selectFields : (isset($arConf['selectFields'])) ? $arConf['selectFields'] : false,
    'checkboxFields'   => !empty($checkboxFields) ? $checkboxFields : (isset($arConf['checkboxFields'])) ? $arConf['checkboxFields'] : false,
    'radioFields'      => !empty($radioFields) ? $radioFields : (isset($arConf['radioFields'])) ? $arConf['radioFields'] : false,
    'sendAsPlain'      => !empty($sendAsPlain) ? true : (isset($arConf['sendAsPlain'])) ? true : false,
    'sendToAuthor'     => !empty($sendToAuthor) ? true : (isset($arConf['sendToAuthor'])) ? true : false,
    'sendToSender'     => !empty($sendToSender) ? true : (isset($arConf['sendToSender'])) ? true : false,
    'allowAttachments' => !empty($allowAttachments) ? true : (isset($arConf['allowAttachments'])) ? true : false,
    'maxFileSize'      => !empty($maxFileSize) ? $maxFileSize : (isset($arConf['maxFileSize'])) ? $arConf['maxFileSize'] : false,
    'allowedFileTypes' => !empty($allowedFileTypes) ? $allowedFileTypes : (isset($arConf['allowedFileTypes'])) ? $arConf['allowedFileTypes'] : false,
    'version'          => '1.3.0',
    'releaseDate'      => '12.10.2016',
];
