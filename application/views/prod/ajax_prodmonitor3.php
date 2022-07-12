<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>


<link href="<?php echo base_url('_static/summernote/summernote-lite.css') ?>" rel="stylesheet">
<script src="<?php echo base_url('_static/summernote/summernote-lite.js') ?>"></script>
<script src="<?php echo base_url('_static/summernote/lang/summernote-ko-KR.js') ?>"></script>
<script src="../_static/js/go.js"></script>
<script src="../_static/js/Figures.js"></script>
<style type="text/css">
  .body_Content {
    background-color: #121415;
  }
</style>


<div class="bdcont_100">
  <div class="">
    <div id="allSampleContent" class="p-4 w-full">
      <!-- <script src="https://unpkg.com/gojs@2.2.11/extensions/Figures.js"></script> -->
      <script id="code">
        $(document).ready(function() {
          init();


          $.ajax({
            type: "post",
            url: "<?=base_url('PROD/load_monitor_setting')?>",
            dataType: "html",
            success: function (data) {
              console.log(data);
              document.getElementById("mySavedModel").value = data;
              load();
            }
          });

         
        });


        function init() {
          // console.log("init");
          // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
          // For details, see https://gojs.net/latest/intro/buildingObjects.html
          const $ = go.GraphObject.make; // for more concise visual tree definitions

          myDiagram =
            $(go.Diagram, "myDiagramDiv", {
              "grid.visible": false,
              "grid.gridCellSize": new go.Size(10, 10),
              "draggingTool.isGridSnapEnabled": false,
              "resizingTool.isGridSnapEnabled": true,
              "rotatingTool.snapAngleMultiple": 90,
              "rotatingTool.snapAngleEpsilon": 45,
              "undoManager.isEnabled": true,
              allowHorizontalScroll:false,
              allowVerticalScroll:false,
              // allowSelect:false,
              initialScale:1.4
            });

          // when the document is modified, add a "*" to the title and enable the "Save" button
          // myDiagram.addDiagramListener("Modified", e => {
          //   var button = document.getElementById("SaveButton");
          //   if (button) button.disabled = !myDiagram.isModified;
          //   var idx = document.title.indexOf("*");
          //   if (myDiagram.isModified) {
          //     if (idx < 0) document.title += "*";
          //   } else {
          //     if (idx >= 0) document.title = document.title.slice(0, idx);
          //   }
          // });

          myDiagram.nodeTemplateMap.add("Process",
            $(go.Node, "Auto", {
                locationSpot: new go.Spot(0.5, 0.5),
                locationObjectName: "SHAPE",
                resizable: true,
                resizeObjectName: "SHAPE"
              },
              new go.Binding("location", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),
              $(go.Shape, "Cylinder1", {
                  name: "SHAPE",
                  strokeWidth: 2,
                  fill: $(go.Brush, "Linear", {
                    start: go.Spot.Bottom,
                    end: go.Spot.Top,
                    0: "blue",
                    1: "lightgray"
                  }),
                  minSize: new go.Size(50, 50),
                  portId: "",
                  fromSpot: go.Spot.AllSides,
                  toSpot: go.Spot.AllSides
                }, new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify),new go.Binding("fill", "level", convertLinearBrush)),
              $(go.TextBlock, {
                  alignment: go.Spot.Top,
                  textAlign: "center",
                  font: "bold 14px dotum",
                  margin: 5,
                  editable: true
                }, new go.Binding("text").makeTwoWay())
            ));

         

          myDiagram.nodeTemplateMap.add("Table1", //카테고리명
          $(go.Part, "Auto",{},
          new go.Binding("location", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),
            $(go.Shape, { /*fill: "lightgray", */opacity:0.0, stroke: "lightgray", strokeWidth: 3 }),
            $(go.Panel, "Table",

              // drawn before row 1:
              $(go.RowColumnDefinition,
                { row: 1, separatorStrokeWidth: 1.5, separatorStroke: "black" }),

              // drawn before column 1:
              // $(go.RowColumnDefinition,
              //   { column: 1, separatorStrokeWidth: 1.5, separatorStroke: "black" }),

              $(go.TextBlock, "수위", { font: "bold 14px dotum",row: 1, column: 0, stroke: "green", margin: 2 }),
              $(go.TextBlock, "row 1 col 1", { font: "bold 14px dotum",row: 1, column: 1, margin: 2 },new go.Binding("text", "text1")),
              $(go.TextBlock, "온도", { font: "bold 14px dotum",row: 2, column: 0, stroke: "green", margin: 2 }),
              $(go.TextBlock, "row 2 col 1", { font: "bold 14px dotum",row: 2, column: 1, margin: 2 },new go.Binding("text", "text2")),
            )
          ));
          myDiagram.nodeTemplateMap.add("Table2",
          $(go.Part, "Auto",{},
          new go.Binding("location", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),
            $(go.Shape, { /*fill: "lightgray",*/opacity:0.0, stroke: "lightgray", strokeWidth: 3 }),
            $(go.Panel, "Table",

              // drawn before row 1:
              $(go.RowColumnDefinition,
                { row: 1, separatorStrokeWidth: 1.5, separatorStroke: "black" }),
              // // drawn before column 1:
              // $(go.RowColumnDefinition,
              //   { column: 1, separatorStrokeWidth: 1.5, separatorStroke: "black" }),

              $(go.TextBlock, "PH", { font: "bold 14px dotum", row: 1, column: 0, stroke: "green", margin: 2 }),
              $(go.TextBlock, "row 1 col 1", { font: "bold 14px dotum", row: 1, column: 1, margin: 2 },new go.Binding("text", "text1")),
              $(go.TextBlock, "Cl", { font: "bold 14px dotum", row: 2, column: 0, stroke: "green", margin: 2 }),
              $(go.TextBlock, "row 2 col 1", { font: "bold 14px dotum", row: 2, column: 1, margin: 2 },new go.Binding("text", "text2")),
              $(go.TextBlock, "온도", { font: "bold 14px dotum", row: 3, column: 0, stroke: "green", margin: 2 }),
              $(go.TextBlock, "row 3 col 1", { font: "bold 14px dotum", row: 3, column: 1, margin: 2 },new go.Binding("text", "text3")),
              $(go.TextBlock, "압력", { font: "bold 14px dotum", row: 4, column: 0, stroke: "green", margin: 2 }),
              $(go.TextBlock, "row 4 col 1", { font: "bold 14px dotum", row: 4, column: 1, margin: 2 },new go.Binding("text", "text4")),
              $(go.TextBlock, "수위", { font: "bold 14px dotum", row: 5, column: 0, stroke: "green", margin: 2 }),
              $(go.TextBlock, "row 5 col 1", { font: "bold 14px dotum", row: 5, column: 1, margin: 2 },new go.Binding("text", "text5")),
            )
          ));

          myDiagram.linkTemplate =
            $(go.Link, {
                routing: go.Link.AvoidsNodes,
                curve: go.Link.JumpGap,
                corner: 10,
                reshapable: true,
                toShortLength: 7
              },
              new go.Binding("points").makeTwoWay(),
              // mark each Shape to get the link geometry with isPanelMain: true
              $(go.Shape, {
                isPanelMain: true,
                stroke: "black",
                strokeWidth: 7
              }),
              $(go.Shape, {
                isPanelMain: true,
                stroke: "gray",
                strokeWidth: 5
              }),
              $(go.Shape, {
                isPanelMain: true,
                stroke: "white",
                strokeWidth: 3,
                name: "PIPE",
                strokeDashArray: [10, 10]
              }),
              $(go.Shape, {
                toArrow: "Triangle",
                scale: 1.3,
                fill: "gray",
                stroke: null
              })
            );

          load();

         Start_animation();
        }

        function convertLinearBrush(c, shape) 
        {
          var data = shape.part.data;
          var b = new go.Brush(go.Brush.Linear);
          b.addColorStop(0, "gray");
          b.addColorStop(1-data.level-0.01, "lightgray");
          b.addColorStop(1-data.level, "#3498DB");
          b.addColorStop(1, "blue");
          return b;
        }
       
        function save() {
          $.post("<?=base_url("PROD/save_monitor_setting")?>", {
            json:myDiagram.model.toJson()
          },
            function (data, textStatus, jqXHR) {
              console.log(data);
            },
            "html"
          );
          document.getElementById("mySavedModel").value = myDiagram.model.toJson();
          myDiagram.isModified = false;
        }

        function load() {
          myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
          
          Start_animation();

        }
        function default_load()
        {
          myDiagram.model = go.Model.fromJson(document.getElementById("defaultJson").value);
          Start_animation();
        }

        function Start_animation()
        {
           // Animate the flow in the pipes
           var animation = new go.Animation();
          animation.easing = go.Animation.EaseLinear;
          myDiagram.links.each(link => animation.add(link.findObject("PIPE"), "strokeDashOffset", 20, 0));
          // Run indefinitely
          animation.runCount = Infinity;
          animation.start();
        }

        var isShow = false;
        function hide_show()
        {
          isShow = !isShow;
          if(isShow)
          {
            $("#mySavedModel").css("display","block");
            $("#showcode").html("Hide Code");
          }
          else
          {
            $("#mySavedModel").css("display","none");
            $("#showcode").html("Show Code");
          }
        }

        window.addEventListener('DOMContentLoaded', init);
      </script>

      <div id="sample">
        <div id="myDiagramDiv" style="border: 1px solid black; width: 100%; height: 700px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 0);"><canvas tabindex="0" width="1054" height="700" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 1054px; height: 498px;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
          <!-- <div style="position: absolute; overflow: auto; width: 1054px; height: 498px; z-index: 1;"> -->
          <!-- <div style="position: absolute; width: 1px; height: 1px;"></div> -->
        </div>
      </div>

      <div>
        <div>
          <button id="SaveButton" onclick="save()" >Save to DB</button>
          <button onclick="load()">Load from DB</button>
          <button onclick="default_load()">Default Load</button>
          <button id="showcode" onclick="hide_show()" style="float:right;">Show Code</button>
        </div>
        <textarea id="mySavedModel" style="width:100%;height:300px;display:none;">{ "class": "GraphLinksModel",
  "nodeDataArray": [
{"key":"T1","category":"Process","pos":"53.008544921875 120","text":"Na2Co3","size":"140 170"},
{"key":"T2","category":"Process","pos":"230 120","text":"Wash","size":"140 170"},
{"key":"T3","category":"Process","pos":"410 120","text":"Mix","size":"140 170"},
{"key":"W1","category":"Process","pos":"0 -50","text":"H2O","size":"80 90"},
{"key":"W2","category":"Process","pos":"110 -50","text":"H2O","size":"80 90"},
{"key":"M1","category":"Process","pos":"580 240","text":"Licl1","size":"110 150"},
{"key":"M2","category":"Process","pos":"580 70","text":"Licl2","size":"110 150"},
{"key":"B1","category":"Process","pos":"230 300","text":"Li2Co3","size":"80 90"},
{"key":"w1","category":"Table1","text1":"72","text2":"59","pos":"-25.714285714285722 -57.14285714285715"},
{"key":"w2","category":"Table1","text1":"67","text2":"58","pos":"84.99999999999994 -56.42857142857143"},
{"key":"t1","category":"Table2","text1":"11.77","text2":"0.02","text3":"41","text4":"1.01","text5":"43","pos":"20 91.42857142857142"},
{"key":"t2","category":"Table2","text1":"10.3","text2":"5.4","text3":"33","text4":"0.998","text5":"64","pos":"196.4285714285714 91.42857142857144"},
{"key":"t3","category":"Table2","text1":"11.04","text2":"9.8","text3":"45","text4":"1.22","text5":"61","pos":"375.7142857142858 90.7142857142857"},
{"key":"m1","category":"Table2","text1":"12.5","text2":"9.9","text3":"35","text4":"1.09","text5":"43","pos":"550 204.28571428571428"},
{"key":"m2","category":"Table2","text1":"12.5","text2":"9.98","text3":"34","text4":"1.10","text5":"45","pos":"549.2857142857144 35.714285714285694"}
],
  "linkDataArray": [
{"from":"T1","to":"T3","points":[124.008544921875,120,134.008544921875,120,139.99999999999994,120,139.99999999999994,223.12499999999994,319.99999999999994,223.12499999999994,319.99999999999994,148.66666666666669,321,148.66666666666669,339,148.66666666666669]},
{"from":"W1","to":"T1","points":[0,-4,0,6,0,15,29.34187825520833,15,29.34187825520833,24,29.34187825520833,34]},
{"from":"W2","to":"T1","points":[110,-4,110,6,110,15,76.67521158854166,15,76.67521158854166,24,76.67521158854166,34]},
{"from":"M1","to":"T3","points":[524,240,514,240,502.5,240,502.5,148.66666666666666,491,148.66666666666666,481,148.66666666666666]},
{"from":"M2","to":"T3","points":[524,70,514,70,502.5,70,502.5,120,491,120,481,120]},
{"from":"T3","to":"T2","points":[339,91.33333333333334,321,91.33333333333334,316,91.33333333333334,316,120,311,120,301,120]},
{"from":"T2","to":"B1","points":[230,206,230,216,230,230,230,230,230,244,230,254]}
]}
    </textarea>
        <textarea id="defaultJson" style="width:100%;height:300px;display:none;">{ "class": "GraphLinksModel",
  "nodeDataArray": [
{"key":"T1","category":"Process","pos":"53.008544921875 120","text":"Na2Co3","size":"140 170"},
{"key":"T2","category":"Process","pos":"230 120","text":"Wash","size":"140 170"},
{"key":"T3","category":"Process","pos":"410 120","text":"Mix","size":"140 170"},
{"key":"W1","category":"Process","pos":"0 -50","text":"H2O","size":"80 90"},
{"key":"W2","category":"Process","pos":"110 -50","text":"H2O","size":"80 90"},
{"key":"M1","category":"Process","pos":"580 240","text":"Licl1","size":"110 150"},
{"key":"M2","category":"Process","pos":"580 70","text":"Licl2","size":"110 150"},
{"key":"B1","category":"Process","pos":"230 300","text":"Li2Co3","size":"80 90"},
{"key":"w1","category":"Table1","text1":"72","text2":"59","pos":"-25.714285714285722 -57.14285714285715"},
{"key":"w2","category":"Table1","text1":"67","text2":"58","pos":"84.99999999999994 -56.42857142857143"},
{"key":"t1","category":"Table2","text1":"11.77","text2":"0.02","text3":"41","text4":"1.01","text5":"43","pos":"20 91.42857142857142"},
{"key":"t2","category":"Table2","text1":"10.3","text2":"5.4","text3":"33","text4":"0.998","text5":"64","pos":"196.4285714285714 91.42857142857144"},
{"key":"t3","category":"Table2","text1":"11.04","text2":"9.8","text3":"45","text4":"1.22","text5":"61","pos":"375.7142857142858 90.7142857142857"},
{"key":"m1","category":"Table2","text1":"12.5","text2":"9.9","text3":"35","text4":"1.09","text5":"43","pos":"550 204.28571428571428"},
{"key":"m2","category":"Table2","text1":"12.5","text2":"9.98","text3":"34","text4":"1.10","text5":"45","pos":"549.2857142857144 35.714285714285694"}
],
  "linkDataArray": [
{"from":"T1","to":"T3","points":[124.008544921875,120,134.008544921875,120,139.99999999999994,120,139.99999999999994,223.12499999999994,319.99999999999994,223.12499999999994,319.99999999999994,148.66666666666669,321,148.66666666666669,339,148.66666666666669]},
{"from":"W1","to":"T1","points":[0,-4,0,6,0,15,29.34187825520833,15,29.34187825520833,24,29.34187825520833,34]},
{"from":"W2","to":"T1","points":[110,-4,110,6,110,15,76.67521158854166,15,76.67521158854166,24,76.67521158854166,34]},
{"from":"M1","to":"T3","points":[524,240,514,240,502.5,240,502.5,148.66666666666666,491,148.66666666666666,481,148.66666666666666]},
{"from":"M2","to":"T3","points":[524,70,514,70,502.5,70,502.5,120,491,120,481,120]},
{"from":"T3","to":"T2","points":[339,91.33333333333334,321,91.33333333333334,316,91.33333333333334,316,120,311,120,301,120]},
{"from":"T2","to":"B1","points":[230,206,230,216,230,230,230,230,230,244,230,254]}
]}
    </textarea>
      </div>
      <script src="../_static/js/goSamples.js"></script>