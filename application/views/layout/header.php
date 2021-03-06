<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <title><?= $siteTitle ?></title>
    <!--link rel="stylesheet" href="<?= base_url('/_static/css/bootstrap.css?ver=1'); ?>"-->
    <link rel="stylesheet" href="<?= base_url('/_static/css/default_smart.css?ver=1'); ?>">
    <link rel="stylesheet" href="<?= base_url('/_static/css/form.css?ver=1'); ?>">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- <script src="<?= base_url('/_static/js/jquery-1.12.4.min.js'); ?>"></script>
    <script src="<?= base_url('/_static/js/common.js'); ?>"></script> -->

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script src="<?= base_url('/_static/js/datepicker-ko.js') ?>"></script>


</head>

<body>

    <div id="smart_container">

        <div class="menu_Content">
            <div class="scroll">
                <div class="mControl">
                    <span class="mhide"><i class="material-icons">close</i></span>
                </div>
                <div class="mControl_show">
                    <span class="mshow"><i class="material-icons">menu</i></span>
                </div>
                <div class="mcont_hd">
                    <a href="<?= base_url('') ?>">
                        <img src="<?= base_url("_static/img/logo.png"); ?>" width="140">
                    </a>
                    <div class="login_b">

                        <?php
                        if (!empty($this->session->userdata('user_id'))) {
                        ?>
                            <a href="<?= base_url('REG/logout') ?>" class="l_btn">๋ก๊ทธ์์</a>
                        <?php
                        } else {
                        ?>
                            <a href="<?= base_url('REG/login') ?>" class="l_btn">๋ก๊ทธ์ธ</a>
                        <?php
                        }
                        ?>
                    </div>
                </div>

                <div class="mcont_bd">

                    <ul id="menuContent">
                        <?php
                        if (!empty($_SESSION['user_level'])) {
                            foreach ($menuLevel as $i => $row) {
                                if ($row->MENU_CODE == "MDM" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                        ?>
                                    <li class="menu01_li">
                                        <a href="<?= base_url('MDM/code') ?>" class="menu_a <?= ($this->data['pos'] == "MDM") ? "on" : ""; ?>">
                                            <i class="material-icons">add_business</i>๊ธฐ์ค์?๋ณด</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "MDM") ? "style='display:block'" : ""; ?>>
                                            <!-- <li><a href="<?= base_url('MDM/calendar') ?>" class="<?= ($this->data['subpos'] == 'calendar') ? "on" : ""; ?>">Work Calendar</a></li> -->
                                            <li><a href="<?= base_url('MDM/code') ?>" class="<?= ($this->data['subpos'] == "code") ? "on" : ""; ?>">๊ณตํต์ฝ๋๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('MDM/items') ?>" class="<?= ($this->data['subpos'] == "items") ? "on" : ""; ?>">ํ๋ชฉ๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('MDM/biz') ?>" class="<?= ($this->data['subpos'] == "biz") ? "on" : ""; ?>">์์ฒด๋ฑ๋ก</a></li>
                                            <!-- <li><a href="<?= base_url('MDM/bizcur') ?>" class="<?= ($this->data['subpos'] == "bizcur") ? "on" : ""; ?>">์์ฒดํํฉ</a></li> -->
                                            <!-- <li><a href="<?= base_url('MDM/person') ?>" class="<?= ($this->data['subpos'] == "person") ? "on" : ""; ?>">์ธ์ฌ์?๋ณด๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('MDM/personcur') ?>" class="<?= ($this->data['subpos'] == "personcur") ? "on" : ""; ?>">์ธ์ฌ์?๋ณดํํฉ</a></li> -->
                                            <!-- <li><a href="<?= base_url('MDM/member') ?>" class="<?= ($this->data['subpos'] == "member") ? "on" : ""; ?>">์์์๋ฑ๋ก</a></li> -->
                                        </ul>
                                    </li>
                                <?php   }
                                if ($row->MENU_CODE == "PURCHASE" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                ?>
                                    <li class="menu01_li">
                                        <a href="<?= base_url('PURCHASE/matorder') ?>" class="menu_a <?= ($this->data['pos'] == "PURCHASE") ? "on" : ""; ?>">
                                            <i class="material-icons">add_business</i>๊ตฌ๋งค๊ด๋ฆฌ</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "PURCHASE") ? "style='display:block'" : ""; ?>>
                                            <li><a href="<?= base_url('PURCHASE/matorder') ?>" class="<?= ($this->data['subpos'] == 'matorder') ? "on" : ""; ?>">์์์ฌ ๋ฐ์ฃผ๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('PURCHASE/enter') ?>" class="<?= ($this->data['subpos'] == 'enter') ? "on" : ""; ?>">์๊ณ?๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('PURCHASE/orderenter') ?>" class="<?= ($this->data['subpos'] == 'orderenter') ? "on" : ""; ?>">๋ฐ์ฃผ๋๋น ์๊ณ?ํํฉ</a></li>
                                        </ul>
                                    </li>
                                <?php   }
                                if ($row->MENU_CODE == "ORDPLN" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('ORDPLN/order') ?>" class="menu_a <?= ($this->data['pos'] == "ORDPLN") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>์ฃผ๋ฌธ/๊ณํ</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "ORDPLN") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('ORDPLN/order') ?>" class="<?= ($this->data['subpos'] == 'order') ? "on" : ""; ?>">์ฃผ๋ฌธ๋ฑ๋ก</a></li>
                                                <li><a href="<?= base_url('ORDPLN/ordercur') ?>" class="<?= ($this->data['subpos'] == 'ordercur') ? "on" : ""; ?>">์ฃผ๋ฌธํํฉ</a></li>
                                                <li><a href="<?= base_url('ORDPLN/orderprocess') ?>" class="<?= ($this->data['subpos'] == 'orderprocess') ? "on" : ""; ?>">์ฃผ๋ฌธ๋๋น ์งํํํฉ</a></li>
                                                <li><a href="<?= base_url('ORDPLN/prodpln') ?>" class="<?= ($this->data['subpos'] == 'prodpln') ? "on" : ""; ?>">์์ฐ๊ณํ ๋ฑ๋ก</a></li>
                                                <li><a href="<?= base_url('ORDPLN/vacation') ?>" class="<?= ($this->data['subpos'] == 'vacation') ? "on" : ""; ?>">๊ทผํ๊ด๋ฆฌ</a></li>
                                                <!-- <li><a href="<?= base_url('ORDPLN/prodplncur') ?>" class="<?= ($this->data['subpos'] == 'prodplncur') ? "on" : ""; ?>">์์ฐ๊ณํ ์กฐํ</a></li> -->
                                               <li><a href="<?= base_url('ORDPLN/month') ?>" class="<?= ($this->data['subpos'] == 'month') ? "on" : ""; ?>">์ผ๋ณ ๊ทผํ ๋ฑ๋ก ๊ด๋ฆฌ</a></li> 
                                                <li><a href="<?= base_url('ORDPLN/monthpln') ?>" class="<?= ($this->data['subpos'] == 'monthpln') ? "on" : ""; ?>">์ผ๋ณ ๊ทผํ ์กฐํ</a></li> 
                                            </ul>
                                        </li>
                                    <?php   }
                                if ($row->MENU_CODE == "PROD" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                ?>
                                    <li class="menu01_li">
                                        <a href="<?= base_url('PROD/workorder') ?>" class="menu_a <?= ($this->data['pos'] == "PROD") ? "on" : ""; ?>">
                                            <i class="material-icons">add_business</i>์์ฐ๊ด๋ฆฌ</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "PROD") ? "style='display:block'" : ""; ?>>
                                            <li><a href="<?= base_url('PROD/workorder') ?>" class="<?= ($this->data['subpos'] == 'workorder') ? "on" : ""; ?>">์์์ง์๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('PROD/batch') ?>" class="<?= ($this->data['subpos'] == 'batch') ? "on" : ""; ?>">๋ฐฐ์น์์</a></li>
                                            <li><a href="<?= base_url('PROD/pworkorder') ?>" class="<?= ($this->data['subpos'] == 'pworkorder') ? "on" : ""; ?>">๊ณต์?๋ณ ์์์ง์</a></li>
                                            <li><a href="<?= base_url('PROD/matinput') ?>" class="<?= ($this->data['subpos'] == 'matinput') ? "on" : ""; ?>">์์ฌ๋ฃ ํฌ์ ์๋?ฅ</a></li>
                                            <li><a href="<?= base_url('PROD/pprodcur') ?>" class="<?= ($this->data['subpos'] == 'pprodcur') ? "on" : ""; ?>">๊ณต์?๋ณ ์์ฐ๋ด์ญ</a></li>
                                            <li><a href="<?= base_url('PROD/pharvest') ?>" class="<?= ($this->data['subpos'] == 'pharvest') ? "on" : ""; ?>">๊ณต์?๋ณ ์์จ</a></li>
                                            <!-- <li><a href="<?= base_url('PROD/dprodperf') ?>" class="<?= ($this->data['subpos'] == 'dprodperf') ? "on" : ""; ?>">๊ธฐ๊ฐ๋ณ ์์ฐ์ค์?</a></li> -->
                                            <li><a href="<?= base_url('PROD/prodmonitor') ?>" class="<?= ($this->data['subpos'] == 'prodmonitor') ? "on" : ""; ?>">์์ฐ ๋ชจ๋ํฐ๋ง</a></li>
                                            <li><a href="<?= base_url('PROD/prodmonitor2') ?>" class="<?= ($this->data['subpos'] == 'prodmonitor2') ? "on" : ""; ?>">์์ฐ ๋ชจ๋ํฐ๋ง - ํ๋ธ๋ฆฟ</a></li>
                                        </ul>
                                    </li>
                                <?php   }
                                if ($row->MENU_CODE == "_INTERFACE" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('_INTERFACE/interface/0') ?>" class="menu_a <?= ($this->data['pos'] == "_INTERFACE") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>์์ฐ๊ด๋ฆฌINTERFACE</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "_INTERFACE") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('_INTERFACE/interface/0') ?>" class="<?= ($this->data['ssubpos'] == '0') ? "on" : ""; ?>">์จ์ํฑํฌ</a></li>
                                                <li><a href="<?= base_url('_INTERFACE/interface/1') ?>" class="<?= ($this->data['ssubpos'] == '1') ? "on" : ""; ?>">ํ์ฐ๋ํธ๋ฅจํฑํฌ</a></li>
                                                <li><a href="<?= base_url('_INTERFACE/interface/2') ?>" class="<?= ($this->data['ssubpos'] == '2') ? "on" : ""; ?>">์ธ์ฒํฑํฌ</a></li>
                                                <li><a href="<?= base_url('_INTERFACE/interface/3') ?>" class="<?= ($this->data['ssubpos'] == '3') ? "on" : ""; ?>">๋ฐ์ํฑํฌ</a></li>
                                                <li><a href="<?= base_url('_INTERFACE/interface/4') ?>" class="<?= ($this->data['ssubpos'] == '4') ? "on" : ""; ?>">๊ต๋ฐํฑํฌ1</a></li>
                                                <li><a href="<?= base_url('_INTERFACE/interface/5') ?>" class="<?= ($this->data['ssubpos'] == '5') ? "on" : ""; ?>">๊ต๋ฐํฑํฌ2</a></li>
                                                <li><a href="<?= base_url('_INTERFACE/interface/6') ?>" class="<?= ($this->data['ssubpos'] == '6') ? "on" : ""; ?>">๊ต๋ฐํฑํฌ3</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                if ($row->MENU_CODE == "STOCK" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('STOCK/stockcur') ?>" class="menu_a <?= ($this->data['pos'] == "STOCK") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>์ฌ๊ณ?/์๋ถ ๊ด๋ฆฌ</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "STOCK") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('STOCK/stockcur') ?>" class="<?= ($this->data['subpos'] == 'stockcur') ? "on" : ""; ?>">์ฌ๊ณ?๋ด์ญ</a></li>
                                                <li><a href="<?= base_url('STOCK/stockchange') ?>" class="<?= ($this->data['subpos'] == 'stockchange') ? "on" : ""; ?>">์ฌ๊ณ?์กฐ์?</a></li>
                                                <li><a href="<?= base_url('STOCK/package') ?>" class="<?= ($this->data['subpos'] == 'package') ? "on" : ""; ?>">ํฌ์ฅ๋ฑ๋ก</a></li>
                                                <li><a href="<?= base_url('STOCK/release') ?>" class="<?= ($this->data['subpos'] == 'release') ? "on" : ""; ?>">์ถ๊ณ?๋ฑ๋ก</a></li>
                                                <li><a href="<?= base_url('STOCK/dbrelease') ?>" class="<?= ($this->data['subpos'] == 'dbrelease') ? "on" : ""; ?>">๊ธฐ๊ฐ๋ณ/์์ฒด๋ณ ์ถ๊ณ?๋ด์ญ</a></li>
                                                <li><a href="<?= base_url('STOCK/claim') ?>" class="<?= ($this->data['subpos'] == 'claim') ? "on" : ""; ?>">ํด๋์ ๋ฑ๋ก</a></li>
                                                <li><a href="<?= base_url('STOCK/claimcur') ?>" class="<?= ($this->data['subpos'] == 'claimcur') ? "on" : ""; ?>">ํด๋์ ๋ด์ญ ์กฐํ</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                    /*
                                    if ($row->MENU_CODE == "PRODMON" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                        ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('PRODMON/prodmonoff') ?>" class="menu_a <?= ($this->data['pos'] == "PRODMON") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>๋ชจ๋ํฐ๋ง</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "PRODMON") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('PRODMON/prodmonoff') ?>" class="<?= ($this->data['subpos'] == 'prodmonoff') ? "on" : ""; ?>">์์ฐํํฉ ๋ชจ๋ํฐ - ์ฌ๋ฌด๋</a></li>
                                                <li><a href="<?= base_url('PRODMON/prodmonfac') ?>" class="<?= ($this->data['subpos'] == 'prodmonfac') ? "on" : ""; ?>">์์ฐํํฉ ๋ชจ๋ํฐ - ๊ณต์ฅ๋</a></li>
                                                
                                            </ul>
                                        </li>
                                    <?php   }
                                    */
                                    /*
                                    if ($row->MENU_CODE == "SENSOR" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                        ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('SENSOR/senprod') ?>" class="menu_a <?= ($this->data['pos'] == "SENSOR") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>Sensor - ๋ชจ๋ํฐ๋ง</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "SENSOR") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('SENSOR/senprod') ?>" class="<?= ($this->data['subpos'] == 'senprod') ? "on" : ""; ?>">Sensor ๋ชจ๋ํฐ๋ง(์์ฐํ์ฅ์ฉ)</a></li>
                                                <li><a href="<?= base_url('SENSOR/senadmin') ?>" class="<?= ($this->data['subpos'] == 'senadmin') ? "on" : ""; ?>">Sensor ๋ชจ๋ํฐ๋ง(๊ด๋ฆฌ์์ฉ)</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                    */
                                    
                                if ($row->MENU_CODE == "QUAL" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('QUAL/qexam') ?>" class="menu_a <?= ($this->data['pos'] == "QUAL") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>ํ์ง๊ด๋ฆฌ</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "QUAL") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('QUAL/qexam') ?>" class="<?= ($this->data['subpos'] == 'qexam') ? "on" : ""; ?>">ํ์ง๊ฒ์ฌ ๋ฑ๋ก</a></li>
                                                <li><a href="<?= base_url('QUAL/perfpoor') ?>" class="<?= ($this->data['subpos'] == 'perfpoor') ? "on" : ""; ?>">์ค์?๋๋น ๋ถ๋๋ฅ?</a></li>
                                                <li><a href="<?= base_url('QUAL/qualitycur') ?>" class="<?= ($this->data['subpos'] == 'qualitycur') ? "on" : ""; ?>">ํ์ง์ด๋?ฅ</a></li>
                                                <li><a href="<?= base_url('QUAL/pooranal') ?>" class="<?= ($this->data['subpos'] == 'pooranal') ? "on" : ""; ?>">๋ถ๋๋ถ์</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                    
                                if ($row->MENU_CODE == "SYS" && $_SESSION['user_level'] >= $row->MENU_LEVEL) { ?>
                                    <li class="menu01_li">
                                        <a href="<?= base_url('SYS/menu') ?>" class="menu_a <?= ($this->data['pos'] == "SYS") ? "on" : ""; ?>">
                                            <i class="material-icons">settings</i>
                                            ์์คํ๊ด๋ฆฌ</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "SYS") ? "style='display:block'" : ""; ?>>
                                            <li><a href="<?= base_url('SYS/menu') ?>" class="<?= ($this->data['subpos'] == 'menu') ? "on" : ""; ?>">๋ฉ๋ด๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('SYS/register') ?>" class="<?= ($this->data['subpos'] == 'register') ? "on" : ""; ?>">์ฌ์ฉ์ ๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('SYS/level') ?>" class="<?= ($this->data['subpos'] == 'level') ? "on" : ""; ?>">์ฌ์ฉ์ ๊ถํ๋ฑ๋ก</a></li>
                                            <li><a href="<?= base_url('SYS/userlog') ?>" class="<?= ($this->data['subpos'] == 'userlog') ? "on" : ""; ?>">์?์๊ธฐ๋ก</a></li>
                                        <!-- <li><a href="<?= base_url('SYS/version') ?>" class="<?= ($this->data['subpos'] == 'version') ? "on" : ""; ?>">๋ฒ์?๊ด๋ฆฌ</a></li> -->

                                        </ul>
                                    </li>
                            <?php   }
                            }
                        } else {
                            ?>
                            <li class="menu01_li">
                                <a href="<?= base_url('SYS/login') ?>" class="menu_a <?= ($this->data['pos'] == "SYS") ? "on" : ""; ?>">
                                    <i class="material-icons">assignment_ind</i>๋ก๊ทธ์ธ</a>
                            </li>
                        <?php } ?>
                    </ul>


                </div>
            </div>
        </div>
        <input type="hidden" class="savehidden">
        <div class="body_">
            <div class="body_Content">