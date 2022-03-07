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
						<th>날자</th>
						<th>생산예정량</th>
						<th>비고</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td class="cen"><input name="date" type="text" class="calendar" value="<?= $setDate ?>"></td>
						<td class="cen"><input name="qty" type="text" value="<?= !empty($List)?round($List[0]->QTY,2):'' ?>"></td>
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
    var date = $("input[name='date']").val();
    var qty = $("input[name='qty']").val();
    var remark = $("input[name='remark']").val();

    // console.log(year + month);

    $.ajax({
        type: "post",
        url: "<?= base_url('ORDPLN/calendar_update')?>",
        data: {
            remark:remark,
            qty:qty,
            date:date,
			gb: 'ORD'
        },
        dataType: "html",
        success: function (data) {
			location.reload();
        }
    });

});

</script>