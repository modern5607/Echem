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
                    $num = $i + 1;
            ?>
                            <tr id="poc_<?= $num ?>" class="pocbox" data-idx="<?= $num ?>">
                                <td class="cen"><?= $num; ?></td>
                                <td class="items_ajax mlink cen" data-date="<?= $row->START_DATE ?>"><strong><?= $row->START_DATE; ?></strong></td>
                                <td class="cen"><?= $row->BATCH_COUNT ?></td>
                                <td class="items_ajax mlink cen" data-date="<?= $row->START_DATE ?>"><strong><?= $row->START_DATE; ?></strong></td>

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





<script>
    $(document).off("click", ".parsing_ajax");
    $(document).ready(function() {
        // $("#par_1").addClass("over");
        // var IDX = $(".parsingbox").data("idx");
        // detail_parsing(IDX);

    });

    $(document).on("click", ".parsing_ajax", function() {
        var num = $(this).parent().data("num");
        var sdate = $(this).data("sdate");
        var edate = $(this).data("edate");
        $(".parsingbox").removeClass("over");
        $("#par_" + num).addClass("over");

        detail_parsing(sdate, edate);
    });

    function detail_parsing(sdate,edate='') {
        var tank = "<?=$str['tank']?>";
        $.ajax({
            url: "<?= base_url('_INTERFACE/detail_interface') ?>",
            type: "post",
            data: {
                sdate: sdate,
                edate: edate,
                tank:tank
            },
            dataType: "html",
            success: function(data) {
                $("#ajax_detail_container").empty();
                $("#ajax_detail_container").html(data);
                // $(".ajax_container").html(data);
            }
        });

    }
</script>
