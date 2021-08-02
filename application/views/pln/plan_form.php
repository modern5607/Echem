<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>

<style>
label{color: #999;font-weight: 600;}
select{padding: 4px 10px;border: 1px solid #ddd;}
/* .tbl-content{padding: 10px;} */
.tbl-content div{ padding: 5px;}
.tbl-content div button{padding: 4px;}
h2{width: 800px;}
</style>
<h2 style="width: 900px;">
    <?= $title; ?>
    <span class="material-icons close">clear</span>
</h2>

<div class="tbl-content" style="width: 350px; float: left; margin-top: 38px; margin-left: 10px;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%">
        <thead>
            <tr>
                <th>주차</th>
                <th>품목</th>
                <th>건수</th>
                <th>수량</th>
                <th>중량</th>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($List as $i => $row) {
            ?>
                <tr class="link_s2" data-week="<?=$row->WEEK?>" data-date="<?=$str['date']?>">
                    <td class="cen"><?= $row->WEEK ?></td>
                    <td class="cen"><?= $row->DESC ?></td>
                    <td class="cen"><?= number_format($row->COUNT)?></td>
                    <td class="cen"><?= number_format($row->AMOUNT + 0) ?></td>
                    <td class="cen"><?= number_format($row->WEIGHT + 0) ?></td>
                </tr>
            <?php
            } ?>
        </tbody>
    </table>

</div>

<div style="float: right;">

        <button type="button" class="search_submit plan_change" style="padding:5px; margin-top:5px;margin-right:6px;">계획일 변경</button>
    </div>
<div class="tbl-content" style="width: 510px; float: right;margin-right: 10px; margin-top:3px">
    

    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="plan_form2">
        <thead>
            <tr>
                <!-- <th>주차</th> -->
                <th>당초 계획일</th>
                <th>변경 계획일</th>
                <th>Por No</th>
                <th>SEQ</th>
                <th>중량</th>
                <th>수량</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>

</div>


<script>

    $(".link_s2").click(function(){
        $(".link_s2").removeClass("over");
		$(this).addClass("over");

        var year = $(this).data("date").split('-')[0];
        var week = $(this).data("week");
        console.log(year);
        console.log(week);

        $.ajax({
            type: "post",
            url: "<?=base_url("PLN/plan_form2") ?>",
            data: {
                year:year,
                week:week
            },
            dataType: "html",
            success: function (data) {
                $(".plan_form2 tbody").html('');
                var dataset = JSON.parse(data);
                // console.log(dataset);
                var html = "";
                if (dataset.length > 0) {
                    $.each(dataset, function(index, info) {

                        html += "<tr>";
                        html += "<td class='cen'>"+info.PLAN_DA+"</td>";
                        html += "<td class='cen'><input type='date' style='width:125px' name='cdate'></td>";
                        html += "<td class='cen'><input type='hidden' name='no' value='"+info.POR_NO+"'>"+info.POR_NO+"</td>";
                        html += "<td class='cen'><input type='hidden' name='seq' value='"+info.POR_SEQ+"'>"+info.POR_SEQ+"</td>";
                        html += "<td class='right'>"+(info.WEIGHT*1)+"</td>";
                        html += "<td class='right'>"+info.PO_QTY*1+"</td>";
                        html += "</tr>";
                    });
                } else {
                    html +=
                        "<tr><td colspan='8' style='text-align:center; color:#999;'>데이터가 없습니다.</td></tr>"
                }
                // html += "<script>";
                // html += "$('.calendar').datetimepicker({format: 'Y-m-d',timepicker: false,language: 'ko'})";
                // html += "<\/script>";
                $(".plan_form2 tbody").html(html);
            }
        });
    
    });

    $(".plan_change").click(function (){
        let pattern = /^(19|20)\d{2}-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[0-1])$/;   //날짜 정규식
        // console.log(new Date().getFullYear()+2+'-01-01');
        var jsonArray = new Array();
        var tr_cnt = $(".plan_form2 tbody tr").length;
        var count=0;
        for(var i=0;i<tr_cnt;i++)
        {
            var date = $("input[name=cdate]").eq(i).val();

            if($("input[name=cdate]").eq(i).val() == '')
            {
                continue;
            }
            if(pattern.test(date) == false)
            {
                alert("날짜 형식이 다릅니다\n형식을 맞춰 주세요\n예시 : 2020-12-31");
                $("input[name=cdate]").eq(i).focus();
                return;
            }
            if(date != '')
            {
                if((date <='2021-01-01'&& date >= '0000-00-00')|| date > (new Date().getFullYear()+2+"")+'-01-01')
                {
                    var check = confirm("날짜가 과하게 설정되었습니다. 날짜가 확실하십니까?\n"+$("input[name=cdate]").eq(i).val());
                    if(check ==false)
                    {
                        $("input[name=cdate]").eq(i).focus();
                        return;
                    }
                }
                var jsonObj = new Object();
                jsonObj.porno = $("input[name=no]").eq(i).val();
                jsonObj.seq = $("input[name=seq]").eq(i).val();
                jsonObj.date = $("input[name=cdate]").eq(i).val();
                jsonArray.push(jsonObj);
                count++;
            }
        }
        if(count == 0)
        {
            alert("최소 하나이상 입력해 주세요");
            return;
        }
        var json = JSON.stringify(jsonArray);
        console.log(jsonArray[0]["date"]);
        var msg='';
        for(var i=0;i<jsonArray.length;i++)
        {
            msg +="POR NO: "+jsonArray[i]['porno']+" | POR SEQ: "+jsonArray[i]['seq']+" | 변경 계획일: "+jsonArray[i]['date']+"\n";
        }

        msg +="변경 하시겠습니까?"

        var check = confirm(msg);
        if(!check)
            return;

        $.ajax({
            type: "POST",
            url: "<?= base_url('PLN/plan_up')?>",
            // contentType:'application/json',
            data: {data:json},
            dataType: "json",
            success: function (data) {
                // console.log(data);
                if(data==1)
                {
                    alert("정상적으로 변경되었습니다.");
                    $(".ajaxContent").html('');
                    $("#pop_container").fadeOut();
                    $(".info_content").css("top", "-50%");
                    load();
                }

            }
        });
    })

    $(document).on("click", "h2 > span.close", function() {
        $(".ajaxContent").html('');
        $("#pop_container").fadeOut();
        $(".info_content").css("top", "-50%");
    });



</script>