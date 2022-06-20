<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm">
			<label for="sdate">발주등록일</label>
				<input type="date" name="sdate" class="" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
				<input type="date" name="edate" class="" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />
			
			<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
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
					<td class="right"><?= round($row->QTY,2) ?></td>
					<td class="cen"><?= $row->UNIT ?></td>
					<td class="cen"><?= $row->DEL_DATE ?></td>
					<td><?= $row->REMARK ?></td>
					<td><input type="number" min="0" name="QTY" id="QTY" value="<?= round($row->QTY,2) ?>" class="form_input input_100" style="width:100%;"></td>	
					<td><input type="text" name="REMARK" id="REMARK" value="" class="form_input input_100" style="width:100%;"></td>
					<td><input type="text" name="EDATE" class="" size="15" autocomplete="off" value="<?= date("Y-m-d") ?>"
						style="border: 1px solid #ddd; padding: 5px 7px; width:100%; background: white;" ></td>
					<td class="cen">
						<?php if($row->END_YN == "N"){?>
						<span type="button" class="endBtn btn" data-idx="<?= $row->IDX ?>">완료</span>
						<?php }?>
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
	formData.append("QTY", $(this).parents("tr").find("input[name='QTY']").val());
	formData.append("REMARK", $(this).parents("tr").find("input[name='REMARK']").val());


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