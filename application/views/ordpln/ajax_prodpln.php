<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<style>
#calendar{ width:100%; height:100%;}
#calendar tbody td{background:#f8f8f8; vertical-align:top; width:14.285%; height: 90px; text-align:left; padding:5px;}
#calendar tbody tr.week td{height:40px; padding:0px; line-height:40px; text-align:center; background:#ddd;}
.other-month{color:#999;}
.highlight{font-weight:600;}
.moveBtn{ background:#fb7c7c; padding:5px 10px; }
.headset th{padding-bottom:20px;}
.headset a{color:#fff}

.xday{cursor:pointer;}
#calendar tbody tr.week td.sun{background:#fb7c7c; color:#fff;}
#calendar tbody tr.week td.sat{background:#aab7dc; color:#fff;}

#calendar tbody tr td:nth-child(1){color:#fb7c7c;}
#calendar tbody tr td:nth-child(7){color:#aab7dc;}

</style>

<div class="bc_header">

</div>
<br><br>
<div class="bc_cont">
    <div class="cont_header"></div>
    <div class="cont_body">

        <?php echo $calendar;?>

    </div>
</div>

<div id="pop_container">

    <div id="info_content" class="info_content" style="height:auto;">

        <div class="ajaxContent"></div>

    </div>

</div>


<script>

$(".btn").click(function (){
    var date = $(this).data("date");
    var year = date.split("-")[0];
    var month = date.split("-")[1];

    // console.log(year + month);

    $.ajax({
        type: "post",
        url: "<?= base_url('MDM/ajax_calendar')?>",
        data: {
            year:year,
            month:month
        },
        dataType: "html",
        success: function (data) {
            $(".bc_header").parent().html(data);
        }
    });

});

$(".xday").on("click", function() {

    var xdate = $(this).data("date");

    $("#pop_container").fadeIn();
    $(".info_content").animate({
        top: "50%"
    }, 500);

    $.ajax({
        url: "<?php echo base_url('MDM/calendar_form')?>",
        type: "post",
        dataType: "html",
        data: {
            xdate: xdate
        },
        success: function(data) {
            $(".ajaxContent").html(data);
        }

    });
});


$(document).on("click", "h2 > span.close", function() {

    $(".ajaxContent").html('');
    $("#pop_container").fadeOut();
    $(".info_content").css("top", "-50%");
    location.reload();

});
</script>