<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="tbl-content" style="padding-top:86px">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th>POR No</th>
				<th>SQE</th>
				<th>총 중량</th>
				<th>수량</th>
				<th>품면/재질/규격</th>
				<th>MP납기일</th>
				<th>일수</th>
				<th>변경일</th>
				<th>변경일수</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($List)){
		foreach($List as $i=>$row){
			$no = $pageNum+$i+1;
		?>
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		<?php
		}}else{
		?>
			<tr>
				<td colspan="15" class="list_none">납기변경내역이 없습니다.</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
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
//input autocoamplete off
$("input").attr("autocomplete", "off");
</script>