<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	.ui-datepicker-calendar {
		display: none;
	}
</style>

<div class="bdcont_100">
	<header>
		<div class="searchDiv">
			<form id="ajaxForm">
				
			</form>
		</div>
	</header>

	<div class="tbl-content">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" id="excel">
			<thead>
				<tr>
					<th>순번</th>
					<th>1</th>
					<th>2</th>
					<th>3</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($list)) {
					foreach ($list as $i => $row) {
                        $num=$i+1;
				?>
						<tr>
							<td><?= $num?> </td>
							<td><?= $row->COL1?></td>
							<td><?= $row->COL2?> </td>
							<td><?= $row->COL3?> </td>
						</tr>
					<?php
					}
				} else {
					?>
					<tr>
						<td colspan="15" class="list_none">월간 실행계획이 없습니다.</td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>

</div>


<div id="pop_container">

	<div class="info_content" style="height:auto;">
		<div class="ajaxContent">

			<!-- 데이터 -->

		</div>
	</div>

</div>

