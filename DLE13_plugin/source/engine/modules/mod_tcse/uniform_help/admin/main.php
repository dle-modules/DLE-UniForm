<?php
if (!defined('DATALIFEENGINE') || !defined('LOGGED_IN')) {
    die('Hacking attempt!');
}
?>

    <div class="panel">

        <div class="panel-header">
            <ul class="nav nav-tabs nav-tabs-left">
                <li class="active"><a href="#tabmain" data-toggle="tab">Описание работы модуля</a></li>
                <li><a href="#tabhelp" data-toggle="tab">Справочная информация</a></li>
                <li><a href="#tabhistory" data-toggle="tab">История версий DLE-Uniform</a></li>
            </ul>
        </div>

        <div class="panel-content">
            <div class="tab-content">

                <div class="tab-pane active" id="tabmain">
                    <?php include MODULE_DIR . '/admin/text_1.php'; ?>
                </div>

                <div class="tab-pane" id="tabhelp">
                    <?php include MODULE_DIR . '/admin/text_2.php'; ?>
                </div>

                <div class="tab-pane" id="tabhistory">
                    <?php include MODULE_DIR . '/admin/text_3.php'; ?>
                </div>

            </div>
        </div>

    </div>

