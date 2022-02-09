<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<link href="<?= base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">
<script src="<?= base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?= base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>



<div class="bdcont_100">
	<div class="">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					<label>수주일</label>
					<input type="text" name="sdate" value="<?= $str['sdate']; ?>" class="calendar"  /> ~ 
					<input type="text" name="edate" value="<?= $str['edate']; ?>" class="calendar" />
					
					<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
			<span class="btn print add_order"  style="padding:7px 11px;"><i class="material-icons">add</i>작업지시 등록</span>
			<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>NO</th>
						<th>COL1</th>
						<th>COL2</th>
						<th>COL3</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($list as $i => $row) {
						$no = $i + 1;
					?>
						<tr>
							<td class="cen"><?= $no; ?></td>
							<td class="cen"><?= $row->COL1; ?></td>
							<td class="cen"><?= $row->COL2; ?></td>
							<td class="cen"><?= $row->COL3; ?></td>
						</tr>


					<?php
					}
					?>
				</tbody>
			</table>
		</div>


	</div>
</div>



<div id="pop_container">

	<div id="info_content" class="info_content" style="height:auto;">

		<div class="ajaxContent">


		</div>

	</div>

</div>


<script>
$("input").attr("autocomplete", "off");

$(".add_order").on("click", function() {

	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top: "50%"
	}, 500);

	$.ajax({
		url: "<?= base_url('PROD/order_form') ?>",
		type: "POST",
		dataType: "HTML",
		data: {
		},
		success: function(data) {
			$(".ajaxContent").html(data);
		},
		error: function(xhr, textStatus, errorThrown) {
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	})

});


//제이쿼리 수신일 입력창 누르면 달력 출력
$(".calendar").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});
</script>
