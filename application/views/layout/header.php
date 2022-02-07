<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <title><?= $siteTitle ?></title>
    <!--link rel="stylesheet" href="<?= base_url('/_static/css/bootstrap.css?ver=20200725'); ?>"-->
    <link rel="stylesheet" href="<?= base_url('/_static/css/default_smart.css?ver=20200725'); ?>">
    <link rel="stylesheet" href="<?= base_url('/_static/css/form.css?ver=20200725'); ?>">
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
                            <a href="<?= base_url('REG/logout') ?>" class="l_btn">로그아웃</a>
                        <?php
                        } else {
                        ?>
                            <a href="<?= base_url('REG/login') ?>" class="l_btn">로그인</a>
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
                                            <i class="material-icons">add_business</i>기준정보</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "MDM") ? "style='display:block'" : ""; ?>>
                                            <!-- <li><a href="<?= base_url('MDM/calendar') ?>" class="<?= ($this->data['subpos'] == 'calendar') ? "on" : ""; ?>">Work Calendar</a></li> -->
                                            <li><a href="<?= base_url('MDM/code') ?>" class="<?= ($this->data['subpos'] == "code") ? "on" : ""; ?>">공통코드등록</a></li>
                                            <li><a href="<?= base_url('MDM/items') ?>" class="<?= ($this->data['subpos'] == "items") ? "on" : ""; ?>">품목등록</a></li>
                                            <li><a href="<?= base_url('MDM/biz') ?>" class="<?= ($this->data['subpos'] == "biz") ? "on" : ""; ?>">업체등록</a></li>
                                            <li><a href="<?= base_url('MDM/bizcur') ?>" class="<?= ($this->data['subpos'] == "bizcur") ? "on" : ""; ?>">업체현황</a></li>
                                            <!-- <li><a href="<?= base_url('MDM/person') ?>" class="<?= ($this->data['subpos'] == "person") ? "on" : ""; ?>">인사정보등록</a></li>
                                            <li><a href="<?= base_url('MDM/personcur') ?>" class="<?= ($this->data['subpos'] == "personcur") ? "on" : ""; ?>">인사정보현황</a></li> -->
                                            <!-- <li><a href="<?= base_url('MDM/member') ?>" class="<?= ($this->data['subpos'] == "member") ? "on" : ""; ?>">작업자등록</a></li> -->
                                        </ul>
                                    </li>
                                <?php   }
                                if ($row->MENU_CODE == "PURCHASE" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                ?>
                                    <li class="menu01_li">
                                        <a href="<?= base_url('PURCHASE/matorder') ?>" class="menu_a <?= ($this->data['pos'] == "PURCHASE") ? "on" : ""; ?>">
                                            <i class="material-icons">add_business</i>구매관리</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "PURCHASE") ? "style='display:block'" : ""; ?>>
                                            <li><a href="<?= base_url('PURCHASE/matorder') ?>" class="<?= ($this->data['subpos'] == 'matorder') ? "on" : ""; ?>">원자재 발주등록</a></li>
                                            <li><a href="<?= base_url('PURCHASE/enter') ?>" class="<?= ($this->data['subpos'] == 'enter') ? "on" : ""; ?>">입고등록</a></li>
                                            <li><a href="<?= base_url('PURCHASE/orderenter') ?>" class="<?= ($this->data['subpos'] == 'orderenter') ? "on" : ""; ?>">발주대비 입고현황</a></li>
                                            <li><a href="<?= base_url('PURCHASE/denter') ?>" class="<?= ($this->data['subpos'] == 'denter') ? "on" : ""; ?>">기간별 발주현황</a></li>
                                        </ul>
                                    </li>
                                <?php   }
                                if ($row->MENU_CODE == "ORDPLN" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('ORDPLN/order') ?>" class="menu_a <?= ($this->data['pos'] == "ORDPLN") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>주문/계획</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "ORDPLN") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('ORDPLN/order') ?>" class="<?= ($this->data['subpos'] == 'order') ? "on" : ""; ?>">주문등록</a></li>
                                                <li><a href="<?= base_url('ORDPLN/ordercur') ?>" class="<?= ($this->data['subpos'] == 'ordercur') ? "on" : ""; ?>">주문현황</a></li>
                                                <li><a href="<?= base_url('ORDPLN/orderprocess') ?>" class="<?= ($this->data['subpos'] == 'orderprocess') ? "on" : ""; ?>">주문대비 진행현황</a></li>
                                                <li><a href="<?= base_url('ORDPLN/prodpln') ?>" class="<?= ($this->data['subpos'] == 'prodpln') ? "on" : ""; ?>">생산계획 등록</a></li>
                                                <li><a href="<?= base_url('ORDPLN/prodplncur') ?>" class="<?= ($this->data['subpos'] == 'prodplncur') ? "on" : ""; ?>">생산계획 조회</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                if ($row->MENU_CODE == "PROD" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                ?>
                                    <li class="menu01_li">
                                        <a href="<?= base_url('PROD/workorder') ?>" class="menu_a <?= ($this->data['pos'] == "PROD") ? "on" : ""; ?>">
                                            <i class="material-icons">add_business</i>생산관리</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "PROD") ? "style='display:block'" : ""; ?>>
                                            <li><a href="<?= base_url('PROD/workorder') ?>" class="<?= ($this->data['subpos'] == 'workorder') ? "on" : ""; ?>">작업지시등록</a></li>
                                            <li><a href="<?= base_url('PROD/pworkorder') ?>" class="<?= ($this->data['subpos'] == 'pworkorder') ? "on" : ""; ?>">공정별 작업지시</a></li>
                                            <li><a href="<?= base_url('PROD/matinput') ?>" class="<?= ($this->data['subpos'] == 'matinput') ? "on" : ""; ?>">원재료 투입 입력</a></li>
                                            <li><a href="<?= base_url('PROD/pharvest') ?>" class="<?= ($this->data['subpos'] == 'pharvest') ? "on" : ""; ?>">공정별 수율정보</a></li>
                                            <li><a href="<?= base_url('PROD/pprodcur') ?>" class="<?= ($this->data['subpos'] == 'pprodcur') ? "on" : ""; ?>">공정별 생산내역</a></li>
                                            <li><a href="<?= base_url('PROD/dprodperf') ?>" class="<?= ($this->data['subpos'] == 'dprodperf') ? "on" : ""; ?>">기간별 생산실적</a></li>
                                            <li><a href="<?= base_url('PROD/prodmonitor') ?>" class="<?= ($this->data['subpos'] == 'prodmonitor') ? "on" : ""; ?>">생산 모니터링</a></li>
                                        </ul>
                                    </li>
                                <?php   }
                                if ($row->MENU_CODE == "_INTERFACE" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('_INTERFACE/empty') ?>" class="menu_a <?= ($this->data['pos'] == "_INTERFACE") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>생산관리INTERFACE</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "_INTERFACE") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('_INTERFACE/empty') ?>" class="<?= ($this->data['subpos'] == 'empty') ? "on" : ""; ?>">비어있음</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                if ($row->MENU_CODE == "STOCK" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('STOCK/package') ?>" class="menu_a <?= ($this->data['pos'] == "STOCK") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>재고/수불 관리</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "STOCK") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('STOCK/package') ?>" class="<?= ($this->data['subpos'] == 'package') ? "on" : ""; ?>">포장등록</a></li>
                                                <li><a href="<?= base_url('STOCK/stockcur') ?>" class="<?= ($this->data['subpos'] == 'stockcur') ? "on" : ""; ?>">재고내역</a></li>
                                                <li><a href="<?= base_url('STOCK/stockchange') ?>" class="<?= ($this->data['subpos'] == 'stockchange') ? "on" : ""; ?>">재고조정</a></li>
                                                <li><a href="<?= base_url('STOCK/release') ?>" class="<?= ($this->data['subpos'] == 'release') ? "on" : ""; ?>">출고등록</a></li>
                                                <li><a href="<?= base_url('STOCK/dbrelease') ?>" class="<?= ($this->data['subpos'] == 'dbrelease') ? "on" : ""; ?>">기간별/업체별 출고내역</a></li>
                                                <li><a href="<?= base_url('STOCK/claim') ?>" class="<?= ($this->data['subpos'] == 'claim') ? "on" : ""; ?>">클래임 등록</a></li>
                                                <li><a href="<?= base_url('STOCK/claimcur') ?>" class="<?= ($this->data['subpos'] == 'claimcur') ? "on" : ""; ?>">클래임 내역 조회</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                    if ($row->MENU_CODE == "PRODMON" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                        ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('PRODMON/prodmonoff') ?>" class="menu_a <?= ($this->data['pos'] == "PRODMON") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>모니터링</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "PRODMON") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('PRODMON/prodmonoff') ?>" class="<?= ($this->data['subpos'] == 'prodmonoff') ? "on" : ""; ?>">생산현황 모니터 - 사무동</a></li>
                                                <li><a href="<?= base_url('PRODMON/prodmonfac') ?>" class="<?= ($this->data['subpos'] == 'prodmonfac') ? "on" : ""; ?>">생산현황 모니터 - 공장동</a></li>
                                                
                                            </ul>
                                        </li>
                                    <?php   }
                                    if ($row->MENU_CODE == "SENSOR" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                        ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('SENSOR/senprod') ?>" class="menu_a <?= ($this->data['pos'] == "SENSOR") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>Sensor - 모니터링</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "SENSOR") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('SENSOR/senprod') ?>" class="<?= ($this->data['subpos'] == 'senprod') ? "on" : ""; ?>">Sensor 모니터링(생산현장용)</a></li>
                                                <li><a href="<?= base_url('SENSOR/senadmin') ?>" class="<?= ($this->data['subpos'] == 'senadmin') ? "on" : ""; ?>">Sensor 모니터링(관리자용)</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                if ($row->MENU_CODE == "QUAL" && $_SESSION['user_level'] >= $row->MENU_LEVEL) {
                                    ?>
                                        <li class="menu01_li">
                                            <a href="<?= base_url('QUAL/qexam') ?>" class="menu_a <?= ($this->data['pos'] == "QUAL") ? "on" : ""; ?>">
                                                <i class="material-icons">add_business</i>품질관리</a>
                                            <ul class="menu02" <?= ($this->data['pos'] == "QUAL") ? "style='display:block'" : ""; ?>>
                                                <li><a href="<?= base_url('QUAL/qexam') ?>" class="<?= ($this->data['subpos'] == 'qexam') ? "on" : ""; ?>">품질검사 등록</a></li>
                                                <li><a href="<?= base_url('QUAL/perfpoor') ?>" class="<?= ($this->data['subpos'] == 'perfpoor') ? "on" : ""; ?>">실적대비 불량률</a></li>
                                                <li><a href="<?= base_url('QUAL/qualitycur') ?>" class="<?= ($this->data['subpos'] == 'qualitycur') ? "on" : ""; ?>">품질이력</a></li>
                                                <li><a href="<?= base_url('QUAL/pooranal') ?>" class="<?= ($this->data['subpos'] == 'pooranal') ? "on" : ""; ?>">불량분석</a></li>
                                            </ul>
                                        </li>
                                    <?php   }
                                if ($row->MENU_CODE == "SYS" && $_SESSION['user_level'] >= $row->MENU_LEVEL) { ?>
                                    <li class="menu01_li">
                                        <a href="<?= base_url('SYS/menu') ?>" class="menu_a <?= ($this->data['pos'] == "SYS") ? "on" : ""; ?>">
                                            <i class="material-icons">settings</i>
                                            시스템관리</a>
                                        <ul class="menu02" <?= ($this->data['pos'] == "SYS") ? "style='display:block'" : ""; ?>>
                                            <li><a href="<?= base_url('SYS/menu') ?>" class="<?= ($this->data['subpos'] == 'menu') ? "on" : ""; ?>">메뉴등록</a></li>
                                            <li><a href="<?= base_url('SYS/register') ?>" class="<?= ($this->data['subpos'] == 'register') ? "on" : ""; ?>">사용자 등록</a></li>
                                            <li><a href="<?= base_url('SYS/level') ?>" class="<?= ($this->data['subpos'] == 'level') ? "on" : ""; ?>">사용자 권한등록</a></li>
                                            <li><a href="<?= base_url('SYS/userlog') ?>" class="<?= ($this->data['subpos'] == 'userlog') ? "on" : ""; ?>">접속기록</a></li>
                                            <li><a href="<?= base_url('SYS/version') ?>" class="<?= ($this->data['subpos'] == 'version') ? "on" : ""; ?>">버전관리</a></li>

                                        </ul>
                                    </li>
                            <?php   }
                            }
                        } else {
                            ?>
                            <li class="menu01_li">
                                <a href="<?= base_url('SYS/login') ?>" class="menu_a <?= ($this->data['pos'] == "SYS") ? "on" : ""; ?>">
                                    <i class="material-icons">assignment_ind</i>로그인</a>
                            </li>
                        <?php } ?>
                    </ul>


                </div>
            </div>
        </div>
        <input type="hidden" class="savehidden">
        <div class="body_">
            <div class="body_Content">