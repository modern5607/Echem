<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<link href="<?php echo base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>



<div class="bdcont_100">
	<div class="">
		<header>
		</header>
		<div class="tbl-content">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<thead>
					<tr>
                        <th>NO</th>
						<th>COL1</th>
						<th>COL2</th>
						<th>COL3</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($list as $i => $row) {
						$no = $i + 1;
					?>
						<tr>
							<td class="cen"><?php echo $no; ?></td>
							<td class="cen"><?php echo $row->COL1; ?></td>
							<td class="cen"><?php echo $row->COL2; ?></td>
                            <td class="cen"><?php echo $row->COL3; ?></td>			
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

	<div id="info_content" class="info_content" style="height:auto;">

		<div class="ajaxContent">


		</div>

	</div>

</div>