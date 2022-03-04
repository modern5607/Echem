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

	.water_tank td:nth-child(0) {}

	.color {
		background: #ffcb2f;
		text-align: center;
		width: 210px;
	}

	.column {
		width: 110px;
		text-align: center;
	}

	.datacell {
		width: 190px;
	}
	.tbl-content{margin-bottom: 4px;}
</style>


<div class="bdcont_100">
	<div class="">
		<header>
			<span class="btn print add_order" style="padding:7px 11px; margin-bottom: 5px;"><i class="material-icons"></i><?= date("Y-m-d H:i:s", time()) ?></span>

		</header>


		<div style="display: block;height: 132px;">
			<!-- 온수탱크-->
			<span class="tbl-content water_tank" style="max-height: none; overflow-y: auto; left: 10px; top: 10px; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="2" class="color">온수탱크<br><br><img src="../_static/img/water-tank.png" width="75"></td>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</span>


			<!--온수탱크 -->
			<span class="tbl-content" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left; ">
				<table class="tg">
					<tr>
						<td rowspan="2" class="color">온수탱크<br><br><img src="../_static/img/water-tank.png" width="75"></td>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</span>
		</div>

		<div style="display: block;height: 156px;">
			<!--탄산나트륨탱크 -->
			<span class="tbl-content" style="max-height: none; overflow-y: auto; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">탄산나트륨탱크<br><br><img src="../_static/img/tank.png" width="100"></td>
						<td class="column">PH</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">CL</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">압력</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</span>
			<!--탄산나트륨탱크 -->
			<span class="tbl-content" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">탄산나트륨탱크<br><br><img src="../_static/img/tank.png" width="100"></td>
						<td class="column">PH</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">CL</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">압력</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</span>
			<!--탄산나트륨탱크 -->
			<span class="tbl-content" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left;margin-bottom: 4px;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">탄산나트륨탱크<br><br><img src="../_static/img/tank.png" width="100"></td>
						<td class="column">PH</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">CL</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">압력</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</span>
		</div>



		<div style="float: right; margin-right: 36px;">
			<div class="tbl-content" style="max-height: none; overflow-y: auto;margin-bottom: 4px;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">원료탱크<br><br><img src="../_static/img/raw-tank.png" width="75" alt=""></td>
						<td class="column">CL</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</div>

			<div class="tbl-content" style="max-height: none; overflow-y: auto;margin-bottom: 4px; ">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">원료탱크<br><br><img src="../_static/img/raw-tank.png" width="75" alt=""></td>
						<td class="column">CL</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</div>

			<div class="tbl-content" style="max-height: none; overflow-y: auto;margin-bottom: 4px; ">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">원료탱크<br><br><img src="../_static/img/raw-tank.png" width="75" alt=""></td>
						<td class="column">CL</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">온도</td>
						<td class="datacell"></td>
					</tr>
					<tr>
						<td class="column">수위</td>
						<td class="datacell"></td>
					</tr>
				</table>
			</div>
		</div>

		<div style="float: left; margin-top: 200px;">
			<div class="tbl-content" style="max-height: none; overflow-y: auto;">
				<table class="tg">
				<tr>
						<td class="datacell column" style="height: 100px;">일일 생산계획</td>
						<td class="datacell column" style="height: 100px;">일일 생산실적</td>
						<td class="datacell column" style="height: 100px;">진행률(%)</td>
						<td class="datacell column" style="height: 100px;">잔여율(%)</td>
					</tr>
					<tr>
						<td class="datacell column"style="height: 100px;"></td>
						<td class="datacell column"style="height: 100px;"></td>
						<td class="datacell column"style="height: 100px;"></td>
						<td class="datacell column"style="height: 100px;"></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<!-- <div id="pop_container">
	<div id="info_content" class="info_content" style="height:auto;">
		<div class="ajaxContent">
		</div>
	</div>
</div> -->