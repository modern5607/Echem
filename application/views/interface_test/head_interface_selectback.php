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

<div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
            <tr>
                <th class="number">NO</th>
                <th>배치 시작시간</th>
                <th>배치 종료시간</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($selectinfo)) {
                foreach ($selectinfo as $i => $row) {
                    $num = $i + 1;
            ?>
                    <tr id="par_<?= $num ?>" class="parsingbox" data-num="<?= $num ?>" data-idx=<?= $row->IDX ?>>
                        <td class="cen number"><?= $num; ?></td>
                        <td class="parsing_ajax mlink cen" data-sdate="<?= $row->START_DATE ?>" data-edate="<?= $row->FINISH_DATE ?>"><strong><?= $row->START_DATE ?></strong></td>
                        <td class="cen"><?= $row->FINISH_DATE ?></td>
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