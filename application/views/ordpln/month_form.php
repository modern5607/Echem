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
						<th>연차일</th>
						<th>작업자</th>
						<th>사유</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="cen"><input name="VACATION_DATE" type="input" class="calendar" value="<?= $setDate ?>"></td>
						<td class="cen"><input name="MEMBER_IDX" type="text" value="<?= !empty($List)?$List[0]->MEMBER_IDX:'' ?>"></td>
						<td class="cen"><input name="REMARK" type="text" value="<?= !empty($List)?$List[0]->REMARK:'' ?>"></td>
					</tr>
				</tbody>
			</table>
		</fieldset>

		<div class="bcont">
			<span id="loading"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></span>
			<button type="button" class="submitBtn blue_btn"> 등록 </button>
		</div>

	</div>

	</form>

</div>

<script>
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});


	$(".submitBtn").click(function (){

		var formData = new FormData();
    formData.append("VACATION_DATE", $("input[name='VACATION_DATE']").val());
    formData.append("MEMBER_IDX", $("select[name='MEMBER_IDX']").val());
    formData.append("REMARK", $("textarea[name='REMARK']").val());


    // console.log(year + month);

    $.ajax({
        type: "post",
        url: "<?= base_url('ORDPLN/month_update')?>",
        data: formData,
        dataType: "html",
        success: function (data) {
			location.reload();
        }
    });

});

</script>