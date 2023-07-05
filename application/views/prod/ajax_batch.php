<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<header>
	<div class="searchDiv">
		<form id="headForm" onsubmit="return false">
		<label>배치 시작일</label>
			<input type="date" name="sdate" value="<?= $str['sdate']; ?>" class="" /> ~
			<input type="date" name="edate" value="<?= $str['edate']; ?>" class="" />

			<button class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<!-- <span class="btn print add_order"  style="padding:7px 11px;"><i class="material-icons">add</i>작업지시 등록</span> -->
	<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
</header>

<button class="search_submit batch_start">배치시작</button>
<div class="bdcont_100">
	<div class="">
		<header>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
                        <th>NO</th>
						<th>배치 넘버</th>
						<th>배치 시작</th>
						<th>배치 종료</th>
						<th>배치 종료</th>
						<th>등록일</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($list as $i => $row) {
						$no = $i + 1;
					?>
						<tr>
							<td class="cen"><?= $no; ?></td>
							<td class="cen"><?= $row->BATCH_NUM; ?></td>
							<td class="cen"><?= $row->START_DATE; ?></td>
                            <td class="cen"><?= $row->FINISH_DATE; ?></td>			
							<td class="pHide cen"><button type="button" class="pord endbatch" data-idx="<?= $row->IDX; ?>">종료</button></td>
                            <td class="cen"><?= $row->INSERT_DATE; ?></td>			
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
	$(".batch_start").click(function () { 
		$.post("<?=base_url('PROD/batch_start')?>", 
			function (data, textStatus, jqXHR) {
				console.log(data);
			},
			"html"
		);
	});

 
	$(".endbatch").on("click", function() {
		var idx = $(this).data("idx");

		if (confirm('배치를 종료 하시겠습니까??') !== false) {

			$.get("<?= base_url('PROD/batch_end') ?>", {
				idx: idx
			}, function(data) {
				location.reload();
			}, "JSON").done(function(jqXHR) {
				location.reload();
			})
		}
	});

</script>