<header>
	<div class="searchBoxxx" style="margin-bottom:20px; padding:15px; border:1px solid #ddd;">
		<form id="headForm">
			<label>발주 요청일</label>
			<input type="text" name="date" value="" class="calendar" />
			
			<button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<!-- <span class="btn add add_head"><i class="material-icons">add</i>추가</span> -->
</header>

<div id="cocdHead" class="tbl-content">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th>No</th>
				<th>발주요청일</th>
				<th>발주건수</th>
				<th>발주 진행 여부</th>
				<th>신청 완료일</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ($list as $i => $row) {
                $num = $i+1;
			?>
				<tr class="pocbox">
					<td class="cen"><?=$num?></td>
					<td><?=$row->COL1 ?></td>
					<td><?=$row->COL2 ?></td>
					<td><?=$row->COL3 ?></td>
					<td></td>
				</tr>

			<?php
			}
			?>
		</tbody>
	</table>
</div>


<script>
//제이쿼리 수신일 입력창 누르면 달력 출력
$(".calendar").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});


</script>