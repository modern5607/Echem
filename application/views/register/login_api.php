<div style = " position: fixed; height: 100%; width: 100%; background: #fff; left: 0; top: 0"></div>
<script>

    var param1 = {
        'apiKey'    : "0x90629539260EC32337962D1B17A5025D60C36F72877E997218273FF8E3953498",
        'bizNumber' : "2021_00362",
        'kpiDate'   : "<?= date("Ymd") ?>",
        'kpiCode'   : "P004",   // 설비 가동률 
        'kpiValue'  : "154"        // 실제가동시간/총가동시간x100
    };

    var param2 = {
        'apiKey'    : "0x90629539260EC32337962D1B17A5025D60C36F72877E997218273FF8E3953498",
        'bizNumber' : "2021_00362",
        'kpiDate'   : "<?= date("Ymd") ?>",
        'kpiCode'   : "C006",   // 작업 공수 (단위당 투입공수) 
        'kpiValue'  : "13.8"        // 총투입공수/생산량
    };

    function ajaxx(param) {
        $.ajax({
            type : "POST",
            url : "https://cors-anywhere.herokuapp.com/http://smartapi.jntp.or.kr/api/KPI_API_SUPY",
            cache : false,
            timeout : 360000,
            data : param,
            dataType : "json",
            contentType : "application/x-www-form-urlencoded; charset=utf-8",
            beforeSend : function() {
            },
            success : function(data, textStatus, jqXHR) {
                var result = data;
                console.log(param);  	// <-- 전송 값 확인
                console.log(result);  	// <-- 전송 결과 확인
            },
            error : function(jqXHR, textStatus, errorThrown) {
            },
            complete : function() {
                if(param['kpiCode'] == "C006"){
					location.href = "<?= base_url('')?>";
				}
            }
        });
    }



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
            'conectIp' : "112.164.79.101",
            // 'conectIp' : data.ip,
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
                    ajaxx(param1);
                    ajaxx(param2);
                    // location.href = "<?= base_url('')?>";
                }
            });
        })
    }
    loadCountry();


    
        

</script>