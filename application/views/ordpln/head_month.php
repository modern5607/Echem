<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
	header {
		width: 870px;
		position: relative;
		z-index: 10;
	}

	@media screen and (min-width: 1200px) {
		header {
			width: calc(100vw - 330px);
		}
	};
</style>

<header>
	<div class="searchDiv">
		<form id="headForm">
			<label>일자</label>
			<input type="date" name="sdate" class="" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="date" name="edate" class="" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />

			<button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>

			<input type="button" value="선택해제" class="link_s1" data-idx="r" style="position:absolute; right:20px; color:#333;">
		</form>
	</div>
</header>
<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width:8%">NO</th>
				<th style="width:30%">월차등록일</th>
				<th style="width:20%">작업자</th>
				<th style="width:42%">월차사유</th>
				
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($list)) {
				foreach ($list as $i => $row) {
					$no = $pageNum + $i + 1;
			?>
					<tr class="headLink">
						<td class="cen"><?= $no ?></td>
						<td class="link_s1 cen" data-idx="<?= $row->IDX ?>"><?= $row->VACATION_DATE ?></td>
						<td><?= $row->NAME ?></td>
						<td><?= $row->REMARK ?></td>
						
						
					</tr>
				<?php
				}
			} else {
				?>
				<tr>
					<td colspan="15" class="list_none">근무일정이 없습니다.</td>
				</tr>
			<?php
			}
			?>
		</tbody>
	</table>
</div>

<div class="pagination2">
	<?php
	if ($this->data['cnt'] > 20) {
	?>
		<div class="limitset">
			<select name="per_page">
				<option value="20" <?php echo ($perpage == 20) ? "selected" : ""; ?>>20</option>
				<option value="50" <?php echo ($perpage == 50) ? "selected" : ""; ?>>50</option>
				<option value="80" <?php echo ($perpage == 80) ? "selected" : ""; ?>>80</option>
				<option value="100" <?php echo ($perpage == 100) ? "selected" : ""; ?>>100</option>
			</select>
		</div>
	<?php
	}
	?>
	<?php echo $this->data['pagenation']; ?>
</div>

<div id="pop_container">

	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">

			<!-- 데이터 -->

		</div>
	</div>

</div>



<script>
	$(document).off("click", ".link_s1");
	$(document).on("click", ".link_s1", function() {
		$(".headLink").removeClass("over");
		$(this).parent().addClass("over");

		var idx = $(this).data("idx");
		console.log(idx);

		$.ajax({
			url: "<?= base_url('/ORDPLN/detail_month/') ?>",
			type: "post",
			data: {
				idx:idx
			},
			dataType: "html",
			success: function(data) {
				$("#ajax_detail_container").html(data);
			}
		});
	});





	//달력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});


	//input autocoamplete off
	$("input").attr("autocomplete", "off");
</script>