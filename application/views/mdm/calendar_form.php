<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<h2>
	<?php echo $title; ?>
	<span class="material-icons close">clear</span>
</h2>

<div class="formContainer">
	<div class="register_form">
		<fieldset class="form_2" >
			<table class="nhover" style="width:100%">
				<thead>
					<tr>
						<th class="" style="width: 80px;">POR No</th>
						<th class="cen" style="width: 45px;">SEQ</th>
						<th>품명</th>
						<th style="width: 45px;">공정</th>
					</tr>
				</thead>
				<tbody>
					<!-- <tr style="line-height:42px">
						<th>날짜<span class="re"></span></th>
						<td>
							<?php echo $setDate; ?>
							<input type="hidden" name="WORK_DATE" value="<?php echo $setDate; ?>">
						</td>
					</tr> -->

					<?php if (!empty($List)) {
						foreach ($List as $row) { ?>
							<tr>
								<td class="cen"><?= $row->POR_NO ?></td>
								<td class="cen"><?= $row->POR_SEQ ?></td>
								<td class=""><?= $row->MCCSDESC ?></td>
								<td class="cen"><?= $row->GJ ?></td>
							</tr>
					<?php }
					} ?>


				</tbody>
			</table>
		</fieldset>

		<!-- <div class="bcont">
			<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></span>
			<button type="button" class="submitBtn blue_btn"> 등록 </button>
		</div> -->

	</div>

	</form>

</div>



<script type="text/javascript">
	// $("input").attr("autocomplete", "off");

	// $(".submitBtn").on("click",function(){

	// 	var formData = new FormData($("#ajaxform")[0]);
	// 	var $this = $(this);		

	// 	$.ajax({
	// 		url  : "<?php echo base_url('MDM/calendar_up') ?>",
	// 		type : "POST",
	// 		data : formData,
	// 		//asynsc : true,
	// 		cache  : false,
	// 		contentType : false,
	// 		processData : false,
	// 		beforeSend  : function(){
	// 			$this.hide();
	// 			$("#loading").show();
	// 		},
	// 		success : function(data){

	// 			var jsonData = JSON.parse(data);
	// 			if(jsonData.status == "ok"){

	// 				setTimeout(function(){
	// 					alert(jsonData.msg);
	// 					$(".ajaxContent").html('');
	// 					$("#pop_container").fadeOut();
	// 					$(".info_content").css("top","-50%");
	// 					$("#loading").hide();
	// 					location.reload();

	// 				},1000);

	// 				chkHeadCode = false;

	// 			}
	// 		},
	// 		error   : function(xhr,textStatus,errorThrown){
	// 			alert(xhr);
	// 			alert(textStatus);
	// 			alert(errorThrown);
	// 		}
	// 	});
	// });









	// $("input[name='TRANS_DATE']").datetimepicker({
	// 	format:'Y-m-d',
	// 	timepicker:false,
	// 	lang:'ko-KR'
	// });





	//-->
</script>