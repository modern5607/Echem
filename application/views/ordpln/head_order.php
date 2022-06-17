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
		<form id="headForm" onsubmit="return false">
			<label>수주일자</label>
			<input type="date" name="sdate" class="" size="11" value="<?= $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="date" name="edate" class="" size="11" value="<?= $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
			<label>수주명</label>
			<input type="text" name="actnm" class="" size="11" value="<?= $str['actnm']?>">

			<button class="search_submit head_search"><i class="material-icons">search</i></button>

			<input type="button" value="선택해제" class="link_s1" data-idx="r" style="position:absolute; right:20px; color:#333;">
		</form>
	</div>
</header>
<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width:8%">NO</th>
				<th style="width:20%">수주일</th>
				<th style="width:30%">수주명</th>
				<th style="width:12%">수량(T)</th>
				<th style="width:20%">거래처</th>
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
						<td class="cen"><?= $row->ACT_DATE ?></td>
						<td class="link_s1" data-idx="<?= $row->IDX ?>"><?= $row->ACT_NAME ?></td>
						<td class="right"><?= round($row->QTY,2) ?></td>
						<td><?= $row->CUST_NM ?></td>
					</tr>
				<?php
				}
			} else {
				?>
				<tr>
					<td colspan="15" class="list_none">납기변경내역이 없습니다.</td>
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
				<option value="20" <?= ($perpage == 20) ? "selected" : ""; ?>>20</option>
				<option value="50" <?= ($perpage == 50) ? "selected" : ""; ?>>50</option>
				<option value="80" <?= ($perpage == 80) ? "selected" : ""; ?>>80</option>
				<option value="100" <?= ($perpage == 100) ? "selected" : ""; ?>>100</option>
			</select>
		</div>
	<?php
	}
	?>
	<?= $this->data['pagenation']; ?>
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
		detailData = new FormData();
		detailData.append('idx', idx);

		$.ajax({
			url: "<?= base_url('/ORDPLN/detail_order/') ?>",
			type: "post",
			data: detailData,
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
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