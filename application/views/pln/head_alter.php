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
	}

	;
</style>

<header>
	<div class="searchDiv">
		<form id="headForm">
			<label>일자</label>
			<input type="text" name="sdate" class="calendar" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="text" name="edate" class="calendar" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
			<label>수주구분</label>
			<select name="sjgb" id="sjgb" class="searchSelect">
				<option value="">전체</option>
				<option value="R" <?= ($str['sjgb'] == 'R') ? 'selected' : '' ?>>정규</option>
				<option value="G" <?= ($str['sjgb'] == 'G') ? 'selected' : '' ?>>돌발</option>
			</select>
			<button class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header>

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th>변경일자</th>
				<th>건수</th>
				<th>총 중량</th>
				<th>수량</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($List)) {
				foreach ($List as $i => $row) {
					$no = $pageNum + $i + 1;
			?>
					<tr class="headLink" data-idx="S987TL2">
						<td class="link_s1">1</td>
						<td>2</td>
						<td></td>
						<td></td>
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

<div class="pagination">
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
		var idx = $(this).data("idx");
		console.log(idx);

		$(".headLink").removeClass("over");
		$(this).parent().addClass("over");

		detailData = new FormData($("#detailForm")[0]);

		detailData.append('idx', idx);
		detailData.append('pageNum', datailpage);
		if (dataillimit != 0) {
			detailData.append('perpage', dataillimit);
		}

		$.ajax({
			url: "<?= base_url('/PLN/detail_alter/') ?>",
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