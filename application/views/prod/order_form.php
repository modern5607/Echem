<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<link rel="stylesheet" href="../_static/prettydropdowns/dist/css/prettydropdowns.css?ver=1">
<!-- <script src="//code.jquery.com/jquery.min.js"></script> -->
<script src="../_static/prettydropdowns/dist/js/jquery.prettydropdowns.js?ver=1"></script>

<style>





</style>
<h2>
	지시등록<span class="material-icons close">clear</span>
</h2>


<div class="formContainer">

	<form name="itemform" id="itemform">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id"><span class="res"></span>수주일자 </label></th>
							<td>
								<!-- <input type="text" name="ACT_DATE" id="ACT_DATE" value="<?= isset($List->ACT_DATE) ? $List->ACT_DATE : ""; ?>" class="form_input calendar"> -->
								<select name="ACT" id="ACT">
									<option value="">선택</option>
									<?php foreach ($ACT as $i => $row) { ?>
										<option value="<?= $row->IDX ?>"><?= $row->ACT_DATE . " / " . $row->ACT_NAME ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>
						<!-- <tr>
							<th><label class="l_pw">수주명</label></th>
							<td><input type="text" disabled name="ACT_NAME" id="ACT_NAME" value="" class="form_input"></td>
						</tr> -->
						<tr>
							<th><label class="l_pw">작업일자</label></th>
							<td><input type="text" name="ORDER_DATE" id="ORDER_DATE" value="" class="form_input calendar"></td>
						</tr>
						<tr>
							<th><label class="l_pw">품목</label></th>
							<td>
								<select name="COMPONENT" id="COMPONENT" style="padding:7px 8px; border:1px solid #ddd;">
									<?php foreach ($COMPONENT as $i => $row) { ?>
										<option value="<?= $row->IDX ?>"><?= $row->COMPONENT_NAME ?></option>
									<?php } ?>
								</select>

							</td>
						</tr>
						<tr>
							<th><label class="l_pw">지시량</label></th>
							<td><input type="text" name="ORDER_QTY" id="ORDER_QTY" value="" class="form_input"></td>
						</tr>

					</tbody>
				</table>
			</fieldset>

			<div class="bcont">
				<span id="loading"><img src='<?= base_url('_static/img/loader.gif'); ?>' width="100"></span>

				<button type="button" class="submitBtn blue_btn">등록</button>

			</div>

		</div>

	</form>

</div>


<script>
	$("input").attr("autocomplete", "off");

	$(document).ready(function() {
		$("#ACT").prettyDropdown({
			classic: true,
			customClass: 'arrow',
			width: "38%",
			height: 31.5,
			hoverIntent: 200,
			selectedMarker: '&#10003;',
			afterLoad: function() {}
		});

		$("#COMPONENT").prettyDropdown({
			classic: true,
			customClass: 'arrow',
			width: "38%",
			height: 31.5,
			hoverIntent: 200,
			selectedMarker: '&#10003;',
			afterLoad: function() {}
		});

	});
	$(".submitBtn").on("click", function() {
		var $this = $(this);
		var formData = new FormData($("#itemform")[0]);

		var ACT = $("#ACT");
		var ORDER_DATE = $("#ORDER_DATE");
		var COMPONENT = $("#COMPONENT");
		var ORDER_QTY = $("#ORDER_QTY");

		if (ACT.val() == "") {
			alert("수주일을 선택해 주세요");
			// act.focus()
			return;
		}
		if (ORDER_DATE.val() == "") {
			alert("작업일자를 선택해 주세요");
			// act.focus()
			return;
		}
		if (COMPONENT.val() == "") {
			alert("품목을 선택해 주세요");
			// act.focus()
			return;
		}
		if (ORDER_QTY.val() == "") {
			alert("지시량을 입력해 주세요");
			// act.focus()
			return;
		}
		for (var i of formData.entries())
			console.log(i[0] +" "+ i[1]);

		$.ajax({
			url: "<?= base_url('PROD/add_order') ?>",
			type: "POST",
			data: formData,
			//asynsc : true,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$this.hide();
				$("#loading").show();
			},
			success: function(data) {
				if (data == 1) {
					// alert("등록되었습니다.");
					// $(".ajaxContent").html('');
					// $("#pop_container").fadeOut();
					// $(".info_content").css("top", "-50%");
					// $("#loading").hide();
					// load();

				}
				// 	setTimeout(function() {
				// 		alert(jsonData.msg);


				// 	}, 1000);

				// 	chkHeadCode = false;

				// }
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});
	});

	$(document).on("click", "h2 > span.close", function() {
		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top", "-50%");
		// location.reload();

	});

	// $("#ACT").change(function() {
	// 	var idx = this.value;
	// 	console.log(idx);
	// 	$.ajax({
	// 		type: "post",
	// 		url: "<?= base_url("PROD/act_idx") ?>",
	// 		data: {
	// 			idx: idx
	// 		},
	// 		dataType: "json",
	// 		success: function(data) {

	// 			if (data.status == "ok")
	// 				$("#ACT_NAME").val(data.info.ACT_NAME);
	// 			else
	// 				alert("해당 정보가 없습니다");
	// 		}
	// 	});
	// });

	//제이쿼리 수신일 입력창 누르면 달력 출력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>