<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<div id="pageTitle">
	<h1><?= $title; ?></h1>
</div>


<div class="bdcont_50">
	<div class="bc__box">
		<div id="ajax_head_container"></div>
	</div>
</div>

<div class="bdcont_50">
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
	var headpage = 0;
	var headlimit = 0;
	var datailpage = 0;
	var dataillimit = 0;

	$(document).ready(function() {
		load();
	});

	function load() {
		$(".xdsoft_datetimepicker").remove();

		var formData = new FormData($("#headForm")[0]);
		console.log("경로 : <?= $pos ?>" + "/<?= $subpos ?>");
		console.log("호출 경로 : /<?= $pos ?>/head_<?= $subpos ?>")

		formData.append('pageNum', headpage);
		if (headlimit != 0) {
			formData.append('perpage', headlimit);
		}

		$.ajax({
			url: "<?= base_url('/' . $pos . '/head_' . $subpos . '/'.$ssubpos) ?>",
			type: "post",
			data: formData,
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				$("#ajax_head_container").html(data);
			}
		});


		var formData = new FormData($("#detailForm")[0]);
		$.ajax({
			url: "<?= base_url('/' . $pos . '/detail_' . $subpos . '/') ?>",
			type: "post",
			data: formData,
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				$("#ajax_detail_container").empty();
				$("#ajax_detail_container").html(data);
			}
		});
	}




	//head 검색
	$(document).on("click", ".head_search", function() {
		$(".xdsoft_datetimepicker").remove();

		var formData = new FormData($("#headForm")[0]);
		
		if(new Date($("input[name='edate']").val()).getTime() < new Date($("input[name='sdate']").val()).getTime())
		{
			alert("To 날짜가 From 날짜보다 작을수 없습니다.");
			return;
		}

		for (var i of formData.entries())
			console.log(i[0] + ", " + i[1]);

		$.ajax({
			url: "<?= base_url('/' . $pos . '/head_' . $subpos . '/'.$ssubpos) ?>",
			type: "post",
			data: formData,
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				$('#ajax_head_container').empty();
				$("#ajax_head_container").html(data);
			}
		});

		$.ajax({
			url: "<?= base_url('/' . $pos . '/detail_' . $subpos . '/') ?>",
			type: "post",
			data: "",
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				$("#ajax_detail_container").empty();
				$("#ajax_detail_container").html(data);
			}
		});
	});

	//detail 검색
	$(document).on("click", ".detail_search", function() {
		$(".xdsoft_datetimepicker").remove();

		var formData = new FormData($("#detailForm")[0]);
		for (var i of formData.entries())
			console.log(i[0] + i[1]);
		$.ajax({
			url: "<?= base_url('/' . $pos . '/detail_' . $subpos . '/') ?>",
			type: "post",
			data: formData,
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				$("#ajax_detail_container").empty();
				$("#ajax_detail_container").html(data);
			}
		});
		return false;
	});


	//head Load
	function load1() {
		var formData = new FormData($("#headForm")[0]);

		

		console.log("경로 : <?= $pos ?>" + "/<?= $subpos ?>");
		console.log("호출 경로 : /<?= $pos ?>/head_<?= $subpos ?>")
		$.ajax({
			url: "<?= base_url('/' . $pos . '/head_' . $subpos . '/') ?>",
			type: "post",
			data: formData,
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				$("#ajax_head_container").html(data);
			}
		});
	}


	//detail Load
	function load2() {
		var formData = new FormData($("#detailForm")[0]);
		for (var i of formData.entries())
			console.log(i[0] + ", " + i[1]);
		$.ajax({
			url: "<?= base_url('/' . $pos . '/detail_' . $subpos . '/') ?>",
			type: "post",
			data: formData,
			dataType: "html",
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				$("#ajax_detail_container").empty();
				$("#ajax_detail_container").html(data);
			}
		});
	}

	//헤드페이지넘김
	$(document).on("click", ".pageBtn", function() {
		headpage = $(this).data('page');
		load();
	});
	//헤드페이지 항목 수
	$(document).on("change", ".limitset select", function() {
		headlimit = $(this).val();
		headpage = 0;
		load();
	});
	//디테일페이지넘김
	$(document).on("click", "#ajax_detail_container .pageBtn", function() {
		datailpage = $(this).data('page');
		load2();
	});
	//디테일페이지 항목 수
	$(document).on("change", "#ajax_detail_container .limitset select", function() {
		detaillimit = $(this).val();
		detailpage = 0;
		load2();
	});
	
</script>