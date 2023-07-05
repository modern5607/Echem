<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="bdcont_100">

	<div class="">

		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					<label>품명</label>
					<input type="text" name="ITEM_NAME" value="<?= $str['ITEM_NAME']; ?>" />
					<label>사용유무</label>
							<select name="USEYN" id="USEYN" style="padding:4px 10px; border:1px solid #ddd;">
								<option value="" <?= ($str['USEYN'] == '')?'selected':'' ?>>전체</option>
								<option value="Y" <?= ($str['USEYN'] == 'Y')?'selected':'' ?>>사용</option>
								<option value="N" <?= ($str['USEYN'] == 'N')?'selected':'' ?>>미사용</option>
							</select>
					<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
			<span class="btn add add_items" style="float:right;"><i class="material-icons">add</i>추가</span>

			<!-- <span class="btn print add_biz"><i class="material-icons">add</i>업체추가</span> -->
			<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
		</header>

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th style="width: 50px;">No</th>
						<th>품명</th>
						<th>단위</th>
						<th>현 재고</th>
						<th>재고 등록일</th>
						<th>최초 등록일</th>
						<th>등록 사용자</th>
						<th>비고</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (!empty($list)) {
						foreach ($list as $i => $row) {
							$no =$pageNum+ $i + 1;
					?>
							<tr>
								<td class="cen"><?= $no; ?></td>
								<td><?= $row->ITEM_NAME ?></td>
								<td class="cen"><?= $row->UNIT ?></td>
								<td class="right"><?= number_format($row->STOCK) ?></td>
								<td class="cen"><?= $row->UPDATE_DATE ?></td>
								<td class="cen"><?= $row->INSERT_DATE ?></td>
								<td class="cen"><?= $row->INSERT_ID ?></td>
								<td class="pHide cen" style="width: 70px;"><button type="button" class="mod item_mod" data-idx="<?= $row->IDX; ?>">수정</button></td>
							</tr>

						<?php
						} ?>

					<?php } else {
					?>
						<tr>
							<td colspan="15" class="list_none">정보가 없습니다.</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
	</div>


</div>


<div class="pagination">	
	<?php
	if($this->data['cut'] > 15){
	?>
	<div class="limitset">
		<select name="per_page">
			<option value="15" <?= ($perpage == 15)?"selected":"";?>>15</option>
			<option value="30" <?= ($perpage == 30)?"selected":"";?>>30</option>
			<option value="80" <?= ($perpage == 80)?"selected":"";?>>80</option>
			<option value="100" <?= ($perpage == 100)?"selected":"";?>>100</option>
		</select>
	</div>
	<?php
	}	
	?>
	<?= $this->data['pagenation'];?>
</div>

<div id="pop_container">

	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">

		</div>
	</div>

</div>

<script>
	$("input").attr("autocomplete", "off");

	$(".add_items").on("click", function() {

		$(".ajaxContent").html('');
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?= base_url('MDM/items_form') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				mode: "add"
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


	$(".item_mod").on("click", function() {
		var idx = $(this).data("idx");

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?= base_url('MDM/items_form') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				idx: idx,
				mode: "mod"
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
</script>