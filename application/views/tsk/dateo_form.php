<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>


<div class="formContainer">
	<form name="orderform" id="orderform">
	<input type="hidden" name="por" value="<?= $List[0]->POR_NO;?>">
	<input type="hidden" name="seq" value="<?= $List[0]->POR_SEQ;?>">
		<div class="register_form">
			<fieldset class="form_2" style="max-height:60vh; margin-top: 132px;">
			<div class="formDiv">
				<h2>작업지시
					<span class="material-icons close">clear</span>
				</h2>
				<div class="inform">
					<label for="">공정: </label>
						<input disabled type="text" value="<?= $Gj[0]->D_NAME;?>">
					<label for="plndate">작업예정일: </label>
						<input type="text" name="plndate" class="calendar" value="<?= date("Y-m-d") ?>">
						<!-- <?= $List[0]->{$Gj[0]->D_CODE.'_PLN'};?> -->
					<br>
					<label for="">품명: </label>
						<input disabled type="text" value="<?= $List[0]->MCCSDESC;?>" style="width:392px">
					<input type="hidden" name="QW" data-qty="<?= round($List[0]->PO_QTY);?>" data-weight="<?= round($List[0]->WEIGHT);?>">
				</div>
			</div>
				<table>
					<thead>
						<tr>
							<th style="width:33px;"></th>
							<th>작업자</th>
							<th>수량</th>
							<th>누적수량</th>
							<th>누적중량</th>
							<th>숙련도</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach($Member as $i=>$row){ 
								$QTY = $row->PROCQ + $row->ASSEQ + $row->WELDQ + $row->MROQ;
								$WEIGHT = $row->PROCW + $row->ASSEW + $row->WELDW + $row->MROW;
						?>
						<tr>
							<td><input name="check" type="checkbox" <?= ($NAME == $row->NAME)?"checked":""; ?>></td>
							<td><?= $row->NAME; ?></td>
							<td class="right"><?= NUMBER_FORMAT($List[0]->PO_QTY); ?></td>
							<td class="right"><?= ($NAME == $row->NAME)?NUMBER_FORMAT($QTY+$List[0]->PO_QTY):NUMBER_FORMAT($QTY); ?></td>
							<td class="right"><?= ($NAME == $row->NAME)?NUMBER_FORMAT($WEIGHT+$List[0]->WEIGHT):NUMBER_FORMAT($WEIGHT); ?></td>
							<td class="right"></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</fieldset>
			
			<div class="bcont">
				<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif');?>' width="100"></span>
				<button type="button" class="submitBtn blue_btn"> 등록 </button>
			</div>
			
		</div>

	</form>

</div>







<script>
//달력
$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


// 테이블 체크박스, 수량조절
$(document).off("click",".register_form table tr");
$(document).on("click",".register_form table tr",function(){
	//체크된 박스 행
	var checked = $("input[name=check]:checked").closest("tr");
	
	// 기존 물량
	var qty = $("input[name='QW']").data("qty") * 1;
	var weight = $("input[name='QW']").data("weight") * 1 * qty;

	// 표시할 변경값
	var minusQty = Math.round(checked.find('td:eq(3)').text().replace(/[^\d]+/g, '')*1-qty).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
	var plusQty = Math.round($(this).find('td:eq(3)').text().replace(/[^\d]+/g, '')*1+qty).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");

	var minusWeight = Math.round(checked.find('td:eq(4)').text().replace(/[^\d]+/g, '')*1-weight).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
	var plusWeight = Math.round($(this).find('td:eq(4)').text().replace(/[^\d]+/g, '')*1+weight).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");

	// tr클릭시 체크 설정
	if($("input:checkbox[name=check]").is(":checked") == true) {
		$("input[name=check]:checkbox").prop("checked", false);
	};
	$(this).find('td:first-child :checkbox').prop("checked", true);
	
	// 체크된 체크박스 선택시 수량조절 x
	if(checked.get(0) !== $(this).get(0)){
	checked.find('td:eq(3)').text(minusQty);
	checked.find('td:eq(4)').text(minusWeight);

	$(this).find('td:eq(3)').text(plusQty);
	$(this).find('td:eq(4)').text(plusWeight);
	};
});
$(document).off("click","input[type=checkbox]")
$(document).on("click","input[type=checkbox]",function(){
	if($(this).is(":checked") == true) { $(this).prop("checked", false); }else{ $(this).prop("checked", true); };
});

//업체 리스트 업데이트(추가,변경)
$(document).off("click",".submitBtn");
$(document).on("click",".submitBtn",function(){
	var $this = $(this);
	
	var formData = new FormData();
	formData.append("date",$("input[name=plndate]").val());
	formData.append("por",'<?= $List[0]->POR_NO;?>');
	formData.append("seq",'<?= $List[0]->POR_SEQ;?>');
	formData.append("gj",'<?= $Gj[0]->D_CODE;?>');
	formData.append("name",$("input[name=check]:checked").closest("tr").find('td:eq(1)').text());


	$.ajax({
		url  : "<?php echo base_url('/TSK/order_up')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		beforeSend  : function(){
			$this.hide();
			$("#loading").show();
		},
		success : function(data){

			var jsonData = JSON.parse(data);
			if(jsonData.status == "ok"){
			
				setTimeout(function(){
					alert(jsonData.msg);
					$(".ajaxContent").html('');
					$("#pop_container").fadeOut();
					$(".info_content").css("top","-50%");
					$("#loading").hide();
					
					load();
				},1000);

			}
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr,textStatus,errorThrown);
			$this.show();
			$("#loading").hide();
		}
	});
});



//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>