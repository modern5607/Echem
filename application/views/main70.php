<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<div id="pageTitle">
	<h1><?= $title; ?></h1>
</div>


<div class="bdcont_80">
	<div class="bc__box">
		<div id="ajax_head_container"></div>
	</div>
</div>

<div class="bdcont_20">
	<div class="bc__box">
		<div id="ajax_detail_container"></div>
	</div>
</div>

<div id="pop_container">

    <div class="info_content" style="height:auto;">
        <div class="ajaxContent">

            <!-- 데이터 -->

        </div>
    </div>

</div>



<script>
var headData = '';
var detailData = '';
var headpage=0;
var headlimit=0;
var datailpage=0;
var dataillimit=0;

function load()
{
	$(".xdsoft_datetimepicker").remove();

	headData = new FormData($("#headForm")[0]);

	headData.append('pageNum', headpage);
	if(headlimit != 0){
		headData.append('perpage', headlimit);
	}

	$.ajax({
		url: "<?= base_url('/' . $pos . '/head_' . $subpos . '/') ?>",
		type: "post",
		data : headData,
		dataType: "html",
		cache  : false,
		contentType : false,
		processData : false,
		success: function(data) {
			$("#ajax_head_container").html(data);
		}
	});

	detailData = new FormData($("#detailForm")[0]);
	
	detailData.append('pageNum', datailpage);
	if(dataillimit != 0){
		detailData.append('perpage', dataillimit);
	}

	$.ajax({
		url: "<?= base_url('/' . $pos . '/detail_' . $subpos . '/') ?>",
		type: "post",
		data : detailData,
		dataType: "html",
		cache  : false,
		contentType : false,
		processData : false,
		success: function(data) {
			$("#ajax_detail_container").html(data);
		}
	});
}
	$(document).ready(function() { 
        headData = new FormData($("#headForm")[0]);
        datailData = new FormData($("#detailForm")[0]);
        load();
    });
	//헤드검색
	$(document).off("click", ".head_search");
	$(document).on("click", ".head_search", function() {
        headData = new FormData($("#headForm")[0]);
        datailData = '';
		headpage = 0;
		detailpage = 0;
		load();
	});
	//디테일검색
	$(document).off("click", ".detail_search");
	$(document).on("click", ".detail_search", function() {
        datailData = new FormData($("#detailForm")[0]);
		detailpage = 0;
		load();
	});
	//헤드페이지넘김
	$(document).off("click", ".pageBtn");
	$(document).on("click", ".pageBtn", function() {
		headpage = $(this).data('page');
		load();
	});
	//헤드페이지 항목 수
	$(document).off("change", ".limitset select");
	$(document).on("change", ".limitset select", function() {
		headlimit = $(this).val();
		headpage = 0;
		load();
	});
	//디테일페이지넘김
	$(document).off("click", "#ajax_detail_container .pageBtn");
	$(document).on("click", "#ajax_detail_container .pageBtn", function() {
		datailpage = $(this).data('page');
		load();
	});
	//디테일페이지 항목 수
	$(document).off("change", "#ajax_detail_container .limitset select");
	$(document).on("change", "#ajax_detail_container .limitset select", function() {
		detaillimit = $(this).val();
		detailpage = 0;
		load();
	});
</script>