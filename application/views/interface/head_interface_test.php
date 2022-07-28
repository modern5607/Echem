<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<style>
    @media(max-width:1400px) {
        .number {
            display: none;
        }
    }

    .errorTr:not(.over)>td {
        background: #f7e7e7
    }
</style>
<div class="searchBox">
    <header>
        <div class="searchDiv">
            <form id="headForm" onsubmit="return false">
                <label>배치등록일</label>
                <input type="date" name="sdate" value="<?= $str['sdate']; ?>" class="" /> ~
                <input type="date" name="edate" value="<?= $str['edate']; ?>" class="" />

                <button class="search_submit head_search"><i class="material-icons">search</i></button>
            </form>
        </div>
        <!-- <span class="btn print add_order"  style="padding:7px 11px;"><i class="material-icons">add</i>작업지시 등록</span> -->
        <!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
    </header>

</div>

<div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
            <tr>
                <th class="number">NO</th>
                <th>배치 시작시간</th>
                <th>배치 종료시간</th>
				<th>배치등록일</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($list)) {
                foreach ($list as $i => $row) {
                    $num = $pageNum + $i + 1;
            ?>
            <?php
            // if (!empty($selectinfo)) {
            //     foreach ($selectinfo as $i => $row) {
            //         $num = $i + 1;
            // ?>
                    <tr id="poc_<?= $num ?>" class="pocbox" data-idx="<?= $num ?>">
                        <td class="cen number"><?= $num; ?></td>
                        <td class="parsing_ajax mlink cen" data-sdate="<?= $row->START_DATE ?>" data-edate="<?= $row->FINISH_DATE ?>"><strong><?= $row->START_DATE ?></strong></td>
                        <td class="cen"><?= $row->FINISH_DATE ?></td>
                        <td class="items_ajax mlink cen" data-date="<?= $row->START_DATE ?>"><strong><?= $row->START_DATE; ?></strong></td>                                
				
				</tr>

                    </tr>

                <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="15" class="list_none">수신일을 선택해주세요.</td>
                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>
</div>




<script>
    $(document).off("click", ".items_ajax");
    // 달력 출력
   
    $(document).off("click", ".parsing_ajax");
    $(document).on("click", ".items_ajax", function() {
        //alert("asdf");
        var idx = $(this).parent().data("idx");
        var date = $(this).data("date");
        var tank = "<?=$str['tank']?>";
        // console.log(idxdate);
        $(".pocbox").removeClass("over");
        $("#poc_" + idx).addClass("over");

        var num = $(this).parent().data("num");
        var sdate = $(this).data("sdate");
        var edate = $(this).data("edate");
        $(".parsingbox").removeClass("over");
        $("#par_" + num).addClass("over");

        head_interface_select(date,tank);
        detail_parsing(sdate, edate);
    });


    $(document).on("click", ".items_ajax", function() {
        $(".parsingbox").removeClass("over");
        $("#par_").addClass("over");

        detail_parsing(sdate, edate);
    });

    function head_interface_select(date,tank='') {
        // console.log("ajax_container" + idx);
        $.ajax({
            url: "<?= base_url('_INTERFACE/head_interface_select') ?>",
            type: "post",
            data: {
                date:date,
                tank:tank
            },
            dataType: "html",
            success: function(data) {
                $(".ajax_select").empty();
                $(".ajax_select").html(data);
                // load2();
            }
        });
    }
    $(document).ready(function() {
        head_interface_select(0);
        // detail_parsing2(0);

        google.charts.load('current', {'packages':['line','controls']});
        chartDrowFun.chartDrow(); //chartDrow() 실행
    });
</script>