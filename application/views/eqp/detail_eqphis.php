<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<style>
    .re {
        color: red;
        top: 5px;
        right: 10px;
    }

    .re:after {
        content: "*";
        font-size: 16px;
        font-weight: 600;
    }
/* 
    #detailForm2 .tbl-content th {
        background: white;
    }

    #detailForm2 .tbl-content td {
        background: white;
    } */
    .tbl-content table td {
        white-space: nowrap;
    }
</style>

<header style="margin-bottom: 0px;">
    <div class="searchDiv">
        <form id="detailForm">
            <div style="height: 34px; padding-top: 0px;">
            <?php
            if(!empty($List)){ ?>
                <span class="btn print download"><i class="material-icons">get_app</i>출력</span>
            
            <?php }?>
            </div>

        </form>
    </div>
</header>

    <div class="tbl-content">
        <table cellpadding="0" cellspacing="0" border="0" width="100%" id="excel">
            <thead>
                <tr>
                    <th style="width: 7%;">순번</th>
                    <th>생성일</th>
                    <th>교정일</th>
                    <th>작성자</th>
                    <th>검토자</th>
                    <th>승인자</th>
                    <th>비고</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (!empty($List)) {
                    foreach ($List as $i => $row) {
                        $num = $i+1; ?>
                        <tr>
                            <td class="cen"><?= $num ?></td>
                            <td><?= (empty($row->INSERT_DATE) || $row->INSERT_DATE=='0000-00-00')?"":date("Y-m-d",strtotime($row->INSERT_DATE)) ?></td>
                            <td><?= (empty($row->CORDATE) || $row->CORDATE=='0000-00-00')?"":date("Y-m-d",strtotime($row->CORDATE)) ?></td>
                            <td><?= $row->WRITER ?></td>
                            <td><?= $row->REVIEWER ?></td>
                            <td><?= $row->APPROVER ?></td>
                            <td><?= $row->REMARK ?></td>
                        </tr>

                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="15" class="list_none">등록된 장비가 없습니다</td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>



<script>
$('.download').click(function() {
    
		fnExcelReport('excel', '검교정 이력현황 [<?= (empty($str['MNGNUM'])?"?":$str['MNGNUM']).', '.(empty($str['NAME'])?"?":$str['NAME']) ?>]');
	});
	function fnExcelReport(id, title) {
		var tab_text = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
		tab_text = tab_text + '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">';
		tab_text = tab_text + '<xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet>'
		tab_text = tab_text + '<x:Name>Test Sheet</x:Name>';
		tab_text = tab_text + '<x:WorksheetOptions><x:Panes></x:Panes></x:WorksheetOptions></x:ExcelWorksheet>';
		tab_text = tab_text + '</x:ExcelWorksheets></x:ExcelWorkbook></xml></head><body>';
		tab_text = tab_text + "<table border='1px'>";
		var exportTable = $('#' + id).clone();
		exportTable.find('input').each(function(index, elem) {
			$(elem).remove();
		});
		tab_text = tab_text + exportTable.html();
		tab_text = tab_text + '</table></body></html>';
		var data_type = 'data:application/vnd.ms-excel';
		var ua = window.navigator.userAgent;
		var msie = ua.indexOf("MSIE ");
		var fileName = title + '.xls';
		//Explorer 환경에서 다운로드
		if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
			if (window.navigator.msSaveBlob) {
				var blob = new Blob([tab_text], {
					type: "application/csv;charset=utf-8;"
				});
				navigator.msSaveBlob(blob, fileName);
			}
		} else {
			var blob2 = new Blob([tab_text], {
				type: "application/csv;charset=utf-8;"
			});
			var filename = fileName;
			var elem = window.document.createElement('a');
			elem.href = window.URL.createObjectURL(blob2);
			elem.download = filename;
			document.body.appendChild(elem);
			elem.click();
			document.body.removeChild(elem);
		}
	}
</script>