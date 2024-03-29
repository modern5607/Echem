<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<link href="<?php echo base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>
<style type="text/css">
    .tg {
        border-collapse: collapse;
        border-spacing: 0;
        width: 100%;
    }

    .tg td {

        border-color: #121415;
        /* border-style: solid; */
        border-width: 5px;
        font-family: Arial, sans-serif;
        /* font-size: 14px; */
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
        font-weight: bolder;
    }

    .tg th {
        border-color: black;
        border-style: solid;
        border-width: 1px;
        font-family: Arial, sans-serif;
        font-size: 14px;
        font-weight: normal;
        overflow: hidden;
        padding: 10px 5px;
        word-break: normal;
    }

    .tg .tg-9wq8 {
        border-color: inherit;
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-8uya {
        background-color: #FFCB2F;
        border-color: #000000;
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-936e {
        background-color: #ffcb2f;
        border-color: inherit;
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-ndbq {
        background-color: #FFCB2F;
        border-color: inherit;
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-rcip {
        background-color: #FFF;
        border-color: inherit;
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-nrix {
        text-align: center;
        vertical-align: middle
    }

    .tg .tg-xwyw {
        border-color: #000000;
        text-align: center;
        vertical-align: middle
    }

    .water_tank td:nth-child(0) {}

    .color {
        background: #121415;
        text-align: center;
        width: 210px;
        height: auto;
        color: white;
        font-size: 18px;
    }

    .column {
        width: 100px;
        text-align: center;
        background: #232526;
        border-right: #232526;
        border-bottom: 5px solid #121415;
        color: white;

        font-size: 18px;
    }

    .datacell {
        width: 190px;
        height: 55px;
        background: #232526;
        border-bottom: 5px solid #121415;
        font-size: 40px;
        color: yellow;

    }

    .tag {
        border-bottom: 1px solid blueviolet;
        width: 18px;
        background-color: blueviolet;

    }

    .tbl-content {
        margin-bottom: 4px;
    }

    .body_Content {
        background-color: #121415;
        height: 100%;
    }

    body {
        /* margin-top: 100px; */
        font-family: 'Trebuchet MS', serif;
        line-height: 1.6
    }

    .container {
        width: 100%;
        margin: 0 auto;
    }



    ul.tabs {
        margin: 0px;
        padding: 0px;
        list-style: none;
    }

    ul.tabs li {

        font-family: 'Malgun Gothic', dotum, sans-serif;
        font-size: large;
        /* border: 2px solid #414350; */
        background: none;
        color: #fff;
        display: inline-block;
        padding: 15px 20px;
        font-weight: lighter;
        cursor: pointer;
    }

    ul.tabs li.current {
        background: #0157FE;
        color: white;
        font-weight: bolder;
    }

    .tab-content {
        display: none;
        background: #121415;
        padding: 15px;
    }

    .tab-content.current {
        display: inherit;
    }
</style>


<div class="bdcont_100">
    <div class="">
        <div class="container">

            <ul class="tabs">
                <!-- <li class="tab-link current" data-tab="tab-1">온수탱크</li> -->
                <li class="tab-link current" data-tab="tab-1">A 탱크(2Ton)</li>
                <li class="tab-link" data-tab="tab-2">B 탱크(2Ton)</li>
                <li class="tab-link" data-tab="tab-3">C 탱크(5Ton)</li>
                <li class="tab-link" data-tab="tab-4">SUS 탱크(1Ton)</li>
                <li class="tab-link" data-tab="tab-5">SUS 탱크(1Ton)</li>
                <!-- <li class="tab-link" data-tab="tab-7">교반탱크3</li> -->
            </ul>

             <!-- <div id="tab-1" class="tab-content current">
                <div style="display: block;height: 440px;">
                    <span class="water_tank" style="float: left;width: 50%;">
                        <table class="tg">
                            <tr>
                                <td rowspan="2" class="color">온수탱크1<br><br><img src="../_static/img/water-tank.png" width="200"></td>
                                <td class="tag"></td>
                                <td class="column">수위(%)</td>
                                <td class="datacell right"><?=$list[0]->LEVEL?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">온도(°C)</td>
                                <td class="datacell right"><?=$list[0]->TEMP?></td>
                            </tr>
                        </table>
                    </span>


                    <span class="water_tank" style="float: left; width:50%">
                        <table class="tg">
                            <tr>
                                <td rowspan="2" class="color">온수탱크2<br><br><img src="../_static/img/water-tank.png" width="200"></td>
                                <td class="tag"></td>
                                <td class="column">수위(%)</td>
                                <td class="datacell right"><?=$list[1]->LEVEL?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">온도(°C)</td>
                                <td class="datacell right"><?=$list[1]->TEMP?></td>
                            </tr>
                        </table>
                    </span>
                </div>

            </div>   -->

            <div id="tab-1" class="tab-content current">
                <div style="display: block;height: 440px;">
                    <!--탄산나트륨탱크 -->
                    <span class="" style="float: left;width:100%">
                        <table class="tg">
                            <tr>
                                <td rowspan="5" class="color">A 탱크(2Ton)<br><br><img src="../_static/img/tank.png" width="270"></td>
                                <td class="tag"></td>
                                <td class="column">PH</td>
                                <td class="datacell right"><?=$list[0]->PH?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">CL</td>
                                <td class="datacell right"><?=$list[0]->CL?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">온도(°C)</td>
                                <td class="datacell right"><?=$list[0]->TEMP?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">압력(Pa)</td>
                                <td class="datacell right"><?=$list[0]->PRESS?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">수위(%)</td>
                                <td class="datacell right"><?=$list[0]->LEVEL?></td>
                            </tr>
                        </table>
                    </span>
                </div>

            </div>
            <div id="tab-2" class="tab-content">
                <div style="display: block;height: 440px;">
                    <!--탄산나트륨탱크 -->
                    <span class="" style="float: left;width:100%">
                        <table class="tg">
                            <tr>
                                <td rowspan="5" class="color">B 탱크(2Ton)<br><br><img src="../_static/img/tank.png" width="270"></td>
                                <td class="tag"></td>
                                <td class="column">PH</td>
                                <td class="datacell right"><?=$list[1]->PH?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">CL</td>
                                <td class="datacell right"><?=$list[1]->CL?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">온도(°C)</td>
                                <td class="datacell right"><?=$list[1]->TEMP?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">압력(Pa)</td>
                                <td class="datacell right"><?=$list[1]->PRESS?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">수위(%)</td>
                                <td class="datacell right"><?=$list[1]->LEVEL?></td>
                            </tr>
                        </table>
                    </span>
                </div>

            </div>
            <div id="tab-3" class="tab-content">
                <div style="display: block;height: 440px;">
                    <!--세척탱크 -->
                    <span class="" style="float: left;width:100%">
                        <table class="tg">
                            <tr>
                                <td rowspan="5" class="color">C 탱크(5Ton)<br><br><img src="../_static/img/tank.png" width="270"></td>
                                <td class="tag"></td>
                                <td class="column">PH</td>
                                <td class="datacell right"><?=$list[2]->PH?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">CL</td>
                                <td class="datacell right"><?=$list[2]->CL?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">온도(°C)</td>
                                <td class="datacell right"><?=$list[2]->TEMP?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">압력(Pa)</td>
                                <td class="datacell right"><?=$list[2]->PRESS?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">수위(%)</td>
                                <td class="datacell right"><?=$list[2]->LEVEL?></td>
                            </tr>
                        </table>
                    </span>
                </div>

            </div>
            <div id="tab-4" class="tab-content">
                <div style="display: block;height: 440px;">
                    <!--반응탱크 -->
                    <span class="" style="float: left;width:100%">
                        <table class="tg">
                            <tr>
                                <td rowspan="5" class="color">SUS 탱크(1Ton)<br><br><img src="../_static/img/raw-tank.png" width="270"></td>
                                <td class="tag"></td>
                                <td class="column">PH</td>
                                <td class="datacell right"><?=$list[3]->PH?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">CL</td>
                                <td class="datacell right"><?=$list[3]->CL?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">온도(°C)</td>
                                <td class="datacell right"><?=$list[3]->TEMP?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">압력(Pa)</td>
                                <td class="datacell right"><?=$list[3]->PRESS?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">수위(%)</td>
                                <td class="datacell right"><?=$list[3]->LEVEL?></td>
                            </tr>
                        </table>
                    </span>
                </div>

            </div>
            <div id="tab-5" class="tab-content">
                <div style="display: block;height: 440px;">
                    <!--교반탱크 -->
                    <span class="" style=" float: left;width:100%">
                        <table class="tg">
                            <tr>
                                <td rowspan="5" class="color">SUS 탱크(1Ton)<br><br><img src="../_static/img/raw-tank.png" width="270"></td>
                                <td class="tag"></td>
                                <td class="column">PH</td>
                                <td class="datacell right"><?=$list[4]->PH?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">CL</td>
                                <td class="datacell right"><?=$list[4]->CL?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">온도(°C)</td>
                                <td class="datacell right"><?=$list[4]->TEMP?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">압력(Pa)</td>
                                <td class="datacell right"><?=$list[4]->PRESS?></td>
                            </tr>
                            <tr>
                                <td class="tag"></td>
                                <td class="column">수위(%)</td>
                                <td class="datacell right"><?=$list[4]->LEVEL?></td>
                            </tr>
                        </table>
                    </span>
                </div>

            </div>

        <!-- <div style="float: left;">
            <div class="tbl-content" style="max-height: none; overflow-y: auto;">
                <table class="tg">
                    <tr>
                        <td class="datacell column" style="height: 100px;">일일 생산계획</td>
                        <td class="datacell column" style="height: 100px;">일일 생산실적</td>
                        <td class="datacell column" style="height: 100px;">진행률(%)</td>
                        <td class="datacell column" style="height: 100px;">잔여율(%)</td>
                    </tr>
                    <tr>
                        <td class="datacell column" style="height: 100px;"></td>
                        <td class="datacell column" style="height: 100px;"></td>
                        <td class="datacell column" style="height: 100px;"></td>
                        <td class="datacell column" style="height: 100px;"></td>
                    </tr>
                </table>
            </div>
        </div> -->
    </div>
</div>

<!-- <div id="pop_container">
	<div id="info_content" class="info_content" style="height:auto;">
		<div class="ajaxContent">
		</div>
	</div>
</div> -->

<script>
    $(document).ready(function() {
        $(".menu_Content").animate({
            width: 0
        }, 0, function() {
            $(".mhide").addClass('mshow');
            $(".mhide span").text("keyboard_arrow_right");
        });

        $("#smart_container").animate({
            paddingLeft: '15px'
        }, 0, function() {
            $(".mControl_show").show();
        });

        window.scrollTo(0,1);
    });

    $('ul.tabs li').click(function() {
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs li').removeClass('current');
        $('.tab-content').removeClass('current');

        $(this).addClass('current');
        $("#" + tab_id).addClass('current');
    });
</script>