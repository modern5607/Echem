<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<link href="<?php echo base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>
<style type="text/css">
	.tg {
		border-collapse: collapse;
		border-spacing: 0;
	}

	.tg td {
		border-color: black;
		border-style: solid;
		border-width: 1px;
		font-family: Arial, sans-serif;
		font-size: 14px;
		overflow: hidden;
		padding: 10px 5px;
		word-break: normal;
	}

	.tg th {
		border-color: black;
		border-style: solid;
		border-width: 1px;
		font-family: Arial, sans-serif;
		font-size: 14px;
		font-weight: normal;
		overflow: hidden;
		padding: 10px 5px;
		word-break: normal;
	}

	.tg .tg-9wq8 {
		border-color: inherit;
		text-align: center;
		vertical-align: middle
	}

	.tg .tg-8uya {
		background-color: #FFCB2F;
		border-color: #000000;
		text-align: center;
		vertical-align: middle
	}

	.tg .tg-936e {
		background-color: #ffcb2f;
		border-color: inherit;
		text-align: center;
		vertical-align: middle
	}

	.tg .tg-ndbq {
		background-color: #FFCB2F;
		border-color: inherit;
		text-align: center;
		vertical-align: middle
	}

	.tg .tg-rcip {
		background-color: #FFF;
		border-color: inherit;
		text-align: center;
		vertical-align: middle
	}

	.tg .tg-nrix {
		text-align: center;
		vertical-align: middle
	}

	.tg .tg-xwyw {
		border-color: #000000;
		text-align: center;
		vertical-align: middle
	}
</style>


<div class="bdcont_100">
	<div class="">
		<header>
			<span class="btn print add_order" style="padding:7px 11px; margin-bottom: 5px;"><i class="material-icons"></i><?= date("Y-m-d H:i:s", time()) ?></span>

		</header>
		<div class="tbl-content">
			<table class="tg" style="table-layout: fixed;" cellpadding="0" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col style="width: 107px">
					<col style="width: 107px">
					<col style="width: 93px">
					<col style="width: 95px">
					<col style="width: 45px">
					<col style="width: 46px">
					<col style="width: 82px">
					<col style="width: 55px">
					<col style="width: 70px">
				</colgroup>
				<tbody>
					<tr>
						<td class="tg-936e" rowspan="2">온수탱크1</td>
						<td class="tg-9wq8">수위</td>
						<td class="tg-9wq8"></td>
						<td class="tg-ndbq" rowspan="2">온수탱크2</td>
						<td class="tg-9wq8">수위</td>
						<td class="tg-9wq8"></td>
						<td class="tg-9wq8" colspan="3" rowspan="2"></td>
					</tr>
					<tr>
						<td class="tg-9wq8">온도</td>
						<td class="tg-9wq8"></td>
						<td class="tg-9wq8">온도</td>
						<td class="tg-9wq8"></td>
					</tr>
					<tr>
						<td class="tg-9wq8" colspan="9"></td>
					</tr>
					<tr>
						<td class="tg-ndbq" rowspan="5">탱크아이콘</td>
						<td class="tg-9wq8">PH</td>
						<td class="tg-9wq8"></td>
						<td class="tg-ndbq" rowspan="5">탱크아이콘<br> <br> <br> <br> </td>
						<td class="tg-9wq8">PH</td>
						<td class="tg-9wq8"></td>
						<td class="tg-ndbq" rowspan="5">탱크아이콘</td>
						<td class="tg-rcip">PH</td>
						<td class="tg-9wq8"></td>
					</tr>
					<tr>
						<td class="tg-9wq8">CL</td>
						<td class="tg-9wq8"></td>
						<td class="tg-9wq8">CL</td>
						<td class="tg-9wq8"></td>
						<td class="tg-rcip">CL</td>
						<td class="tg-9wq8"></td>
					</tr>
					<tr>
						<td class="tg-9wq8">온도</td>
						<td class="tg-9wq8"></td>
						<td class="tg-9wq8">온도</td>
						<td class="tg-9wq8"></td>
						<td class="tg-rcip">온도</td>
						<td class="tg-9wq8"></td>
					</tr>
					<tr>
						<td class="tg-9wq8">압력</td>
						<td class="tg-9wq8"></td>
						<td class="tg-9wq8">압력</td>
						<td class="tg-9wq8"></td>
						<td class="tg-rcip">압력</td>
						<td class="tg-9wq8"></td>
					</tr>
					<tr>
						<td class="tg-9wq8">수위</td>
						<td class="tg-9wq8"></td>
						<td class="tg-9wq8">수위</td>
						<td class="tg-9wq8"></td>
						<td class="tg-rcip">수위</td>
						<td class="tg-9wq8"></td>
					</tr>
					<tr>
						<td class="tg-9wq8" colspan="9"></td>
					</tr>
					<tr>
						<td class="tg-9wq8" colspan="6" rowspan="5"></td>
						<td class="tg-ndbq" rowspan="3">원료탱크</td>
						<td class="tg-rcip">Cl</td>
						<td class="tg-9wq8"></td>
					</tr>
					<tr>
						<td class="tg-nrix">온도</td>
						<td class="tg-nrix"></td>
					</tr>
					<tr>
						<td class="tg-nrix">수위</td>
						<td class="tg-nrix"></td>
					</tr>
					<tr>
						<td class="tg-ndbq" rowspan="3">원료탱크</td>
						<td class="tg-nrix">Cl</td>
						<td class="tg-nrix"></td>
					</tr>
					<tr>
						<td class="tg-nrix">온도</td>
						<td class="tg-nrix"></td>
					</tr>
					<tr>
						<td class="tg-9wq8">일일 생산계획</td>
						<td class="tg-9wq8">일일 생산실적</td>
						<td class="tg-9wq8">진행률(%)</td>
						<td class="tg-9wq8">잔여율(%)</td>
						<td class="tg-9wq8" colspan="2" rowspan="4"></td>
						<td class="tg-nrix">수위</td>
						<td class="tg-nrix"></td>
					</tr>
					<tr>
						<td class="tg-xwyw" rowspan="3"></td>
						<td class="tg-xwyw" rowspan="3"></td>
						<td class="tg-xwyw" rowspan="3"></td>
						<td class="tg-xwyw" rowspan="3"></td>
						<td class="tg-8uya" rowspan="3">원료탱크</td>
						<td class="tg-nrix">Cl</td>
						<td class="tg-nrix"></td>
					</tr>
					<tr>
						<td class="tg-nrix">온도</td>
						<td class="tg-nrix"></td>
					</tr>
					<tr>
						<td class="tg-nrix">수위</td>
						<td class="tg-nrix"></td>
					</tr>
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