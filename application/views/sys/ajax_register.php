<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<div class="bdcont_100">
	<div class="">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					<label for="mid">아이디</label>
					<input type="text" name="mid" id="mid" value="<?= $str['mid'] ?>" size="6" />

					<label for="mname">이름</label>
					<input type="mname" name="mname" id="mname" value="<?= $str['mname'] ?>" size="6" />

					<label for="level">권한</label>
					<select name="level" style="padding:3px 10px; border:1px solid #ddd;">
						<option value="">전체</option>
						<?php for ($i = 1; $i <= 3; $i++) { ?>
							<option value="<?= $i ?>" <?= ($str['level'] == $i) ? "selected" : ""; ?>><?= $i ?></option>
						<?php } ?>
					</select>

					<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>

					<span class="btn print add_member"><i class="material-icons">add</i>신규등록</span>
				</form>

			</div>

		</header>

		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>아이디</th>
						<th>권한</th>
						<th>이름</th>
						<th>부서</th>
						<th>직급</th>
						<th>전화</th>
						<th>휴대폰</th>
						<th>이메일</th>
						<th>입사일</th>
						<th>상태</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($List as $i => $row) { ?>
						<tr>
							<td><?= $row->ID; ?></td>
							<td class="cen"><?= $row->LEVEL; ?></td>
							<td class="cen"><?= $row->NAME; ?></td>
							<td class="cen"><?= $row->PART; ?></td>
							<td class="cen"><?= $row->GRADE; ?></td>
							<td class="cen"><?= $row->TEL; ?></td>
							<td class="cen"><?= $row->HP; ?></td>
							<td><?= $row->EMAIL; ?></td>
							<td class="cen"><?= $row->FIRSTDAY; ?></td>
							<td class="cen"><?= ($row->STATE == 1) ? "사용" : "사용안함"; ?></td>
							<td class="cen">
								<span class="mod register_update" data-idx="<?= $row->IDX; ?>">수정</span>
							</td>
						</tr>


					<?php
					}
					if (empty($List)) {
					?>

						<tr>
							<td colspan="15" class="list_none">회원정보가 없습니다.</td>
						</tr>

					<?php
					}
					?>
				</tbody>
			</table>
		</div>

		<div class="pagination">
			<?php
			if ($this->data['cut'] > 20) {
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

	</div>
</div>
<div id="pop_container">

	<div id="info_content" class="info_content" style="height:unset;">

		<div class="ajaxContent"></div>

	</div>

</div>

<script>
	

	$(".add_member").on("click", function() {

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?= base_url('SYS/memberinfo_form') ?>",
			type: "post",
			dataType: "html",
			success: function(data) {
				$(".ajaxContent").html(data);
			}
		});


	});

	$(document).on("click", "h2 > span.close", function() {

		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top", "-50%");
		location.reload();

	});

	$(".register_update").on("click", function() {
		var idx = $(this).data("idx");

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		modchk = true;

		$.ajax({
			url: "<?= base_url('SYS/memberinfo_form') ?>",
			type: "post",
			data: {
				idx: idx
			},
			dataType: "html",
			success: function(data) {
				$(".ajaxContent").html(data);
			}

		});
	});
</script>