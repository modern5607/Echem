<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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

<div class="bdcont_50">
    <div class="bc__box">
        <div class="tbl-content">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <thead>
                    <tr>
                        <th>NO</th>
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
                            <tr id="par_<?= $num ?>" class="parsingbox" data-num="<?= $num ?>" data-idx=<?= $row->IDX ?>>
                                <td class="cen"><?= $num; ?></td>
                                <td class="items_ajax mlink cen" data-date="<?= $row->START_DATE ?>"><strong><?= $row->START_DATE; ?></strong></td>
                                <td class="cen"><?= $row->FINISH_DATE ?></td>
                                <td class="parsing_ajax mlink cen" data-sdate="<?= $row->INSERT_DATE ?>" data-edate="<?= $row->INSERT_DATE ?>"><strong><?= $row->INSERT_DATE ?></strong></td>
                            </tr>

                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="15" class="list_none">제품정보가 없습니다.</td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
        <div class="pagination noflex">
            <?php
            if ($this->data['cnt'] > 20) {
            ?>
                <div class="limitset">
                    <select name="per_page">
                        <option value="20" <?= ($perpage == 20) ? "selected" : ""; ?>>20</option>
                        <option value="50" <?= ($perpage == 50) ? "selected" : ""; ?>>50</option>
                        <option value="80" <?= ($perpage == 80) ? "selected" : ""; ?>>80</option>
                        <option value="100" <?= ($perpage == 100) ? "selected" : ""; ?>>100</option>
                    </select>
                </div>
            <?php
            }
            ?>
            <?= $this->data['pagenation']; ?>
        </div>
    </div>
</div>


<div class="bdcont_50">
    <div class="bc__box">
        <div class="ajax_select">
        </div>
        <!-- 
        <div class="ajax_container bdcont_80">
        </div> -->
    </div>
</div>


<script>
    $(document).off("click", ".items_ajax");
    // 달력 출력
   
    $(document).on("click", ".items_ajax", function() {
        //alert("asdf");
        var idx = $(this).parent().data("idx");
        var date = $(this).data("date");
        var tank = "<?=$str['tank']?>";
        // console.log(idxdate);
        $(".pocbox").removeClass("over");
        $("#poc_" + idx).addClass("over");

        head_interface_select(date,tank);
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