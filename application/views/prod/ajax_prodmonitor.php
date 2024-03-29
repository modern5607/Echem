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

		border-color: #121415;
		/* border-style: solid; */
		border-width: 5px;
		font-family: Arial, sans-serif;
		/* font-size: 14px; */
		overflow: hidden;
		padding: 10px 5px;
		word-break: normal;
		font-weight: bolder;
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
		background: #121415;
		text-align: center;
		width: 210px;
		height: auto;
		color: white;
		font-size: 18px;
	}

	.column {
		width: 100px;
		text-align: center;
		background: #232526;
		border-right: #232526;
		border-bottom: 5px solid #121415;
		color: white;

		font-size: 18px;
	}

	.datacell {
		width: 190px;
		height: 55px;
		background: #232526;
		border-bottom: 5px solid #121415;
		font-size: 25px;
		color: yellow;

	}

	.tag {
		border-bottom: 1px solid blueviolet;
		width: 18px;
		background-color: blueviolet;

	}

	.tbl-content {
		margin-bottom: 4px;
	}

	.body_Content {
		background-color: #121415;
	}
</style>


<div class="bdcont_100">
	<div class="">
		<header>
			<span class="btn print add_order" style="padding:7px 11px; margin-bottom: 5px; color:white;"><i class="material-icons"></i><?= date("Y-m-d H:i:s", time()) ?></span>

		</header>


		<div style="display: block;height: 150px;">
			<!-- 온수탱크-->
			<span class="water_tank" style="max-height: none; overflow-y: auto; left: 10px; top: 10px; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="2" class="color">온수탱크1<br><br><img src="../_static/img/water-tank.png" width="75"></td>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">72</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">59</td>
					</tr>
				</table>
			</span>


			<!--온수탱크 -->
			<span class="" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left; ">
				<table class="tg">
					<tr>
						<td rowspan="2" class="color">온수탱크2<br><br><img src="../_static/img/water-tank.png" width="75"></td>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">67</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">58</td>
					</tr>
				</table>
			</span>
		</div>

		<div style="display: block;height: 300px;">
			<!--탄산나트륨탱크 -->
			<span class="" style="max-height: none; overflow-y: auto; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">탄산나트륨탱크<br><br><img src="../_static/img/tank.png" width="100"></td>
						<td class="tag"></td>
						<td class="column">PH</td>
						<td class="datacell right">11.77</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">CL(ppm)</td>
						<td class="datacell right">0.02</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">41</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">압력(Pa)</td>
						<td class="datacell right">1.01</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">43</td>
					</tr>
				</table>
			</span>
			<!--탄산나트륨탱크 -->
			<span class="" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">세척탱크<br><br><img src="../_static/img/tank.png" width="100"></td>
						<td class="tag"></td>
						<td class="column">PH</td>
						<td class="datacell right">10.3</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">CL(ppm)</td>
						<td class="datacell right">5.4</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">33</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">압력(Pa)</td>
						<td class="datacell right">0.998</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">64</td>
					</tr>
				</table>
			</span>
			<!--탄산나트륨탱크 -->
			<span class="" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left;margin-bottom: 4px;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">반응탱크<br><br><img src="../_static/img/tank.png" width="100"></td>
						<td class="tag"></td>
						<td class="column">PH</td>
						<td class="datacell right">11.04</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">CL(ppm)</td>
						<td class="datacell right">9.8</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">45</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">압력(Pa)</td>
						<td class="datacell right">1.22</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">61</td>
					</tr>
				</table>
			</span>
		</div>
		<div style="display: block;height: 300px;">
			<!--교반탱크 -->
			<span class="" style="max-height: none; overflow-y: auto; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">교반탱크<br><br><img src="../_static/img/raw-tank.png" width="100"></td>
						<td class="tag"></td>
						<td class="column">PH</td>
						<td class="datacell right">12.5</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">CL(ppm)</td>
						<td class="datacell right">9.9</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">35</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">압력(Pa)</td>
						<td class="datacell right">1.09</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">43</td>
					</tr>
				</table>
			</span>
			<!--교반탱크 -->
			<span class="" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">교반탱크<br><br><img src="../_static/img/raw-tank.png" width="100"></td>
						<td class="tag"></td>
						<td class="column">PH</td>
						<td class="datacell right">12.5</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">CL(ppm)</td>
						<td class="datacell right">9.98</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">34</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">압력(Pa)</td>
						<td class="datacell right">1.10</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">45</td>
					</tr>
				</table>
			</span>
			<!--교반탱크 -->
			<span class="" style="max-height: none; overflow-y: auto; margin-left: 10px; float: left;margin-bottom: 4px;">
				<table class="tg">
					<tr>
						<td rowspan="5" class="color">교반탱크<br><br><img src="../_static/img/raw-tank.png" width="100"></td>
						<td class="tag"></td>
						<td class="column">PH</td>
						<td class="datacell right">12.4</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">CL(ppm)</td>
						<td class="datacell right">9.97</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">온도(°C)</td>
						<td class="datacell right">35</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">압력(Pa)</td>
						<td class="datacell right">1.11</td>
					</tr>
					<tr>
						<td class="tag"></td>
						<td class="column">수위(%)</td>
						<td class="datacell right">40</td>
					</tr>
				</table>
			</span>
		</div>

		<!-- <div style="float: left;">
			<div class="tbl-content" style="max-height: none; overflow-y: auto;">
				<table class="tg">
					<tr>
						<td class="datacell column" style="height: 100px;">일일 생산계획</td>
						<td class="datacell column" style="height: 100px;">일일 생산실적</td>
						<td class="datacell column" style="height: 100px;">진행률(%)</td>
						<td class="datacell column" style="height: 100px;">잔여율(%)</td>
					</tr>
					<tr>
						<td class="datacell column" style="height: 100px;"></td>
						<td class="datacell column" style="height: 100px;"></td>
						<td class="datacell column" style="height: 100px;"></td>
						<td class="datacell column" style="height: 100px;"></td>
					</tr>
				</table>
			</div>
		</div> -->
	</div>
</div>

<!-- <div id="pop_container">
	<div id="info_content" class="info_content" style="height:auto;">
		<div class="ajaxContent">
		</div>
	</div>
</div> -->

<script>
	$(document).ready(function() {
		$(".menu_Content").animate({
			width: 0
		},0, function() {
			$(".mhide").addClass('mshow');
			$(".mhide span").text("keyboard_arrow_right");
		});

		$("#smart_container").animate({
			paddingLeft: '15px'
		},0, function() {
			$(".mControl_show").show();
		});
	});
</script>