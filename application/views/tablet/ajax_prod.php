<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<style>
</style>

<h2 class="tableth2">
    <?php echo $title; ?>
    <span class="material-icons close" style="font-size:50px">clear</span>
</h2>


<div class="formContainer">

    <form name="ajaxform" id="ajaxform">
        <div class="register_form_5">
            <fieldset class="form_5">
                <legend>이용정보</legend>
                <table class="nhover">
                    <tbody>
                        <tr>
                            <!-- <input type="hidden" name="idx" id="idx" value="<?= $info->IDX ?>"> -->
                            <th>날자</th>
                            <td>
                                <input type="text" name="" value="<?= $info[0]->col1 ?>" class="form_input5 input_5" autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <th>완제품</th>
                            <td>
                                <input type="text" name="" value="<?= $info[0]->col1 ?>" class="form_input5 input_5" autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <th>KG</th>
                            <td>
                                <input type="text" name="" value="<?= $info[0]->col1 ?>" class="form_input5 input_5" autocomplete="off">
                            </td>
                        </tr>
                        <tr>
                            <th>실적입력일</th>
                            <td>
                                <input type="text" name="" value="<?= $info[0]->col1 ?>" class="form_input5 input_5" autocomplete="off">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </fieldset>

            <div class="bcont">
                <span id="loading"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></span>
                
                <span style="margin-top:10px;" class="btni_5 btn_right add_form" data-hidx="3">입력</span>
                
            </div>

        </div>

    </form>

</div>


<script>
var gjqty = $('.qty').val()*1;
    $(".qty:eq(0)").on("change", function() {
        var qty= $('.qty').val()*1;
        var bqty= $('.qty').eq(1).val()*1;

        if(qty < 1){
            $('.qty').eq(0).val(gjqty)
            return false;
        };
        if(bqty > qty){
            $('.qty').eq(1).val(qty)
            return false;
        };
    });
    $(".qty:eq(1)").on("change", function() {
        var qty= $('.qty').val()*1;
        var bqty= $('.qty').eq(1).val()*1;
        if(bqty < 1){
            $('.qty').eq(1).val(0)
            return false;
        };
        if(bqty > qty){
            $('.qty').eq(1).val(qty)
            return false;
        };
    });




    $(".submitBtn").on("click", function() {

        var formData = new FormData($("#ajaxform")[0]);
        var $this = $(this);
        if($("select[name=GB]").val() == '' && $(".qty:eq(1)").val() > 0){
            alert("불량 이유를 선택해주세요.")
            return false;
        }
        if($("select[name=GB]").val() != '' && $(".qty:eq(1)").val() <= 0){
            alert("불량수량을 입력해주세요.")
            return false;
        }

        $.ajax({
            url: "<?php echo base_url('/Tablet/add_sh_order') ?>",
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

                var jsonData = JSON.parse(data);
                if (jsonData.status == "ok") {

                    setTimeout(function() {
                        // alert(jsonData.msg);
                        $(".ajaxContent").html('');
                        $("#pop_container").fadeOut();
                        $(".info_content").css("top", "-50%");
                        $("#loading").hide();
                        location.reload();

                    }, 1000);

                    chkHeadCode = false;

                }
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        });
    });
</script>