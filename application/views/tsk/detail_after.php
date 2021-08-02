<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>


<style>
.headafter{width:100%;margin-bottom:20px; padding:15px; border:1px solid #ddd;}
.headafter table{width:100%;}
.headafter table select{border: 1px solid #ddd;padding: 5px 7px;margin: 3px 4px; width:100%}
.headafter table input{border: 1px solid #ddd;padding: 5px 7px;margin: 3px 4px; width:100%}
.headafter table input:read-only{background: #eee;}
.headafter .list_none{ padding: 49px 0;color: #999;text-align: center;}

.left{text-align:left;padding-left:5px}
.cen{text-align:center}
.after_up_btn{background: #414350; color: #fff; width: 70px; height: 30px; border: 0;}
</style>

<div class="headafter">
    <form id="WRK">
        <table>
            <?php if(!empty($List[0])){?>
            <input type="hidden" name="idx" value="<?= $List[0]->IDX; ?>">
            <tr><th class="left res">의뢰업체</th></tr>
            <tr>
                <td>
                    <?php if(empty($List[0]->TRS_REMARK)){ ?>
                        <select name="dept" id="biz">
                            <option value="" <?= ($List[0]->TRS_REMARK)?'disabled':''; ?>>선택</option>
                            <?php foreach($Biz as $i=>$row){ ?>
                                <option value="<?= $row->CUST_NM ?>" <?= ($List[0]->TRS_REMARK == $row->CUST_NM)?"selected":(($List[0]->TRS_DATE)?'disabled':'') ?>  ><?= $row->CUST_NM ?></option>
                            <?php } ?>
                        </select>
                    <?php }else{ ?>
                        <input type="text" name="trgb" value="<?= $List[0]->TRS_REMARK ?>" readonly>
                    <?php } ?>
                </td>
            </tr>
            <tr><th class="left res">의뢰일</th></tr>
            <tr><td><input type="text" name="date" class="<?= ($List[0]->TRS_DATE)?'':'calendar'; ?>" value="<?= ($List[0]->TRS_DATE)?$List[0]->TRS_DATE:date("Y-m-d"); ?>" <?= ($List[0]->TRS_DATE)?'readonly':''; ?>/></td></tr>
            <?php if($List[0]->TRS_REMARK != ""){?> 
            <tr><th class="left res">의뢰완료일</th></tr>
            <tr><td><input type="text" name="enddate"class="<?= ($List[0]->END_YN == 'Y')?'':'calendar'; ?>" value="<?= ($List[0]->ENDDATE)?$List[0]->ENDDATE:date('Y-m-d'); ?>" <?= ($List[0]->END_YN == 'Y')?'readonly':''; ?>></td></tr>
            <?php } ?>
            <tr><th class="left">POR_NO</th></tr>
            <tr><td><input type="text" name="porno" value="<?= $List[0]->POR_NO; ?>" readonly></td></tr>
            <tr><th class="left">POR_SEQ</th></tr>
            <tr><td><input type="text" name="seq" value="<?= $List[0]->POR_SEQ; ?>" readonly></td></tr>
            <tr><th class="left res">물량</th></tr>
            <tr><td>
                <input type="number" name="qty" min="0" max="<?= round($List[0]->PO_QTY); ?>" 
                data-max="<?= round($List[0]->PO_QTY - $List[0]->TRS_QTY); ?>" data-weight="<?= $List[0]->WEIGHT; ?>" 
                onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" <?= ($List[0]->TRS_REMARK)?'readonly':''; ?> 
                value="<?= ($List[0]->TRS_REMARK)?round($List[0]->TRS_QTY):round($List[0]->PO_QTY - $List[0]->TRS_QTY); ?>">
            </td></tr>
            <tr><th class="left">총 중량</th></tr>
            <tr><td><input name="weight" type="text" readonly
                    value="<?= ($List[0]->TRS_REMARK)?round($List[0]->TRS_WEIGHT,3):round(($List[0]->PO_QTY - $List[0]->TRS_QTY) * $List[0]->WEIGHT,3); ?>">
            </td></tr>
            <?php if($List[0]->END_YN != 'Y'){?>
            <tr><td class="cen"><button type="button" class="after_up_btn"><?= ($List[0]->TRS_REMARK)?'완료':'저장'; ?></button></td></tr>
            <?php } }else{ ?>

            <tr><td colspan="15" class="list_none" style="height:382px">목록을 선택하세요.</td></tr>

            <?php } ?>
        </table>
    </form>
</div>




<script>
// 달력
$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

$(document).off("change",".headafter table tr:eq(9) td input");
$(document).on("change",".headafter table tr:eq(9) td input",function(){
    if( $(this).val() >  $(this).data('max')){ $(this).val( $(this).data('max')); }
    if( $(this).val() < 0){ $(this).val(0); }
    $('.headafter table tr:eq(11) td input').val(($(this).val() * $(this).data('weight')).toFixed(3))
})
// tr클릭시 체크 설정
$(document).off("click",".after_up_btn");
$(document).on("click",".after_up_btn",function(){
    if($("select[name=dept]").val() == '' || $("input[name='date']").val() == '' || $("input[name='weight']").val() == '0.000'){
        alert("필수사항을 입력해주세요")
        return false;
    }    
    var yn = 'N'
    if($(".headafter table").find('.after_up_btn').text() == '완료'){
        yn = 'Y'
    }

    afterUp = new FormData($("#WRK")[0]);
    afterUp.append('yn', yn);
	$.ajax({
		url: "<?= base_url('/TSK/after_up/') ?>",
		type: "post",
		data : afterUp,
		dataType: "html",
		cache  : false,
		contentType : false,
		processData : false,
		success: function(data) {
            alert('저장되었습니다.')
			load()
		}
	});

});

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>