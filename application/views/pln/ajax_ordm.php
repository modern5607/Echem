<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- 달력 및 에디터호출 -->
<!-- <link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css')?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js')?>"></script> -->

<div class="bdcont_100">
		
		<div class="bc__box100">
			<header>
				<div class="searchDiv">
					<form id="ajaxForm">
						<!-- <label>수주구분</label>
						<select name="sjgb" id="" class="form_select">
							<option value="">전체</option>
							<option value="R" <?php echo ($str['sjgb'] == "R")?"selected":"" ?>>정규</option>
							<option value="G" <?php echo ($str['sjgb'] == "G")?"selected":"" ?>>돌발</option>
						</select> -->

						<?php
					if(!empty($SJ_GB)){
					?>
						<label for="sjgb">수주구분</label>
						<select name="sjgb" id="sjgb" class="form_select">
							<option value="" >전체</option>
							
						<?php
						foreach($SJ_GB as $row){
						?>
							<option value="<?php echo $row->D_CODE?>" <?php echo ($str['sjgb'] == $row->D_CODE)?"selected":"";?>><?php echo $row->D_NAME;?></option>
						<?php
						}
						?>
						</select>
					<?php
					}
					?>

						<label>일자</label>
						<input type="text" name="month" id="month" size=12 value="<?php echo $str['month']; ?>" />
						<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
					</form>
				</div>

			</header> 

			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>POR No</th>
							<th>SEQ</th>
							<th>단중</th>
                            <th>총 중량</th>
                            <th>수량</th>
							<th>계약 금액</th>
							<th>품명/재질/규격</th>
							<th>계약일</th>
							<th>MP납기</th>
							<th>일수</th>
							<th>후처리 업체</th>
							<th>후처리 코드</th>
							<th>구분</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($List as $i=>$row){
						$no = $pageNum+$i+1;
						$sjgb='';
						foreach($SJ_GB as $row2)
						{
							if($row2->D_CODE == $row->OUTB_GBN)
							{
								$sjgb = $row2->D_NAME;
								break;
							}
							else
								continue;
						}
					?>

						<tr>
							<td class="cen"><?php echo $no?></td>
							<td class="cen"><?php echo $row->POR_NO?></td>
							<td class="cen"><?php echo $row->POR_SEQ?></td>
							<td class="right"><?php echo $row->UNITW+0 ?></td>
							<td class="right"><?php echo $row->WEIGHT+0 ?></td>
							<td class="right"><?php echo number_format($row->PO_QTY+0)?></td>
							<td class="right"><?php echo number_format($row->PO_AMT+0)?></td>
							<td class="left"><?php echo $row->MCCSDESC?></td>
							<td class="cen"><?php echo $row->POWRDA?></td>
							<td class="cen"><?php echo $row->PORRQDA?></td>
							<td class="right"><?php echo (strtotime($row->PORRQDA) - strtotime($row->POWRDA))/86400?></td>
							<td class="cen"><?php echo $row->AVCODE?></td>
							<td class="cen"><?php echo $row->AREANO?></td>
							<td class="cen"><?php echo $sjgb?></td>
						</tr>

					<?php
					}
					if(empty($List)){
						?>
		
							<tr>
								<td colspan="15" class="list_none">제품정보가 없습니다.</td>
							</tr>
		
						<?php
						}	
					?>
					</tbody>
				</table>
			</div>

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
				<option value="80" <?php echo ($perpage == 80)?"selected":"";?>>80</option>
				<option value="100" <?php echo ($perpage == 100)?"selected":"";?>>100</option>
			</select>
		</div>
		<?php
		}	
		?>
		<?php echo $this->data['pagenation'];?>
	</div>

<div id="pop_container">
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>



<script>
$("#month").datepicker( {
	
    changeMonth: true,      
    changeYear: true,       
    showButtonPanel: true,  
    dateFormat: 'yy-mm',
    onClose: function(dateText, inst) {
        var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
        var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
        $(this).datepicker('setDate', new Date(year, month, 1));
            // $(this).blur();
		}

	
});

$("#month").focus(function () {
    $(".ui-datepicker-calendar").hide();
    // $("#ui-datepicker-div").position({
    //     my: "center top",
    //     at: "center bottom",
    //     of: $(this)
    // });
});
</script>