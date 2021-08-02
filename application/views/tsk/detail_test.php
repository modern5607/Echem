
<header>
	<div class="test_wrkaft" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="detail_date">				
				<label for="mname">신청일</label>
				<input type="text" name="sdate" class="calendar" size='13' value="<?= date('Y-m-d') ?>" />		

		</form>
	</div>
</header>
<button type="button" class="test-icon test_del_btn"><i class="material-icons">delete_sweep</i>검사취소</button>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th style="width:33px;"></th>
					<th>POR NO</th>
					<th>SEQ</th>
					<th>수량</th>
					<th>품명/재질/규격</th>
					<th>신청일</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			if($this->data['cnt'] != 0){
			foreach($List as $i=>$row){ 
			if($row->POR_NO != "합계"){?>
			<tr>
				<td><input style="width:13px;" name="checkD" type="checkbox"></td>
				<td class="cen"><?= $row->POR_NO; ?></td>
				<td class="cen"><?= $row->POR_SEQ; ?></td>
				<td class="right"><?= number_format($row->PO_QTY); ?></td>
				<td><?= $row->MCCSDESC; ?></td>
				<td class="cen"><?= $row->CHKDATE; ?></td>
			</tr>
	
			<?php
			}}}else{
			?>
				<tr>
					<td colspan="15" class="list_none">신청된 검사가 없습니다.</td>
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

$(document).off("click",".test_del_btn");
$(document).on("click",".test_del_btn",function(){
	
	var formData = new FormData();
	var checkbox = $("input[name=checkD]:checked");


	// 체크된 체크박스 값을 가져온다
	checkbox.each(function(i) {
		var tr = checkbox.parent().parent().eq(i);
		var td = tr.children();

		// td.eq(0)은 체크박스 이므로  td.eq(1)의 값부터 가져온다.
		var porno = td.eq(1).text()
		var seq = td.eq(2).text()
		
		formData.append('porno['+i+']',porno)
		formData.append('seq['+i+']',seq)
	});


	$.ajax({
		url  : "<?php echo base_url('/TSK/test_del')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		success : function(data){
			load();
		},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr,textStatus,errorThrown);
		}
	});
});

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>