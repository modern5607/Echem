<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<div class="bdcont_100">

	<div class="">

		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					<label>업체명</label>
					<input type="text" name="custnm" value="<?= $str['custnm']; ?>" />
					<label>주소</label>
					<input type="text" name="address" value="<?= $str['address']; ?>" />
					<label>사용유무</label>
					<select name="useyn" id="useyn" style="padding:4px 10px; border:1px solid #ddd;">
						<option value="A" <?= ($str['useyn'] == 'A') ? 'selected' : '' ?>>전체</option>
						<option value="Y" <?= ($str['useyn'] == 'Y') ? 'selected' : '' ?>>사용</option>
						<option value="N" <?= ($str['useyn'] == 'N') ? 'selected' : '' ?>>미사용</option>
					</select>
					<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
			<span class="btn print add_biz"><i class="material-icons">add</i>업체추가</span>
			<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
		</header>

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>순번</th>
						<th>업체명</th>
						<th>주소</th>
						<th>연락처</th>
						<th>담당자</th>
						<th>주거래품목</th>
						<th>거래구분</th>
						<th>사용여부</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (!empty($bizList)) {
						foreach ($bizList as $i => $row) {
							$no = $pageNum + $i + 1;
					?>

							<tr>
								<td class="cen"><?= $no; ?></td>
								<td><span class="mod_biz  link_s1" data-idx="<?= $row->IDX; ?>"><?= $row->CUST_NM; ?></span></td>
								<td><?= $row->ADDRESS; ?></td>
								<td><?= $row->TEL; ?></td>
								<td><?= $row->CUST_NAME; ?></td>
								<td><?= $row->ITEM; ?></td>
								<td class="cen"><?= $row->CUST_TYPE; ?></td>
								<td class="cen"><?= ($row->USE_YN == "Y") ? "사용" : "미사용"; ?></td>
							</tr>

						<?php
						}
					} else {
						?>
						<tr>
							<td colspan="15" class="list_none">업체정보가 없습니다.</td>
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
	//추가
	$(document).off("click", ".add_biz");
	$(".add_biz").on("click", function() {

		$(".ajaxContent").html('');
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?= base_url('/MDM/biz_form') ?>",
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
		});

	});

	//수정
	$(document).off("click", ".mod_biz");
	$(".mod_biz").on("click", function() {
		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		var idx = $(this).data("idx");
		$.ajax({
			url: "<?= base_url('/MDM/biz_form') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				mode: "mod",
				IDX: idx
			},
			success: function(data) {
				$(".ajaxContent").html(data);
			},
			error: function(xhr, textStatus, errorThrown) {
				// alert(xhr);
				alert(textStatus);
				// alert(errorThrown);
			}
		});

	});

	//팝업닫기
	$(document).off("click", "h2 > span.close");
	$(document).on("click", "h2 > span.close", function() {
		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top", "-50%");
	});

	//input autocoamplete off
	$("input").attr("autocomplete", "off");

	//달력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>