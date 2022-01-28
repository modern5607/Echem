<link href="<?php echo base_url('_static/css/jquery.datetimepicker.min.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/js/jquery.datetimepicker.full.min.js') ?>"></script>

<div id="pageTitle">
    <h1><?php echo $title; ?></h1>
</div>


<div class="bdcont_100">
    <div class="bc__box">
        <div id="ajax_container"></div>
    </div>
</div>


<script>
    var formData = '';
    var page = 0;
    var limit = 0;

    function load() {
        // for (var i of formData.entries())
        //     console.log(i[0] + ", " + i[1]);


        $(".xdsoft_datetimepicker").remove();

        formData.append('pageNum', page);
        if (limit != 0) {
            formData.append('perpage', limit);
        }

        $.ajax({
            url: "<?= (empty($pos) && empty($subpos)) ? base_url("/MDM/ajax_calendar") : base_url('/' . $pos . '/ajax_' . $subpos . '/') ?>",
            type: "post",
            data: formData,
            dataType: "html",
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                $("#ajax_container").html(data);
            }
        });
    }

    $(document).ready(function() {
        formData = new FormData($("#ajaxForm")[0]);
        load();
    });
    //검색
    $(document).off("click", ".ajax_search");
    $(document).on("click", ".ajax_search", function() {
        formData = new FormData($("#ajaxForm")[0]);
        page = 0;
        load();
    });
    //페이지넘김
    $(document).off("click", ".pageBtn");
    $(document).on("click", ".pageBtn", function() {
        page = $(this).data('page');
        load();
    });
    //페이지 항목 수
    $(document).off("click", ".limitset select");
    $(document).on("change", ".limitset select", function() {
        limit = $(this).val();
        page = 0;
        load();
    });




    $(document).on("click", ".page-link", function() {
        var page = $(this).data("startpage");
        $.ajax({
            type: "POST",
            url: "<?= base_url('/' . $pos . '/ajax_' . $subpos . '/') ?>",
            data: {
                pageNum: page
            },
            dataType: "html",
            success: function(data) {
                // $('#ajax_container').html('');
                $('#ajax_container').html(data);
            }
        });
    });

    $(document).on("click", ".prev-link", function() {
        var page = $(this).data("startpage");
        var pagegroup = $(this).data("pagegroup");
        $.ajax({
            type: "POST",
            url: "<?= base_url('/' . $pos . '/ajax_' . $subpos . '/') ?>",
            data: {
                pageNum: page,
                pagegroup: pagegroup
            },
            dataType: "html",
            success: function(data) {
                $('#ajax_container').html(data);
            }
        });
    });

    $(document).on("click", ".next-link", function() {
        var page = $(this).data("startpage");
        var pagegroup = $(this).data("pagegroup");
        $.ajax({
            type: "POST",
            url: "<?= base_url('/' . $pos . '/ajax_' . $subpos . '/') ?>",
            data: {
                pageNum: page,
                pagegroup: pagegroup
            },
            dataType: "html",
            success: function(data) {
                $('#ajax_container').html(data);
            }
        });
    });
</script>