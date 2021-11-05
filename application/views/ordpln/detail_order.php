<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="tbl-content" style="padding-top:86px">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<thead>
			<tr>
				<th>NO</th>
				<th>품목</th>
				<th>수량</th>
				<th>납기요청일</th>
				<th>납기예정일</th>
				<th>거래처</th>
				<th>거래처 담당자</th>
				<th>등록일</th>
				<th>등록 ID</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if (!empty($list)) {
				foreach ($list as $i => $row) {
					$no = $pageNum + $i + 1;
			?>
					<tr class="headLink">
						<td><?= $no ?></td>
						<td><?= $row->col1 ?></td>
						<td><?= $row->col2 ?></td>
						<td><?= $row->col3 ?></td>
						<td><?= $row->col4 ?></td>
						<td><?= $row->col5 ?></td>
						<td><?= $row->col6 ?></td>
						<td><?= $row->col7 ?></td>
						<td><?= $row->col8 ?></td>
					</tr>
				<?php
				}
			} else {
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
	if ($this->data['cnt'] > 20) {
	?>
		<div class="limitset">
			<select name="per_page">
				<option value="20" <?php echo ($perpage == 20) ? "selected" : ""; ?>>20</option>
				<option value="50" <?php echo ($perpage == 50) ? "selected" : ""; ?>>50</option>
				<option value="80" <?php echo ($perpage == 80) ? "selected" : ""; ?>>80</option>
				<option value="100" <?php echo ($perpage == 100) ? "selected" : ""; ?>>100</option>
			</select>
		</div>
	<?php
	}
	?>
	<?php echo $this->data['pagenation']; ?>
</div>