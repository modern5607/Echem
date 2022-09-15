<div style = " position: fixed; height: 100%; width: 100%; background: #fff; left: 0; top: 0"></div>
<script>

    var today = new Date(+new Date() + 3240 * 10000).toISOString().replace("T", " ").replace("Z", "");

    function loadCountry() {
        $.ajax({
            "url": "https://api.ip.pe.kr/json/",
            "method": "GET"
        }).done(function (data) {
            var param = {
            'crtfcKey' : "$5$API$Qpu2EuGQpYnWq22fS690r/2KF/xAGdMjldCpqjULMs2",
            'logDt' : today,
            'useSe' : "Y",
            'sysUser' : "<?=$this->session->userdata('user_id')?>",
            'conectIp' : data.ip,
            'dataUsgqty' : "0"
            };
                    
            console.log(param)

            $.ajax({
                type : "POST",
                url : "https://log.smart-factory.kr/apisvc/sendLogData.json",
                cache : false,
                timeout : 360000,
                data : param,
                dataType : "json",
                contentType : "application/x-www-form-urlencoded; charset=utf-8",
                beforeSend : function() {
                },
                success : function(data, textStatus, jqXHR) {
                    var result = data.result;
                    console.log(result);  // <-- 전송 결과 확인
                },
                error : function(jqXHR, textStatus, errorThrown) {
                },
                complete : function() {
                    location.href = "<?= base_url('')?>";
                }
            });
        })
    }
    loadCountry();


    
        

</script>