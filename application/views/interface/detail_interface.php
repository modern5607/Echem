<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- google charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div id="Line_Controls_Chart">
  <!-- 라인 차트 생성할 영역 -->
  <div id="lineChartArea"></div>
  <!-- 컨트롤바를 생성할 영역 -->
  <div id="controlsArea" style="height:140px; padding-top:40px; display:none;"></div>
</div>


<script>
  //  $("#AAAA").css({'display':'none'});
  var chartDrowFun = {

    chartDrow: function() {
      var chartData = '';
      //날짜형식 변경하고 싶으시면 이 부분 수정하세요.
      var chartDateformat = 'HH:mm:ss';
      //라인차트의 라인 수
      var chartLineCount = 10;
      //컨트롤러 바 차트의 라인 수
      var controlLineCount = 10;

      function drawDashboard() {

        var data = new google.visualization.DataTable();
        //그래프에 표시할 컬럼 추가
        data.addColumn('date', '날짜');
        data.addColumn('number', '온도');
        data.addColumn('number', '수위');
        data.addColumn('number', 'PH');
        data.addColumn('number', 'CL');
        data.addColumn('number', '압력');


        //그래프에 표시할 데이터
        var dataRow = [];


        <?php
        foreach ($info as $row) { ?>

          var year = '<?= $row->Y; ?>';
          var month = '<?= $row->M; ?>';
          var day = '<?= $row->D; ?>';
          var hour = '<?= $row->H; ?>';
          var min = '<?= $row->I; ?>';
          var sec = '<?= $row->S; ?>';

          var temp = <?= number_format($row->TEMP); ?>;
          var level = <?= number_format($row->LEVEL); ?>;
          var ph = <?= number_format($row->PH); ?>;
          var cl = <?= number_format($row->CL); ?>;
          var press = <?= number_format($row->PRESS); ?>;

          dataRow = [new Date(year, month, day, hour, min, sec), temp, level, ph, cl, press];
          data.addRow(dataRow);

        <?php } ?>


        var chart = new google.visualization.ChartWrapper({
            chartType: 'LineChart',
            containerId: 'lineChartArea', //라인 차트 생성할 영역
            options: {
              // 백분율 전체값 100%에서 퍼센티지로 보여줌
              //isStacked     : 'percent',
              //마우스 오버시 모양
              focusTarget: 'category',
              width: '100%',
              height: 500,
              legend: {
                position: "top",
                textStyle: {
                  fontSize: 13
                }
              },
              pointSize: 3,
              tooltip: {
                textStyle: {
                  fontSize: 12
                },
                showColorCode: true,
                trigger: 'both'
              },
              chartArea: {
                'width': '80%',
                'height': '90%'
              },
              // animation     : {startup: true, duration: 500, easing: 'in'},
              //차트 행
              hAxis: {
                format: 'HH시mm분',
                gridlines: {
                  count: 5
                }
              },
              //     textStyle   : {fontSize:12}},
              //   //차트 열
              vAxis: {
                minValue: 1,
                viewWindow: {
                  min: -50
                },
                gridlines: {
                  count: 10
                },
                // textStyle   : {fontSize:12}
              }
              //   annotations   : {pattern: chartDateformat,
              //     textStyle   : {
              //       fontSize  : 15,
              //       bold      : true,
              //       italic    : true,
              //       color     : '#871b47',
              //       auraColor : '#d799ae',
              //       opacity   : 0.8,
              //       pattern   : chartDateformat
              //     }
              //   }
            }
          }

        );

        var control = new google.visualization.ControlWrapper({
          controlType: 'CategoryFilter',
          containerId: 'controlsArea', //control bar를 생성할 영역
          options: {
            ui: {
              mamRangeSize: 5,
              chartType: 'LineChart',
              chartOptions: {
                chartArea: {
                  'width': '80%',
                  'height': 80
                },
                hAxis: {
                  'baselineColor': 'none',
                  format: chartDateformat,
                  textStyle: {
                    fontSize: 12
                  },
                  gridlines: {
                    count: controlLineCount,
                    units: {
                      //years       : {format: ['yyyy년']},
                      //months      : {format: ['MM월']},
                      days: {
                        format: ['dd일']
                      }
                    }
                  }
                }
              }
            },
            filterColumnIndex: 0
          }
        });


        var date_formatter = new google.visualization.DateFormat({
          pattern: chartDateformat
        });
        date_formatter.format(data, 0);

        var dashboard = new google.visualization.Dashboard(document.getElementById('Line_Controls_Chart'));
        window.addEventListener('resize', function() {
          dashboard.draw(data);
        }, false); //화면 크기에 따라 그래프 크기 변경
        dashboard.bind([control], [chart]);
        dashboard.draw(data);

      }
      google.charts.setOnLoadCallback(drawDashboard);

    }
  }


  $(document).ready(function() {
    google.charts.load('current', {
      'packages': ['line', 'controls']
    });
    chartDrowFun.chartDrow(); //chartDrow() 실행
  });
</script>