<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<style>
    @media(max-width:1400px){.number{display:none;}}
    .errorTr:not(.over) >td {background:#f7e7e7}
</style>

<div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
            <tr>
                <th class="number">NO</th>
                <th>수신 시간</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if(!empty($selectinfo)){
                foreach($selectinfo as $i=>$row){
                $num = $i+1;
        ?>
                <tr id="par_<?=$num?>" class="parsingbox" data-num="<?=$num?>" data-idx=<?=$row->IDX?>>
                    <td class="cen number"><?= $num;?></td>
                    <td class="parsing_ajax mlink cen" ><strong><?= $row->INSERT_DATE; ?></strong></td>
                </tr>

        <?php
                }
            }else{
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
$(document).off("click",".parsing_ajax");
$(document).ready(function(){
    // $("#par_1").addClass("over");
    // var IDX = $(".parsingbox").data("idx");
    // detail_parsing(IDX);
    
});

$(document).on("click", ".parsing_ajax", function() {
    var num = $(this).parent().data("num");
    $(".parsingbox").removeClass("over");
    $("#par_" + num).addClass("over");
    
    var IDX = $(".parsingbox.over").data("idx");
    detail_parsing(IDX);
});

function detail_parsing(idx="") {
    $.ajax({
        url: "<?= base_url('_INTERFACE/detail_interface')?>",
        type: "post",
        data: {
            idx: idx
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