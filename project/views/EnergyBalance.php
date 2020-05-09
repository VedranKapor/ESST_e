<?php
require "../config.php";
require '../lang/en.php';
?>

<div class="page-header">
    <h4>
        <?php echo $case_name.': '; 
        if(isset($_SESSION['case'])){
            echo "<span style='color:#A80000 ;'>" .$_SESSION['case']."</span><br>";
        }
        else {
            echo $create_new_or_edit_exisiting_case."<br>";
        }?>
        <small><?php echo $aggregated_energy_balance; ?>   <i class="ace-icon fa fa-angle-double-right"></i></small>
        <small><?php echo $_SESSION['year'];?></small>
    </h4>
</div>  

<?php
if (isset($_SESSION['case']))
{
?>

<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
        <li class="active">
			<a data-toggle="tab" href="#sankey">
                <i class="orange fa fa-area-chart" aria-hidden="true"></i>
                Sankey diagram
				<!-- <span class="badge badge-warning">%</span> -->
			</a>
		</li>
		<li >
			<a data-toggle="tab" href="#balance">
				<i class="green ace-icon fa fa-balance-scale bigger-120"></i>
				Energy balance
			</a>
		</li>
	</ul>
	<div class="tab-content">
		<div id="balance" class="tab-pane fade in ">
            <div class="row">
                <div class="col-md-12">
                    <div id="jqxgrid"></div> 
                    <div type='submit' name='Submit' class="btn btn-nest btn-sm" id='excelExport' ><i class="fa fa-table" aria-hidden="true"></i><?php echo $export_grid; ?></div>
                </div>
            </div>
        </div>
        <div id="sankey" class="tab-pane fade in active">
            <div class="row">
                <div class="col-md-12">
                  <div id="content"></div> 
                </div>
            </div>
        </div>
    </div>
</div>


<?php
}
else{
    echo "<div id='odgovor'> $create_new_or_edit_exisiting_case</div>";
}
?>	
<script src="scripts/d3.v3.js"></script>
<script src="scripts/sankey.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    //napuni nizove za fuels secctors years
    session = getSession();
    var casename = session.case;  
    var user = session.us; 
        
    if (casename != '') {
        var xmlDoc = getXML(casename, user);
        var unit = getUnit(xmlDoc);
        var elmix_fuels = getElMixFuels(xmlDoc);
        var year = getUrlVars()["year"];
        //definisi prvi data adapter za grid i chart po sektorima
        var theme = 'bootstrap';

        $("#excelExport").jqxButton({width: '150px', theme: theme});
        var sector = getUrlVars()["sector"];
        var myURL ='data/EnergyBalance_data.php?year='+year;
        var source = {
            url: myURL,
            root: 'data',
            datatype: 'json',
            cache: true,
            datafields: [
                { name: 'flow', type: 'string' },
                { name: 'Hydro', type: 'number' },
                { name: 'Coal', type: 'number' },
                { name: 'Oil', type: 'number' },
                { name: 'Gas', type: 'number' },
                { name: 'Biofuels', type: 'number' },
                { name: 'Peat', type: 'number' },
                { name: 'Waste', type: 'number' },
                { name: 'Oil_shale', type: 'number' },
                { name: 'Solar', type: 'number' },
                { name: 'Wind', type: 'number' },
                { name: 'Geothermal', type: 'number' },
                { name: 'Nuclear', type: 'number' },
                { name: 'ImportExport', type: 'number' },
                { name: 'Electricity', type: 'number' },
                { name: 'Heat', type: 'number' },
                
                
                ]
        };   
        var dataAdapter = new $.jqx.dataAdapter(source);      
        // var dataAdapter = new $.jqx.dataAdapter(source, { 
        //     loadComplete: function() { 
        //         $("#jqxgrid").jqxGrid('hidecolumn', 'ImportExport');
        //     }
        // });             
 
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {
            if (row == 2 || row == 3 || row == 6 || row == 7) {
                var element = $(defaulthtml);
                element.css({ 'font-weight':'bold' });
                return element[0].outerHTML;
            }
            else{   
                var element = $(defaulthtml);
                element.css({ 'font-style':'Italic'});
                return element[0].outerHTML;
            }
            var value1 = $(value)
            if (value === 0.00){
                return 1.11;
            }
        }
        //settings za grid i incijalizacija
         $("#jqxgrid").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            //showstatusbar: true,
            //showaggregates: true,            
            columnsresize:true,
            columnsautoresize: true,
            source: dataAdapter,
            editable: false,
            selectionmode: 'multiplecellsadvanced',
            columns: [
                { text: unit, datafield: 'flow', width: '9.75%', pinned: true, align: 'right', cellsrenderer: cellsrenderer},    
                { text: coal, datafield: 'Coal', width: '6%', aggregates: ['sum'], cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: peat, datafield: 'Peat', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: oil, datafield: 'Oil', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: oil_shale, datafield: 'Oil_shale', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: gas, datafield: 'Gas', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: nuclear, datafield: 'Nuclear', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: hydro, datafield: 'Hydro', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: geothermal, datafield: 'Geothermal', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: solar, datafield: 'Solar', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: biofuels, datafield: 'Biofuels', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: waste, datafield: 'Waste', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: wind, datafield: 'Wind', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: electricity, datafield: 'Electricity', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: heat, datafield: 'Heat', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer},
                { text: importexport, datafield: 'ImportExport', width: '6%', aggregates: ['sum'],cellsalign: 'center', cellsformat: 'd2', align: 'center', cellsrenderer: cellsrenderer}
            ]
        });//kraj inicijalizacije i settinga za grid   

        $("#excelExport").jqxButton({theme: theme});
        $("#excelExport").click(function() {
            $("#jqxgrid").jqxGrid('exportdata', 'xls', energy_balance+' - '+year);
        });

    ////////////////////////////////////////////////////////////////////////////////////SANKEY
        function divsize() {
            var returnedArray = [];
            margin = {top: 10, right: 10, bottom: 10, left: 10},
            //width = 1400 - margin.left - margin.right,
            //height = 740 - margin.top - margin.bottom;
            width = document.getElementById("content").offsetWidth - margin.left - margin.right,
            //height = document.getElementById("content").offsetHeight - margin.top - margin.bottom;
            height = "550";
            returnedArray.push(width);
            returnedArray.push(height);
            return returnedArray;
        }

        var returnValue  = divsize();
        width = returnValue[0];
        height = returnValue[1];


        var units = "Units";  
        $( "<p id='chart'></p>").appendTo( "#content" );
         
        var formatNumber = d3.format(",.0f"),    // zero decimal places
            format = function(d) { return formatNumber(d) + " " + units; },
            color = d3.scale.category20();
         
        // append the svg canvas to the page
        var svg = d3.select("#chart").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
          .append("g")
            .attr("transform", 
                  "translate(" + margin.left + "," + margin.top + ")");
                  
         //console.log(document.getElementById("chart").offsetWidth);
         
        // Set the sankey diagram properties
        var sankey = d3.sankey()
            .nodeWidth(36)
            .nodePadding(10)
            .size([width, height]);
         
        var path = sankey.link();
         
        // load the data with d3.csv instead of d3.json
        //for another much simpler example uncomment the below
        // d3.csv("d3noob_example.csv", function(error, data) {
            
        d3.csv("data/Sankey_data.php?year="+year+"&action=sankey", function(error, data) {
         		//set up graph in same style as original example but empty
          		graph = {"nodes" : [], "links" : []};
          
                        data.forEach(function (d) {
                            graph.nodes.push({ "name": d.source });
                            graph.nodes.push({ "name": d.target });
        
                            graph.links.push({ "source": d.source, "target": d.target, "value": +d.value });
                        });
        
                        //thanks Mike Bostock https://groups.google.com/d/msg/d3-js/pl297cFtIQk/Eso4q_eBu1IJ
                        //this handy little function returns only the distinct / unique nodes
                        graph.nodes = d3.keys(d3.nest()
                                 .key(function (d) { return d.name; })
                                 .map(graph.nodes));
        
                        //it appears d3 with force layout wants a numeric source and target
                        //so loop through each link replacing the text with its index from node
                        graph.links.forEach(function (d, i) {
                            graph.links[i].source = graph.nodes.indexOf(graph.links[i].source);
                            graph.links[i].target = graph.nodes.indexOf(graph.links[i].target);
                        });
        
                        //now loop through each nodes to make nodes an array of objects rather than an array of strings
                        graph.nodes.forEach(function (d, i) {
                            graph.nodes[i] = { "name": d };
                        });
         
          sankey
              .nodes(graph.nodes)
              .links(graph.links)
              .layout(32);
         
        // add in the links
          var link = svg.append("g").selectAll(".link")
              .data(graph.links)
            .enter().append("path")
              .attr("class", "link")
              .attr("d", path)
              .style("stroke-width", function(d) { return Math.max(1, d.dy); })
              .sort(function(a, b) { return b.dy - a.dy; });
         
        // add the link titles
          link.append("title")
                .text(function(d) {
              	return d.source.name + " ? " + 
                        d.target.name + "\n" + format(d.value); });
         
        // add in the nodes
          var node = svg.append("g").selectAll(".node")
              .data(graph.nodes)
            .enter().append("g")
              .attr("class", "node")
              .attr("transform", function(d) { 
        		  return "translate(" + d.x + "," + d.y + ")"; })
            .call(d3.behavior.drag()
              .origin(function(d) { return d; })
              .on("dragstart", function() { 
        		  this.parentNode.appendChild(this); })
              .on("drag", dragmove));
         
        // add the rectangles for the nodes
          node.append("rect")
              .attr("height", function(d) { return d.dy; })
              .attr("width", sankey.nodeWidth())
              .style("fill", function(d) { 
        		  return d.color = color(d.name.replace(/ .*/, "")); })
              .style("stroke", function(d) { 
        		  return d3.rgb(d.color).darker(2); })
            .append("title")
              .text(function(d) { 
        		  return d.name + "\n" + format(d.value); });
         
        // add in the title for the nodes
          node.append("text")
              .attr("x", -6)
              .attr("y", function(d) { return d.dy / 2; })
              .attr("dy", ".35em")
              .attr("text-anchor", "end")
              .attr("transform", null)
              .text(function(d) { return d.name; })
            .filter(function(d) { return d.x < width / 2; })
              .attr("x", 6 + sankey.nodeWidth())
              .attr("text-anchor", "start");
         
        // the function for moving the nodes
          function dragmove(d) {
            d3.select(this).attr("transform", 
                "translate(" + (
                	   d.x = Math.max(0, Math.min(width - d.dx, d3.event.x))
                	) + "," + (
                           d.y = Math.max(0, Math.min(height - d.dy, d3.event.y))
                    ) + ")");
            sankey.relayout();
            link.attr("d", path);
          }
        });
    }
    $('#loadermain').hide(); 
});
</script>



