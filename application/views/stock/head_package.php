<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
	header {
		width: 870px;
		position: relative;
		z-index: 10;
	}

	@media screen and (min-width: 1200px) {
		header {
			width: calc(100vw - 330px);
		}
	};
</style>


<header>
	<div class="searchDiv">
		<form id="headForm">
			<label>일자</label>
			<input type="text" name="sdate" class="calendar" size="11" value="<?php echo $str['sdate']; ?>" placeholder="<?= date("Y-m-d") ?>" /> ~
			<input type="text" name="edate" class="calendar" size="11" value="<?php echo $str['edate']; ?>" placeholder="<?= date("Y-m-d") ?>" />

			<button type="button" class="search_submit head_search"><i class="material-icons">search</i></button>
		</form>
	</div>
</header>


<div class="tbl-content">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
            <tr>
                <th>NO</th>
                <th>작업지시일</th>
                <th>수주명</th>
                <th>거래처</th>
                <th>작업예정일</th>
                <th>작업종료일</th>
                <th>포장여부</th>
            </tr>
        </thead>
        <tbody>
            <?php
		if(empty($list)){
            foreach ($list as $i => $row) {
                $no = $pageNum + $i + 1;
            ?>
                <tr class="link_hover" data-idx="<?=$row->IDX?>">
                    <td class="cen"><?= $no; ?></td>
                    <td class="cen"><?=(!empty($row->ORDER_DATE))?date("Y-m-d",strtotime($row->ORDER_DATE)):'' ?></td>
                    <td class="cen"><?= $row->ACT_NAME ?></td>
                    <td class="cen"><?= $row->CUST_NM ?></td>
                    <td class="cen"><?= (!empty($row->START_DATE))?date("Y-m-d",strtotime($row->START_DATE)):'' ?></td>
                    <td class="cen"><?= (!empty($row->END_DATE))?date("Y-m-d",strtotime($row->END_DATE)):'' ?></td>
                    <td class="cen">N</td>
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


<!-- <div class="pagination2">	
	<?php
	if($this->data['cnt'] > 20){
	?>
	<div class="limitset">
		<select name="per_page">
			<option value="20" <?= ($perpage == 20)?"selected":"";?>>20</option>
			<option value="30" <?= ($perpage == 30)?"selected":"";?>>30</option>
			<option value="80" <?= ($perpage == 80)?"selected":"";?>>80</option>
			<option value="100" <?= ($perpage == 100)?"selected":"";?>>100</option>
		</select>
	</div>
	<?php
	}	
	?>
	<?= $this->data['pagenation'];?>
</div> -->



<div id="pop_container">

	<div id="info_content" class="info_content" style="height:auto;">

		<div class="ajaxContent">


		</div>

	</div>

</div>


<script>
$("input").attr("autocomplete", "off");

$(".link_hover").click(function () { 
	var idx = $(this).data("idx");
	var hidx = $(this).data("hidx");

	$.ajax({
		url: "<?= base_url('PROD/detail_workorder') ?>",
		type: "POST",
		dataType: "HTML",
		data: {
			idx:idx,
			hidx:hidx
		},
		success: function(data) {
			$("#ajax_detail_container").empty();
				$("#ajax_detail_container").html(data);
		},
		error: function(xhr, textStatus, errorThrown) {
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	})

});


$(".add_order").on("click", function() {

	$(".ajaxContent").html('');
	$("#pop_container").fadeIn();
	$(".info_content").animate({
		top: "50%"
	}, 500);

	$.ajax({
		url: "<?= base_url('PROD/order_form') ?>",
		type: "POST",
		dataType: "HTML",
		data: {
		},
		success: function(data) {
			$(".ajaxContent").html(data);
		},
		error: function(xhr, textStatus, errorThrown) {
			alert(xhr);
			alert(textStatus);
			alert(errorThrown);
		}
	})

});


//제이쿼리 수신일 입력창 누르면 달력 출력
$(".calendar").datetimepicker({
    format: 'Y-m-d',
    timepicker: false,
    lang: 'ko-KR'
});
</script>
