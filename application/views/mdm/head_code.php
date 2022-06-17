<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="headForm">
			<label>code</label>
			<input type="text" name="h_code" value="<?= $str['code'] ?>" />
			<label>name</label>
			<input type="text" name="h_name" value="<?= $str['name'] ?>" />
			<label>사용유무</label>
			<select name="h_use" style="padding:4px 10px; border:1px solid #ddd;">
				<option value="">전체</option>
				<option value="Y" <?= ($str['use'] == "Y") ? "selected" : ""; ?>>사용</option>
				<option value="N" <?= ($str['use'] == "N") ? "selected" : ""; ?>>미사용</option>
			</select>
			<button class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<span class="btn add add_head"><i class="material-icons">add</i>추가</span>
</header>

<div id="cocdHead" class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th>code</th>
				<th>name</th>
				<th>사용유무</th>
				<th>비고</th>
				<th class="pHide"></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($List as $i => $row) {
			?>

				<tr class="pocbox">
					<td><?= $row->CODE; ?></td>
					<td data-idx="<?= $row->IDX ?>" class="link_s1"><?= $row->NAME; ?></a></td>
					<td class="cen"><?= ($row->USE_YN == "Y") ? "사용" : "미사용"; ?></td>
					<td><?= $row->REMARK; ?></td>
					<td class="pHide cen"><button type="button" class="mod mod_head" data-idx="<?= $row->IDX; ?>">수정</button></td>
				</tr>

			<?php
			}
			?>
		</tbody>
	</table>
</div>

<script>
	$(document).off("click", ".link_s1");
	$(document).on("click", ".link_s1", function() {

		var hidx = $(this).data("idx");
		console.log(hidx);

		$(".pocbox").removeClass("over");
		$(this).parent().addClass("over");

		$.ajax({
			url: "<?= base_url('MDM/detail_code') ?>",
			type: "post",
			data: {
				hidx: hidx
			},
			dataType: "html",
			success: function(data) {
				$("#ajax_detail_container").empty();
				$("#ajax_detail_container").html(data);
			}
		});
	});

	$(".print_head").on("click",function(){
		$(".pHide").hide();
		PrintElem("#cocdHead");
	});

	$(".mod_head").on("click", function() {

		var idx = $(this).data("idx");

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?php echo base_url('MDM/ajax_cocdHead_form') ?>",
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
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});

	});

	$(".add_head").on("click", function() {

		$(".ajaxContent").html('');

		$("#pop_container").fadeIn();
		$(".info_content").animate({
			top: "50%"
		}, 500);

		$.ajax({
			url: "<?php echo base_url('MDM/ajax_cocdHead_form') ?>",
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


	//input autocoamplete off
	$("input").attr("autocomplete", "off");
</script>