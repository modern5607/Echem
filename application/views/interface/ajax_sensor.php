<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
	#detail_ec{
		width:35%;
		margin:0 20px;
	}
	#chart_ec{
		width: 40%;
	}
</style>

<header>
	<div class="searchDiv">
		<form id="ajaxForm" onsubmit="return false">
			<label for="date">날짜</label>
				<input type="date" name="sdate" value="<?= $str['sdate']; ?>" class="" /> ~
				<input type="date" name="edate" value="<?= $str['edate']; ?>" class="" />

			<label for="slave">Slave</label>
				<select name="slave" id="slave" class="form_input">
					<option value="" <?= ($str['slave'] == "") ? "selected" : '' ?>></option>
					<option value="1" <?= ($str['slave'] == "1") ? "selected" : '' ?>>1</option>
					<option value="2" <?= ($str['slave'] == "2") ? "selected" : '' ?>>2</option>
					<option value="3" <?= ($str['slave'] == "3") ? "selected" : '' ?>>3</option>
					<option value="4" <?= ($str['slave'] == "4") ? "selected" : '' ?>>4</option>
					<option value="5" <?= ($str['slave'] == "5") ? "selected" : '' ?>>5</option>
				</select>

			<button class="search_submit ajax_search"><i class="material-icons">search</i></button>
		</form>
	</div>
	<!-- <span class="btn print add_order"  style="padding:7px 11px;"><i class="material-icons">add</i>작업지시 등록</span> -->
	<!-- <span class="btn print print_biz"><i class="material-icons">get_app</i>출력하기</span> -->
</header>


<div style="display:flex">

	<div class="tbl-content" style="width:25%;">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<thead>
				<tr>
					<th width="60px;">NO</th>
					<th>일자</th>
					<th>SLAVE</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($List as $i => $row) {
					$no = $pageNum + $i + 1;
				?>
					<tr class="link_hover" data-idx="<?= $row->IDX ?>" data-slave="<?= $row->SLAVE ?>">
						<td class="cen"><?= $no; ?></td>
						<td class="cen" style="color:blue"><?= $row->INSERT_BTN ?></td>
						<td class="cen"><?= $row->SLAVE ?></td>
					</tr>
				<?php
				}
				?>
			</tbody>
		</table>
	</div>


	<div id="detail_ec">
	</div>

	<div id="chart_ec">
	</div>

</div>

<div class="pagination1" style="width:340px;" >
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



<script>
	$("input").attr("autocomplete", "off");

	$(".link_hover").click(function() {
		var idx = $(this).data("idx");
		var slave = $(this).data("slave");

		$(".link_hover").removeClass("over");
		$(this).addClass("over");
		$.ajax({
			url: "<?= base_url('_INTERFACE/detail_sensor') ?>",
			type: "POST",
			dataType: "HTML",
			data: {
				idx: idx,
				slave: slave
			},
			beforeSend: function() {
				// $(this).hide();
				// $("#detailForm").hide();
				// $("#loading").show();
			},
			success: function(data) {
				$("#detail_ec").empty();
				$("#detail_ec").html(data);
				// $("#loading").hide();

				chart();
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		})
	});

	$(document).ready(function(){
		chart();
	})

	function chart() {
		var idx = $(".over").data("idx");
			var slave = $(".over").data("slave");
			$.ajax({
				url: "<?= base_url('_INTERFACE/detail_sensor_chart') ?>",
				type: "POST",
				dataType: "HTML",
				data: {
					idx: idx,
					slave: slave
				},
				success: function(data) {
					$("#chart_ec").empty();
					$("#chart_ec").html(data);
				},
					error: function(xhr, textStatus, errorThrown) {
					alert(xhr);
					alert(textStatus);
					alert(errorThrown);
				}
			})
    }

	//제이쿼리 수신일 입력창 누르면 달력 출력
	$(".calendar").datetimepicker({
		format: 'Y-m-d',
		timepicker: false,
		lang: 'ko-KR'
	});
</script>