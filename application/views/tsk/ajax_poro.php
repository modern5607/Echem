<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="bdcont_100">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					
					<label for="mid">MP납기</label>
						<input type="text" id="sdate-picker" name="sdate" value="<?php echo $str['sdate']; ?>" size="13"/> ~
						<input type="text" id="edate-picker" name="edate" value="<?php echo $str['edate']; ?>" size="13"/>
					
					<label for="mname">POR NO</label>
					<input type="text" name="porno" id="porno" value="<?php echo $str['porno']?>" size='10' />
					
					<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
				</form>
			</div>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
						<th>POR NO</th>
						<th>수량</th>
						<th>총 중량</th>
						<th>MP납기일</th>
						<th>절단/가공</th>
						<th>취부</th>
						<th>용접</th>
						<th>사상</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($List as $i=>$row){ ?>
				<tr>
					<td class="cen"><?= $row->POR_NO; ?></td>
					<td class="right"><?= number_format($row->PO_QTY); ?></td>
					<td class="right"><?= number_format($row->WEIGHT,3); ?></td>
					<td class="cen"><?= $row->PORRQDA; ?></td>
					<td class="cen"><span data-gj="PROC" data-por="<?= $row->POR_NO; ?>" <?= ($row->PROC_YN > 0)?"class='link_s1'>".$row->PROC_PLN."":">".$row->PROC_ACT."" ?></span></td>
					<td class="cen"><span data-gj="ASSE" data-por="<?= $row->POR_NO; ?>" <?= ($row->ASSE_YN > 0)?"class='link_s1'>".$row->ASSE_PLN."":">".$row->ASSE_ACT."" ?></span></td>
					<td class="cen"><span data-gj="WELD" data-por="<?= $row->POR_NO; ?>" <?= ($row->WELD_YN > 0)?"class='link_s1'>".$row->WELD_PLN."":">".$row->WELD_ACT."" ?></span></td>
					<td class="cen"><span data-gj="MRO" data-por="<?= $row->POR_NO; ?>" <?= ($row->MRO_YN > 0)?"class='link_s1'>".$row->MRO_PLN."":">".$row->MRO_ACT."" ?></span></td>
				</tr>
		
				<?php
				}
				if(empty($List)){
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
</div>


<div class="pagination">
	<?php
	if($this->data['cnt'] > 20){
	?>
	<div class="limitset">
		<select name="per_page">
			<option value="20" <?php echo ($perpage == 20)?"selected":"";?>>20</option>
			<option value="50" <?php echo ($perpage == 50)?"selected":"";?>>50</option>
            <?php if($this->data['cnt'] > 50){ ?>
			<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
            <?php }; if($this->data['cnt'] > 80){ ?>
			<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
            <?php }; ?>
		</select>
	</div>
	<?php
	}	
	?>
	<?php echo $this->data['pagenation'];?>
</div>

<div id="pop_container">
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent"></div>
	</div>
</div>



<script>
$(function() {
	var startDate;
	var endDate;
	$('#sdate-picker, #edate-picker').datepicker({
		showOtherMonths: true,
		selectOtherMonths: true,
		selectWeek: true,
		onSelect: function(dateText, inst) {
			var date = $(this).datepicker('getDate');
			startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 0);
			endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay() + 6);
			var dateFormat = 'yy-mm-dd'
			startDate = $.datepicker.formatDate(dateFormat, startDate, inst.settings);
			endDate = $.datepicker.formatDate(dateFormat, endDate, inst.settings);
			$('#sdate-picker').val(startDate);
			$('#edate-picker').val(endDate);
		},
		onChangeMonthYear : function() {
				setTimeout("applyWeeklyHighlight()", 100);
				},
		beforeShow: function() {
			setTimeout("applyWeeklyHighlight()", 100);
		}
	});
});

function applyWeeklyHighlight() {
	$('.ui-datepicker-calendar tr').each(function() {
		if ($(this).parent().get(0).tagName == 'TBODY') {
			$(this).mouseover(function() {
				$(this).find('a').css({
					'background': '#ffffcc',
					'border': '1px solid #dddddd'
				});
				$(this).find('a').removeClass('ui-state-default');
				$(this).css('background', '#ffffcc');
			});
			$(this).mouseout(function() {
				$(this).css('background', '#ffffff');
				$(this).find('a').css('background', '');
				$(this).find('a').addClass('ui-state-default');
			});
		}
	});
}

//수정
$(document).off("click",".link_s1");
$(document).on("click",".link_s1",function(){
	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top : "50%"
	},500);

	var por = $(this).data("por");
	var gj = $(this).data("gj");

	$.ajax({
		url:"<?php echo base_url('TSK/poro_form')?>",
		type : "post",
		data : {por:por, gj:gj},
		dataType : "html",
		success : function(data){
			$(".ajaxContent").html(data);
		}
	});
});


//팝업 닫기
$(document).off("click","h2 > span.close");
$(document).on("click","h2 > span.close",function(){
	$(".ajaxContent").html('');
	$("#pop_container").fadeOut();
	$(".info_content").css("top","-50%");
});

//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>