<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="bdcont_100">
		<header>
			<div class="searchDiv">
				<form id="ajaxForm">
					
					<label for="mid">신청일자</label>
						<input type="text" id="sdate-picker" name="sdate" value="<?php echo $str['sdate']; ?>" placeholder="<?= $str['sweek'] ?>" size="13"/> ~
						<input type="text" id="edate-picker" name="edate" value="<?php echo $str['edate']; ?>" placeholder="<?= $str['eweek'] ?>" size="13"/>
					
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
						<th>SEQ</th>
						<th>수량</th>
						<th>중량</th>
						<th>품명/재질/규격</th>
						<th>신청일자</th>
						<th style="width:53px;">FB</th>
						<th style="width:53px;">PK</th>
						<!-- <th style="width:15%;">결과</th> -->
					</tr>
				</thead>
				<tbody>
				<?php foreach($List as $i=>$row){ ?>
				<tr>
					<td class="cen"><?= $row->POR_NO; ?></td>
					<td class="cen"><?= $row->POR_SEQ; ?></td>
					<td class="right"><?= number_format($row->PO_QTY); ?></td>
					<td class="right"><?= number_format($row->WEIGHT); ?></td>
					<td><?= $row->MCCSDESC; ?></td>
					<td class="cen"><?= $row->CHKDATE; ?></td>
				    <td class="cen fbchk"><input style="width:13px;" <?= ($row->FB_YN != "N")?"checked":"" ?> name="fbchk" type="checkbox" value="FB_YN"></td>
				    <td class="cen pkchk"><input style="width:13px;" <?= ($row->PK_YN != "N")?"checked":"" ?> name="pkchk" type="checkbox" value="PK_YN"></td>
					<!-- <td></td> -->
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

// tr클릭시 체크 설정
$(document).off("click",".tbl-content table tbody tr");
$(document).on("click",".tbl-content table tbody tr",function(){
	var $this = $(this)
	var porno = $(this).find('td:eq(0)').text();
	var seq = $(this).find('td:eq(1)').text();

	if($(this).children().attr("class") == "list_none"){
		return false;
	}

	if($this.find('td:eq(6) :checkbox').is(":checked") == true && $this.find('td:eq(7) :checkbox').is(":checked") == false) {
		if(confirm(porno+"-"+seq+"의 포장검사를 완료하시겠습니까?") == true){
			$this.find('td:eq(7) :checkbox').prop("checked", true);
			var yn = $("input[name=pkchk]").val();
			check_up(yn,porno,seq);
		}
	}

	if($this.find('td:eq(6) :checkbox').is(":checked") == false) {
		if(confirm(porno+"-"+seq+"의 제작검사를 완료하시겠습니까?") == true){
			$this.find('td:eq(6) :checkbox').prop("checked", true);
			var yn = $("input[name=fbchk]").val();
			check_up(yn,porno,seq);
		}
	}

});
$(document).off("click","input[type=checkbox]");
$(document).on("click","input[type=checkbox]",function(){
	console.log($("input[name=fbchk]").val())
	return false;
});

function check_up(yn,porno,seq){
	var formData = new FormData();
	formData.append("yn",yn);
	formData.append("porno",porno);
	formData.append("seq",seq);


	$.ajax({
		url  : "<?php echo base_url('/TSK/check_up')?>",
		type : "POST",
		data : formData,
		cache  : false,
		contentType : false,
		processData : false,
		success : function(data){},
		error   : function(xhr,textStatus,errorThrown){
			alert(xhr,textStatus,errorThrown);
		}
	});
}


//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>