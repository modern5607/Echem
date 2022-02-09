<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>


<link href="<?= base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">

<script src="<?= base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?= base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>


<h2>
	<?= "품목등록"?>
	<span class="material-icons close">clear</span>
</h2>


<div class="formContainer">

	<form name="itemform" id="itemform">
		<input type="hidden" name="mod" value="<?= $mode ?>">
		<input type="hidden" name="idx" value="<?= $idx ?>">
		<div class="register_form">
			<fieldset class="form_1">
				<legend>이용정보</legend>
				<table>
					<tbody>
						<tr>
							<th><label class="l_id"><span class="res"></span>품목</label></th>
							<td>
								<input type="text" name="ITEM_NAME" id="ITEM_NAME" value="<?= isset($List->ITEM_NAME) ? $List->ITEM_NAME : ""; ?>" class="form_input input_100">
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">단위</label></th>
							<td>
                                <select name="UNIT" id="UNIT" style="padding:4px 10px; border:1px solid #ddd;">
                                    <?php foreach($UNIT as $i=>$row){ ?> 
                                        <option value="<?= $row->D_NAME ?>"><?= $row->D_NAME ?></option>
                                    <?php }?>

                                </select>
								<!-- <input type="text" name="UNIT" id="UNIT" value="<?= isset($data->NAME) ? $data->NAME : ""; ?>" class="form_input input_100"> -->
							</td>
						</tr>
						<tr>
							<th><label class="l_pw">사용유무</label></th>
							<td>
								<input type="radio" name="USE_YN" value="Y" <?= (isset($data->USE_YN) && $data->USE_YN == "Y") ? "checked" : ((empty($data->USE_YN)) ? "checked" : ""); ?>>사용
								<input type="radio" name="USE_YN" value="N" <?= (isset($data->USE_YN) && $data->USE_YN == "N") ? "checked" : ""; ?>>미사용
							</td>
						</tr>
						
					</tbody>
				</table>
			</fieldset>

			<div class="bcont">
				<span id="loading"><img src='<?= base_url('_static/img/loader.gif'); ?>' width="100"></span>
				<?php
				if ($mode =="mod") { //수정인경우
				?>
					<button type="button" class="submitBtn blue_btn">수정</button>
				<?php
				} else {
				?>
					<button type="button" class="submitBtn blue_btn">등록</button>
				<?php
				}
				?>
			</div>

		</div>

	</form>

</div>


<script>
	$("input").attr("autocomplete", "off");
	
	$(".submitBtn").on("click", function() {
        var $this = $(this);
		var formData = new FormData($("#itemform")[0]);

        var item_nm = $("#item_nm");

        if(item_nm.val() =="")
        {
            alert("품목을 입력해 주세요");
            item_nm.focus();
            return;
        }
        // for (var i of formData.entries())
		// 	console.log(i[0] +" "+ i[1]);

		$.ajax({
			url: "<?= base_url('MDM/item_update') ?>",
			type: "POST",
			data: formData,
			//asynsc : true,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$this.hide();
				$("#loading").show();
			},
			success: function(data) {
				if(data == 1)
				{
					alert("등록되었습니다.");
					$(".ajaxContent").html('');
					$("#pop_container").fadeOut();
					$(".info_content").css("top", "-50%");
					$("#loading").hide();
					load();

				}
				// 	setTimeout(function() {
				// 		alert(jsonData.msg);
				

				// 	}, 1000);

				// 	chkHeadCode = false;

				// }
			},
			error: function(xhr, textStatus, errorThrown) {
				alert(xhr);
				alert(textStatus);
				alert(errorThrown);
			}
		});
	});

	$(document).on("click", "h2 > span.close", function() {
		$(".ajaxContent").html('');
		$("#pop_container").fadeOut();
		$(".info_content").css("top", "-50%");
		// location.reload();

	});
</script>