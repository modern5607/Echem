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
						<th>휴가자</th>
						<th>사유</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="cen"><input name="vacatoin_date" type="text" class="calendar" value="<?= $setDate ?>"></td>
						<td class="cen"><input name="member_idx" type="text" value="<?= !empty($List)?round($List[0]->QTY,2):'' ?>"></td>
						<td class="cen"><input name="remark" type="text" value="<?= !empty($List)?$List[0]->REMARK:'' ?>"></td>
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
    var vacation_date = $("input[name='vacation_date']").val();
    var member_idx = $("input[name='member_idx']").val();
    var remark = $("input[name='remark']").val();

    // console.log(year + month);

    $.ajax({
        type: "post",
        url: "<?= base_url('ORDPLN/month_update')?>",
        data: {
            vacation_date:vacation_date,
            member_idx:member_idx,
            remark:remark,
			gb: 'ORD'
        },
        dataType: "html",
        success: function (data) {
			location.reload();
        }
    });

});

</script>