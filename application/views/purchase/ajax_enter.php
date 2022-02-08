<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm">
			<label for="sdate">발주등록일</label>
				<input type="text" name="sdate" class="sdate calendar"
					value="<?= (!empty($str['sdate']) && $str['sdate'] != "")?$str['sdate']:date("Y-m-d",strtotime("-1 month", time()));?>"
					size="12" /> ~

				<input type="text" name="edate" class="edate calendar"
					value="<?= (!empty($str['edate']) && $str['edate'] != "")?$str['edate']:date("Y-m-d");?>"
					size="12" />
			
			<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<!-- <span class="btn add add_head"><i class="material-icons">add</i>추가</span> -->
</header>

<div id="cocdHead" class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th style="width:5%;">No</th>
				<th style="width:10%;">발주등록일</th>
				<th style="width:8%;">발주 수량</th>
				<th style="width:8%;">단위</th>
				<th style="width:8%;">입고 예정일</th>
				<th style="width:20%;">비고</th>
				<th style="width:8%;">입고 수량</th>
				<th style="width:20%;">입고 특이사항</th>
				<th style="width:8%;">입고일</th>
				<th style="width:5%;"></th>
			</tr>
		</thead>
		<tbody>

			<?php
			foreach ($list as $i => $row) {
                $num = $i+1;
			?>
				<tr class="pocbox">
					<td class="cen"><?=$num?></td>
					<td class="cen"><?= $row->ACT_DATE ?></td>
					<td class="right"><?= number_format($row->QTY) ?></td>
					<td class="cen"><?= $row->UNIT ?></td>
					<td class="cen"><?= $row->DEL_DATE ?></td>
					<td><?= $row->REMARK ?></td>
					<td><input type="number" min="0" name="QTY" id="QTY" value="<?= $row->QTY ?>" class="form_input input_100" style="width:100%;"></td>	
					<td><input type="text" name="REMARK" id="REMARK" value="" class="form_input input_100" style="width:100%;"></td>
					<td><input type="text" name="EDATE" class="calendar" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>"
						style="border: 1px solid #ddd; padding: 5px 7px; width:100%; background: white;" ></td>
					<td class="cen">
						<span type="button" class="endBtn btn" data-idx="<?= $row->IDX ?>">완료</span>
					</td>
				</tr>

			<?php
			}
			?>
		</tbody>
	</table>
</div>


<script>
//제이쿼리 수신일 입력창 누르면 달력 출력
$(".calendar").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});

$(".endBtn").on("click", function() {
	var formData = new FormData();
	formData.append("IDX", $(this).data("idx"));
	formData.append("QTY", $("input[name='QTY']").val());
	formData.append("REMARK", $("input[name='REMARK']").val());

	if($("input[name='QTY']").val() == ""){
		alert("입고 수량 입력하세요.");
		return false;
	}

	if (confirm('입고하시겠습니까?') !== false) {

		$.ajax({
			url: "<?php echo base_url('PURCHASE/end_component') ?>",
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
	}
});
</script>