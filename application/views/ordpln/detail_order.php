<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	#detailForm2{
		padding-top:86px
	}
    #detailForm2 .tbl-content th {
        background: white;
    }

    #detailForm2 .tbl-content td {
        background: white;
    }
</style>

<?php 
    if (!empty($str['idx'])) {
        $actnm = $list[0]->ACT_NAME;
        $qty = ROUND($list[0]->QTY,3);
        $adate = $list[0]->ACT_DATE;
        $ddate = $list[0]->DEL_DATE;
        $biz = $list[0]->BIZ_IDX;
        $biznm = $list[0]->BIZ_NAME;
        $remark = $list[0]->REMARK;
    }else{
        $actnm = '';
        $qty = '';
        $adate = date('Y-m-d');
        $ddate = date('Y-m-d');
        $biz = '';
        $biznm = '';
        $remark = '';
    }
?>


<form id="detailForm2">
    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <tr>
                    <th>수주명</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $actnm ?>' name="ACTNM"></td>

                    <th>수량(T)</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $qty ?>' name="QTY"></td>
                </tr>
                <tr>
                    <th>납기요청일</th>
                    <td><input type="text" class="form_input input_100 calendar" autocomplete="off" value='<?= $adate ?>' name="ADATE"></td>
                        
                    <th>납기예정일</th>
                    <td><input type="text" class="form_input input_100 calendar" autocomplete="off" value='<?= $ddate ?>' name="DDATE"></td>
                </tr>
                <tr>
                    <th>거래처</th>
                    <td>
                        <select name="BIZ" id="BIZ" class="form_input input_100">
                            <option value="">거래처</option>
                            <?php foreach ($BIZ as $row) { ?>
                                <option value="<?php echo $row->IDX ?>" <?php echo ($biz == $row->IDX) ? "selected" : ""; ?>><?php echo $row->CUST_NM; ?></option>
                            <?php } ?>
                        </select>
                    </td>

                    <th>거래처 담당자</th>
                    <td><input type="text" class="form_input input_100" autocomplete="off" value='<?= $biznm ?>' name="BIZNM"></td>
                </tr>
                <tr>
                    <th>세부사항</th>
                    <td colspan="3">
                        <textarea name="REMARK" id="" style="resize:none;" class="form_input input_100" rows="5"><?= $remark ?></textarea>
                    </td>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <?php if (!empty($str['idx'])) { ?>
                        <td rowspan="5" colspan="4" class="cen" style="padding: 15px;">
                            <button type="button" class="btn blue_btn upBtn" >수정</button>
                            <button type="button" class="btn blue_btn delBtn" >삭제</button>
                        </td>
                    <?php }else{ ?>
                        <td rowspan="5" colspan="4" class="cen" style="padding: 15px;"><button type="button" class="btn blue_btn submitBtn" >저장</button></td>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
</form>



<script>
    $(".calendar").datetimepicker({
        format: 'Y-m-d',
        timepicker: false,
        lang: 'ko-KR'
    });

    $(".submitBtn").on("click",function(){

        var formData = new FormData();
        formData.append("ACTNM", $("input[name='ACTNM']").val());   
        formData.append("QTY", $("input[name='QTY']").val());   
        formData.append("ADATE", $("input[name='ADATE']").val());   
        formData.append("DDATE", $("input[name='DDATE']").val());
        formData.append("BIZNM", $("input[name='BIZNM']").val());
        formData.append("BIZ", $("select[name='BIZ']").val());
        formData.append("REMARK", $("textarea[name='REMARK']").val());


        if($("input[name='QTY']").val() == ""){
            alert("수주 수량 입력하세요.");
            $("input[name='QTY']").focus();
            return false;
        }

        $.ajax({
            url: "<?php echo base_url('ORDPLN/order_insert') ?>",
            type: "POST",
            data: formData,
            //asynsc : true,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                location.reload();
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });
    });


    $(".delBtn").on("click", function() {
        var idx = '<?= $str['idx'] ?>';
        
        if (confirm('삭제하시겠습니까?') !== false) {

            $.get("<?php echo base_url('ORDPLN/del_order') ?>", {
                idx: idx
            }, function(data) {
                location.reload();
            }, "JSON").done(function(jqXHR) {
                location.reload();
            })
        }
    });     


    $(".upBtn").on("click", function() {
        var formData = new FormData();
        formData.append("ACTNM", $("input[name='ACTNM']").val());   
        formData.append("QTY", $("input[name='QTY']").val());   
        formData.append("ADATE", $("input[name='ADATE']").val());   
        formData.append("DDATE", $("input[name='DDATE']").val());
        formData.append("BIZNM", $("input[name='BIZNM']").val());
        formData.append("BIZ", $("select[name='BIZ']").val());
        formData.append("REMARK", $("textarea[name='REMARK']").val());
        formData.append("IDX", '<?= $str['idx'] ?>');

        for (var pair of formData.entries()) {
  console.log(pair[0]+ ', ' + pair[1]);
}

        if (confirm('수정하시겠습니까?') !== false) {
            $.ajax({
                url: "<?php echo base_url('ORDPLN/update_order') ?>",
                type: "POST",
                data: formData,
                //asynsc : true,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    location.reload();
                },
                error: function(xhr, textStatus, errorThrown) {
                    alert(xhr);
                    alert(textStatus);
                    alert(errorThrown);
                }
            });
        }
    });
</script>