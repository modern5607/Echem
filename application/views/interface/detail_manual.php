<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>



<div class="bdcont_100" style="padding: 0;">
    <div id="loading" style="margin: 170px 0px;"><img src='<?php echo base_url('_static/img/loader.gif'); ?>' width="100"></div>
    <input type="hidden" name="idx" value="<?= $idx ?>">

    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <thead>
                <tr>
                    <th width="60px;">NO</th>
                    <th>일자</th>
                    <th width="25%">EC(Sensor)</th>
                    <th width="25%">EC(Manual)</th>
                </tr>
            </thead>
            <tbody id="MANUAL">
                <?php
                $num = 1;
                foreach ($List as $i => $row) {
                ?>
                    <tr class="MANUAL<?= $num ?>"  data-sensordate="<?= $row->SENSOR_DATE ?>" data-sensor="<?= $row->SENSOR ?>" >
                        <td class="cen"><?= $num ?></td>
                        <td class="cen"><?= $row->INSERT_D ?></td>
                        <td class="right"><?= $row->SENSOR ?></td>
                        <td class="cen"><input name="manual<?= $num++ ?>" type="number" style="width:100%" class="right" 
                        value="<?= isset($row->MANUAL) ? $row->MANUAL : ""; ?>"></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <?php if(!empty($List)){?>
                        <td colspan="4" class="cen" style="padding: 15px;"><button type="button" class="btn blue_btn submitBtn">등록</button></td>
                    <?php }else{ ?>
                            <td colspan="4" class="cen" style="padding: 15px;">데이터가 없습니다.</td>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
        
</div>



<script>
    $(".submitBtn").click(function() {
		var idx = $(".over").data("idx");
		var slave = $(".over").data("slave");

        var tbody = document.getElementById('MANUAL');
        var rows = tbody.getElementsByTagName('tr');
      
        var formData = new FormData();
        formData.append("IDX", $("input[name='idx']").val());
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var className = 'MANUAL' + (i + 1);
            if (row.classList.contains(className)) {
                formData.append("SENSORDATE" + (i + 1), $("." + className).data("sensordate"));
                formData.append("SENSOR" + (i + 1), $("." + className).data("sensor"));
                formData.append(className, $("input[name='" + className.toLowerCase() + "']").val());
            }
        }

		// FormData의 값 확인
		for (var pair of formData.entries()) {
			console.log(pair[0] + ', ' + pair[1]);
		}

        $.ajax({
            url: "<?= base_url('_INTERFACE/ec_up') ?>",
            type: "POST",
            dataType: "HTML",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data == 1){
                    alert("등록되었습니다");
                    chart();
                }
                else
                alert("실패");
            },
            error: function(xhr, textStatus, errorThrown) {
                alert(xhr);
                alert(textStatus);
                alert(errorThrown);
            }
        })

    });


	$(document).off("click", 'input[name="manual"]');
    $('input[name="manual"]').on('input', function() {
      $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });
    

</script>