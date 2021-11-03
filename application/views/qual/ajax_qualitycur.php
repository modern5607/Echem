<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>


<div class="bdcont_100">
		
		<div class="">

			<header>
				<div class="searchDiv">
					<form id="ajaxForm">
						
						<button type="button" class="search_submit ajax_search"><i class="material-icons">search</i></button>
					</form>
				</div>
				<!-- <span class="btn print add_biz"><i class="material-icons">add</i>업체추가</span> -->
				<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
			</header> 

			<div class="tbl-content">
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<thead>
						<tr>
							<th>No</th>
							<th>1</th>
							<th>2</th>
							<th>3</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if(!empty($list)){
					foreach($list as $i=>$row){
						$no = $i+1;
					?>

						<tr>
							<td><?php echo $no;?></td>
							<td><?= $row->COL1?></td>
							<td><?= $row->COL2?></td>
							<td><?= $row->COL3?></td>
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

		</div>
			

	</div>


<div id="pop_container">
	
	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">			
			
		<!-- 데이터 -->

		</div>
	</div>

</div>
