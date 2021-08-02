<style>
.test-text{float: right; font-size: 14px; display:flex}
.check_submit{ padding: 2px 5px; cursor: pointer; background: #414350; color: #fff; margin:0 0 3px 10px;}
</style>
<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="headForm">
			<label for="mid">MP납기일</label>
				<input type="text" name="sdate" class="calendar" size="13" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
				<input type="text" name="edate" class="calendar" size="13" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> 
				
				<label for="mname">POR NO</label>
				<input type="text" name="porno" id="porno" value="<?php echo $str['porno']?>" size='10' />

				<label for="yn">상태</label>
				<select name="yn" id="yn" class="form_select">
					<option value="">전체</option>
					<option value="CHK" <?= ($str['yn'] == "CHK")?'selected':''?> >의뢰</option>
					<option value="N" <?= ($str['yn'] == "N")?'selected':''?> >미완</option>
					<option value="Y" <?= ($str['yn'] == "Y")?'selected':''?>>완료</option>
				</select>	

			<button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th>POR NO</th>
					<th>SEQ</th>
					<th>수량</th>
					<th>총 중량</th>
					<th>품명/재질/규격</th>
					<th>MP납기일</th>
					<th>의뢰업체</th>
					<th>상태</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if($this->data['cnt'] != 0){
			foreach($List as $i=>$row){ 
			if($row->POR_NO != "합계"){?>
			<tr style="<?= ($row->END_YN == "Y")?'background:#fce1e1':''; ?>" data-idx="<?= $row->IDX; ?>">
				<td class="cen"><?= $row->POR_NO; ?></td>
				<td class="cen"><?= $row->POR_SEQ; ?></td>
				<td class="right"><?= ($row->TRS_REMARK)?number_format($row->TRS_QTY):number_format($row->PO_QTY - $row->TRS_QTY); ?></td>
				<td class="right"><?= ($row->TRS_REMARK)?number_format($row->TRS_WEIGHT,3):number_format($row->PO_QTY * $row->WEIGHT,3); ?></td>
				<td><?= $row->MCCSDESC; ?></td>
				<td class="cen"><?= $row->PORRQDA; ?></td>
				<td><?= $row->TRS_REMARK; ?></td>
				<td class="cen"><?= ($row->END_YN == "N" && $row->TRS_REMARK =='')?'미완':(($row->END_YN == "Y")?'완료':'의뢰') ?></td>
			</tr>
	
			<?php
			}}}else{
			?>
				<tr>
					<td colspan="15" class="list_none">검색조건에 부합한 작업이 없습니다.</td>
				</tr>
			<?php
			}	
			?>
			</tbody>
		</table>
	</div>




<div id="pop_container">
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent"></div>
	</div>
</div>



<script>
// 달력
$(".calendar").datetimepicker({
	format:'Y-m-d',
	timepicker:false,
	lang:'ko-KR'
});

// tr클릭시 체크 설정
$(document).off("click",".tbl-content table tbody tr");
$(document).on("click",".tbl-content table tbody tr",function(){
    $(".xdsoft_datetimepicker").remove();
	
	$("tr").removeClass("over");
	$(this).addClass("over");

    detailData = new FormData();
    detailData.append('porno', $(this).find('td:eq(0)').text());
    detailData.append('seq', $(this).find('td:eq(1)').text());
    detailData.append('idx', $(this).data('idx'));
	$.ajax({
		url: "<?= base_url('/TSK/detail_after/') ?>",
		type: "post",
		data : detailData,
		dataType: "html",
		cache  : false,
		contentType : false,
		processData : false,
		success: function(data) {
			$("#ajax_detail_container").html(data);
		}
	});

});






//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>