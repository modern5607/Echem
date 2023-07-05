<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- 주문등록 헤더 복사 뷰 -->

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
			<label>등록일</label>
			<input type="date" name="sdate" class="" size="11" value="<?= $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="date" name="edate" class="" size="11" value="<?= $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
			<label>수주명</label>
			<input type="text" name="remark" class="" size="11" value="<?= $str['remark']?>">

			<label for="biz">거래처</label>
			<select name="biz" id="biz" style="padding:4px 10px; border:1px solid #ddd;">
				<option value="">전체</option>
				<?php foreach ($BIZ as $row) { ?>
					<option value="<?= $row->IDX ?>" <?= ($str['biz'] == $row->IDX) ? "selected" : ""; ?>><?= $row->CUST_NM; ?></option>
				<?php } ?>
			</select>

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
				<!-- <th style="width:20%">등록일</th> -->
				<th style="width:30%">생산정보</th>
				<th style="width:12%">생산량(Kg)</th>
				<!-- <th style="width:20%">투입원료</th>  -->
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
						<!-- <td class="cen"><?= $row->INSERT_DATE ?></td> -->
						<td class="link_s1 cen" data-idx="<?= $row->IDX ?>"><?= $row->REMARK ?></td>
						 <td class="cen"><?= round($row->OT_OUT,2) ?></td>
						<!-- <td><?= $row->CUST_NM ?></td> -->
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
			url: "<?= base_url('/ORDPLN/detail_order1/') ?>",
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