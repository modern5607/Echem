<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm" onsubmit="return false">
			<label>일자</label>
			<input type="date" name="sdate" class="" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="date" name="edate" class="" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
			<label>수주명</label>
			<input type="text" name="actnm" class="" size="11" value="<?= $str['actnm'] ?>">

			<label for="biz">거래처</label>
			<select name="biz" id="biz" style="padding:4px 10px; border:1px solid #ddd;">
				<option value="">거래처</option>
				<?php foreach ($BIZ as $row) { ?>
					<option value="<?php echo $row->IDX ?>" <?php echo ($str['biz'] == $row->IDX) ? "selected" : ""; ?>><?php echo $row->CUST_NM; ?></option>
				<?php } ?>
			</select>

			<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header>

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width: 5%">No</th>
				<th style="width: 8%">수주일</th>
				<th style="width: 15%">수주명</th>
				<th style="width: 10%">거래처</th>
				<th style="width: 6%">수주량</th>
				<th style="width: 8%">납기예정일</th>
				<th style="width: 8%">배송방법</th>
				<th style="width: 8%">납기일</th>
				<th style="width: 6%">납품량</th>
				<th>납품세부사항</th>
				<th style="width: 5%"></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($list)) {
				foreach ($list as $i => $row) {
					$no = $pageNum + $i + 1;
			?>

					<tr>
						<td class="cen"><?php echo $no; ?></td>
						<td class="cen"><?= $row->ACT_DATE ?></td>
						<td><?= $row->ACT_NAME ?></td>
						<td><?= $row->CUST_NM ?></td>
						<td class="right"><?= round($row->QTY, 2) ?></td>
						<td class="cen"><?= $row->DEL_DATE ?></td>
						<td>
							<select name="SHIP" id="SHIP" class="form_select input_100">
								<?php foreach ($SHIP as $row2) { ?>
									<option value="<?php echo $row2->D_NAME ?>"><?php echo $row2->D_NAME; ?></option>
								<?php } ?>
							</select>
						</td>
						<td><input type="text" name="EDATE" class="calendar" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>" style="border: 1px solid #ddd; padding: 4px 7px; width:100%; background: white;"></td>
						<td><input type="number" min="0" name="BQTY" id="BQTY" value="<?= round($row->QTY, 2) ?>" class="input_100"></td>
						<td><input type="text" name="REMARK" id="REMARK" value="" class="input_100"></td>
						<td class="cen"><span type="button" class="submitBtn btn" data-idx="<?php echo $row->IDX; ?>">출고</span></td>
					</tr>

				<?php
				}
			} else {
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

<script>
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});

	$(".submitBtn").on("click", function() {

		var formData = new FormData();
		formData.append("SHIP", $(this).parents("tr").find("select[name='SHIP']").val());
		formData.append("EDATE", $(this).parents("tr").find("input[name='EDATE']").val());
		formData.append("BQTY", $(this).parents("tr").find("input[name='BQTY']").val());
		formData.append("REMARK", $(this).parents("tr").find("input[name='REMARK']").val());
		formData.append("IDX", $(this).data('idx'));

		var formData2 = new FormData();
		formData2.append("IDX", '7');
		formData2.append("KIND", 'OT');
		formData2.append("BIZ", $(this).parents("tr").find("td:eq(3)").text());
		formData2.append("QTY", $(this).parents("tr").find("input[name='BQTY']").val());
		formData2.append("DATE", $(this).parents("tr").find("input[name='EDATE']").val());
		formData2.append("REMARK", $(this).parents("tr").find("input[name='REMARK']").val());

		// FormData의 값 확인
		for (var pair of formData2.entries()) {
			console.log(pair[0] + ', ' + pair[1]);
		}

		if ($("input[name='BQTY']").val() == "") {
			alert("출고수량 입력하세요.");
			$("input[name='BQTY']").focus();
			return false;
		}
		// return false;

		$.ajax({
			url: "<?php echo base_url('STOCK/release_update') ?>",
			type: "POST",
			data: formData,
			//asynsc : true,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				location.reload();
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});

		$.ajax({
			url: "<?php echo base_url('STOCK/stock_update') ?>",
			type: "POST",
			data: formData2,
			//asynsc : true,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				location.reload();
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});
	});
</script>