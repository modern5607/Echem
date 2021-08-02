<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>
<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script>


<div class="formContainer">
	<form name="orderform" id="orderform">
		<div class="register_form">
			<fieldset class="form_2" style="max-height:60vh; margin-top: 95px;">
			<div class="formDiv">
				<h2>실적등록
					<span class="material-icons close">clear</span>
				</h2>
				<div class="inform">
					<label for="plndate">작업완료일: </label>
						<input type="text" name="wrkdate" class="calendar" value="<?= date("Y-m-d") ?>">
				</div>
			</div>
				<table>
					<thead>
						<tr>
							<th style="width:33px;"></th>
							<th>POR NO</th>
							<th>작업수</th>
							<th>수량</th>
							<th>공정</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($PORNO as $i => $porno){ ?>
						<tr>
							<td><input  style="width:13px;" name="checkForm" type="checkbox" checked></td>
							<td><?= $porno ?></td>
							<td class="right"><?= $CNT[$i] ?></td>
							<td class="right"><?= $QTY[$i] ?></td>
							<td><?= $GJ[$i] ?></td>
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
$(document).off("click",".submitBtn ");
$(document).on("click",".submitBtn ",function(){
	var $this = $(this);
	var formData = new FormData();
	var checkbox = $("input[name=checkForm]:checked");


	// 체크된 체크박스 값을 가져온다
	checkbox.each(function(i) {
		var tr = checkbox.parent().parent().eq(i);
		var td = tr.children();

		// td.eq(0)은 체크박스 이므로  td.eq(1)의 값부터 가져온다.
		var porno = td.eq(1).text()
		var gj = td.eq(4).text();
		var gjcheck = '';
		if(gj == "절단/가공"){ gj="PROC"; }else if(gj == "취부"){ gj="ASSE"; }else if(gj == "용접"){ gj="WELD"; }else if(gj == "사상"){ gj="MRO"; };

		formData.append('porno['+i+']',porno)
		formData.append('gj['+i+']',gj)
	});
	formData.append("state","update");
	formData.append("date",$("input[name=wrkdate]").val());


	$.ajax({
		url:"<?php echo base_url('TSK/porr_form')?>",
		type : "post",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		dataType : "html",
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



// tr클릭시 체크 설정
$(document).off("click",".register_form table tr");
$(document).on("click",".register_form table tr",function(){
	if($(this).find('td:first-child :checkbox').is(":checked") == true) {
		$(this).find('td:first-child :checkbox').prop("checked", false);
	}else{
		$(this).find('td:first-child :checkbox').prop("checked", true);
	};
	
});



//달력
$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});


//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>