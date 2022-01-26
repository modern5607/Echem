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
						<th class="" style="width: 80px;">날자</th>
						<th class="cen" style="width: 80px;">수주정보</th>
						<th>수량</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($List)) {
						foreach ($List as $row) { ?>
							<tr>
								<td class="cen"><input type="text"></td>
								<td class="cen"><input type="text"></td>
								<td class="cen"><input type="text"></td>
							</tr>
					<?php }
					} ?>


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
