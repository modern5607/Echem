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
    /* background-color: #121415; */
  }
</style>


<div class="bdcont_100">
  <div class="">
    <div id="allSampleContent" class="p-4 w-full">
      <!-- <script src="https://unpkg.com/gojs@2.2.11/extensions/Figures.js"></script> -->
      <script id="code">
        $(document).ready(function() {
          init();

          drawBox();
        });

        function drawBox() {
          /**@type {HTMLCanvasElement} */
          var canvas = document.getElementById("myDiagramDiv").firstChild;
          // console.log(canvas);
          var ctx = canvas.getContext("2d");
          ctx.fillStyle = "red";
          ctx.fillRect(100, 100, 100, 100);
          ctx.font = "30px Arial";
          ctx.fillText("Hello World", 10, 50);
        }

        function init() {
          // console.log("init");
          // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
          // For details, see https://gojs.net/latest/intro/buildingObjects.html
          const $ = go.GraphObject.make; // for more concise visual tree definitions

          myDiagram =
            $(go.Diagram, "myDiagramDiv", {
              "grid.visible": true,
              "grid.gridCellSize": new go.Size(70, 70),
              "draggingTool.isGridSnapEnabled": true,
              "resizingTool.isGridSnapEnabled": true,
              "rotatingTool.snapAngleMultiple": 90,
              "rotatingTool.snapAngleEpsilon": 45,
              "undoManager.isEnabled": true
            });

          // when the document is modified, add a "*" to the title and enable the "Save" button
          myDiagram.addDiagramListener("Modified", e => {
            var button = document.getElementById("SaveButton");
            if (button) button.disabled = !myDiagram.isModified;
            var idx = document.title.indexOf("*");
            if (myDiagram.isModified) {
              if (idx < 0) document.title += "*";
            } else {
              if (idx >= 0) document.title = document.title.slice(0, idx);
            }
          });

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
                    start: go.Spot.Left,
                    end: go.Spot.Right,
                    0: "gray",
                    0.5: "white",
                    1: "gray"
                  }),
                  minSize: new go.Size(50, 50),
                  portId: "",
                  fromSpot: go.Spot.AllSides,
                  toSpot: go.Spot.AllSides
                },
                new go.Binding("desiredSize", "size", go.Size.parse).makeTwoWay(go.Size.stringify)),
              $(go.TextBlock, {
                  alignment: go.Spot.Center,
                  textAlign: "center",
                  margin: 5,
                  editable: true
                },
                new go.Binding("text").makeTwoWay())
            ));

          myDiagram.nodeTemplateMap.add("Valve",
            $(go.Node, "Vertical", {
                locationSpot: new go.Spot(0.5, 1, 0, -21),
                locationObjectName: "SHAPE",
                selectionObjectName: "SHAPE",
                rotatable: true
              },
              new go.Binding("angle").makeTwoWay(),
              new go.Binding("location", "pos", go.Point.parse).makeTwoWay(go.Point.stringify),
              $(go.TextBlock, {
                  alignment: go.Spot.Center,
                  textAlign: "center",
                  margin: 5,
                  editable: true
                },
                new go.Binding("text").makeTwoWay(),
                // keep the text upright, even when the whole node has been rotated upside down
                new go.Binding("angle", "angle", a => a === 180 ? 180 : 0).ofObject()),
              $(go.Shape, {
                name: "SHAPE",
                geometryString: "F1 M0 0 L40 20 40 0 0 20z M20 10 L20 30 M12 30 L28 30",
                strokeWidth: 2,
                fill: $(go.Brush, "Linear", {
                  0: "gray",
                  0.35: "white",
                  0.7: "gray"
                }),
                portId: "",
                fromSpot: new go.Spot(1, 0.35),
                toSpot: new go.Spot(0, 0.35)
              })
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

          // Animate the flow in the pipes
          var animation = new go.Animation();
          animation.easing = go.Animation.EaseLinear;
          myDiagram.links.each(link => animation.add(link.findObject("PIPE"), "strokeDashOffset", 20, 0));
          // Run indefinitely
          animation.runCount = Infinity;
          animation.start();
        }

        // Always show the first Node:
        var inspector2 = new Inspector('myInspectorDiv2', myDiagram, {
          // By default the inspector works on the Diagram selection.
          // This property lets us inspect a specific object by calling Inspector.inspectObject.
          inspectSelection: false,
          properties: {
            "text": {},
            // This property we want to declare as a color, to show a color-picker:
            "color": {
              type: 'color'
            },
            // key would be automatically added for node data, but we want to declare it read-only also:
            "key": {
              readOnly: true,
              show: Inspector.showIfPresent
            }
          }
        });

        function save() {
          document.getElementById("mySavedModel").value = myDiagram.model.toJson();
          myDiagram.isModified = false;
        }

        function load() {
          myDiagram.model = go.Model.fromJson(document.getElementById("mySavedModel").value);
        }

        window.addEventListener('DOMContentLoaded', init);
      </script>

      <div id="sample">
        <div id="myDiagramDiv" style="border: 1px solid black; width: 100%; height: 500px; position: relative; -webkit-tap-highlight-color: rgba(255, 255, 255, 255);"><canvas tabindex="0" width="1054" height="498" style="position: absolute; top: 0px; left: 0px; z-index: 2; user-select: none; touch-action: none; width: 1054px; height: 498px;">This text is displayed if your browser does not support the Canvas HTML element.</canvas>
          <!-- <div style="position: absolute; overflow: auto; width: 1054px; height: 498px; z-index: 1;"> -->
          <!-- <div style="position: absolute; width: 1px; height: 1px;"></div> -->
        </div>
      </div>

      <div>
        <div>
          <button id="SaveButton" onclick="save()" disabled="">Save</button>
          <button onclick="load()">Load</button>
          Diagram Model saved in JSON format:
        </div>
        <textarea id="mySavedModel" style="width:100%;height:300px">{ "class": "GraphLinksModel",
  "nodeDataArray": [
{"key":"T1","category":"Process","pos":"78.008544921875 125","text":"Na2Co3","size":"90 100"},
{"key":"T2","category":"Process","pos":"210 125","text":"Wash","size":"90 100"},
{"key":"T3","category":"Process","pos":"350 125","text":"Mix","size":"90 100"},
{"key":"W1","category":"Process","pos":"150 20","text":"H2O"},
{"key":"W2","category":"Process","pos":"85 20","text":"H2O"},
{"key":"M1","category":"Process","pos":"450 220","text":"Licl1","size":"50 60"},
{"key":"M2","category":"Process","pos":"510 220","text":"Licl2","size":"50 60"},
{"key":"B1","category":"Process","pos":"210 260","text":"Li2Co3","size":"50 60"}
],
  "linkDataArray": [
{"from":"T1","to":"T3","points":[124.008544921875,125,134.008544921875,125,146,125,146,193,269,193,269,125,294,125,304,125]},
{"from":"W1","to":"T1","points":[124,20,114,20,114,20,114,60,93.34187825520833,60,93.34187825520833,64,93.34187825520833,74]},
{"from":"W2","to":"T1","points":[90,46,90,56,76.33760579427083,56,76.33760579427083,56,62.675211588541664,56,62.675211588541664,74]},
{"from":"M1","to":"T3","points":[450,189,450,179,450,142,428,142,406,142,396,142]},
{"from":"M2","to":"T3","points":[484,220,474,220,480,220,480,108,414,108,396,108]},
{"from":"T3","to":"T2","points":[304,108,294,108,280,108,280,100,266,100,256,100]},
{"from":"T2","to":"B1","points":[210,176,210,186,210,202.5,210,202.5,210,219,210,229]}
]}
    </textarea>
      </div>
      <script src="../_static/js/goSamples.js"></script>