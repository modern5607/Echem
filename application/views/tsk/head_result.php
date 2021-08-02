<style>
@media (max-width: 1235px) {  
	.bc__box header .search_submit{padding:4px 10px;};
};
</style>

<header style="">
	<div class="searchDiv">
		<form id="headForm">
			<label>납기일</label>
			<input type="text" name="date" id="month" size="13" value="<?= $str['date']; ?>" />


			<button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header> 

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr style="border-bottom: 2px solid #888;">
                <th colspan="4">정규</th>
			</tr>
			<tr>
                <th>품목</th>
				<th>건수</th>
				<th>수량</th>
				<th>중량</th>
			</tr>
		</thead>
		<tbody>
        <?php 
        $A_COUNT = 0 ;
        $A_QTY = 0 ;
        $A_WEIGHT = 0 ;
        foreach($List as $i=>$row){ 
            $A_COUNT += $row->COUNT;
            $A_QTY += $row->C_QTY;
            $A_WEIGHT += $row->C_WEIGHT;
        ?>
			<tr>
				<td  class="cen"><?= $row->GBN ?></td>
				<td class="right"><?= $row->COUNT ?></td>
				<td class="right"><?= number_format($row->C_QTY) ?></td>
				<td class="right"><?= $row->C_WEIGHT ?></td>
			</tr>
        <?php } ?>
            <tr style="border-top:2px solid #888 ; font-size:.9rem">
                <td  class="cen">합계</td>
				<td class="right"><?= $A_COUNT ?></td>
				<td class="right"><?= number_format($A_QTY) ?></td>
				<td class="right"><?= number_format($A_WEIGHT,3) ?></td>
            </tr>
		</tbody>
	</table>
<br>
<br>
<br>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr style="border-bottom: 2px solid #888;">
                <th colspan="4">돌발</th>
			</tr>
			<tr>
                <th>품목</th>
				<th>건수</th>
				<th>수량</th>
				<th>중량</th>
			</tr>
		</thead>
		<tbody>
        <?php 
        $A_COUNT = 0 ;
        $A_QTY = 0 ;
        $A_WEIGHT = 0 ;
        foreach($List2 as $i=>$row){ 
            $A_COUNT += $row->COUNT;
            $A_QTY += $row->C_QTY;
            $A_WEIGHT += $row->C_WEIGHT;
        ?>
			<tr>
				<td class="cen" data-gbn="<?= $row->PROC_GBN ?>"><?= $row->GBN ?></td>
				<td class="right"><?= $row->COUNT ?></td>
				<td class="right"><?= number_format($row->C_QTY) ?></td>
				<td class="right"><?= $row->C_WEIGHT ?></td>
			</tr>
        <?php } ?>
            <tr style="border-top:2px solid #888 ; font-size:.9rem">
                <td  class="cen">합계</td>
				<td class="right" ><?= $A_COUNT ?></td>
				<td class="right"><?= number_format($A_QTY) ?></td>
				<td class="right"><?= number_format($A_WEIGHT,3) ?></td>
            </tr>
		</tbody>
	</table>
</div>



<script>
$(document).off("click", ".bdcont_30 .tbl-content table tbody tr");
$(document).on("click", ".bdcont_30 .tbl-content table tbody tr", function() {
	var td = $(this).children();

	var desc = td.eq(0).text()
	var date = '<?= $str['date'] ?>'
	
	if(desc == "합계"){ return false; }
	$("tr").removeClass("over");
	$(this).addClass("over");


	detailData = new FormData($("#detailForm")[0]);
	detailData.append('date', date);
	if(td.eq(0).data('gbn')){
	detailData.append('gbn', td.eq(0).data('gbn'));  //PROC_GBN 숫자
	}else{
	detailData.append('desc', desc);				//품목명
	}


	$.ajax({
		url: "<?= base_url('/TSK/detail_result/') ?>",
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


$("#month").datepicker({
changeMonth: true,
changeYear: true,
showButtonPanel: true,
dateFormat: 'yy-mm',
onClose: function(dateText, inst) {
	var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
	var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
	$(this).datepicker('setDate', new Date(year, month, 1));
}});
$("#month").focus(function () { $(".ui-datepicker-calendar").hide(); });

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>