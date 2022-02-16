<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="ajaxForm">
			
            <label>일자</label>
                <input type="text" name="sdate" class="calendar" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
                <input type="text" name="edate" class="calendar" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />

				<label for="">구분</label>
					<select name="" id="">
						<option value="">전체</option>
						<option value="">원자재</option>
						<option value="">완제품</option>
					</select>

				<label for="">입출고</label>
					<select name="" id="">
						<option value="">전체</option>
						<option value="">입고</option>
						<option value="">출고</option>
					</select>

			<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header> 

<div class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th>No</th>
				<th>등록일</th>
				<th>구분</th>
				<th>입출고</th>
				<th>단위</th>
				<th>거래처</th>
				<th>수량</th>
				<th>입고일</th>
				<th>출고일</th>
				<th>등록자</th>
				<th>비고</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if(!empty($list)){
		foreach($list as $i=>$row){
			$no = $i+1;
		?>

			<tr>
				<td colspan="15" class="list_none">정보가 없습니다.</td>
			</tr>

		<?php
		}}else{
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


<div id="pop_container">
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>
