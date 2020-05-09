<?php
require "../config.php";
require '../lang/en.php';
?>
<div class="page-header">
    <h4>
    <p lang="en">Case name:
        <?php
        if(isset($_SESSION['case'])){
            echo "<span style='color:#A80000 ;'>" .$_SESSION['case']."</span><br>";
        }
        else {
            echo "<span style='color:#A80000 ;'>Please select a case!</span><br>";
        }?></p>
        <small class="hidden-xs hidden-sm"><?php echo $secondary_energy_supplies; ?>   <i class="ace-icon fa fa-angle-double-right"></i></small>
        <small class="hidden-xs hidden-sm"><?php echo $td_losses;?></small>
    </h4>
</div>  

<?php
if (isset($_SESSION['case']))
{
?>
<div id="row">
    <div class="col-lg-12">
        <div  id="log" style="display:none">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title">Secondary energy uses Losses [%]</h4>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse">
                        <i class="ace-icon fa fa-chevron-up"></i>
                    </a>
                </div>
                <div class="widget-toolbar">
                    <a href="#" data-action="mix" id="showLog"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton">
                        <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                    </a>
                </div>
                <div class="widget-toolbar">
                    <a href="#" data-action="mix"  id="pngFs"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                        <i class="ace-icon fa fa-file-image-o blue"></i>
                    </a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div>
                        <div id='jqxChart_trans' style="width:100%; height:280px;"></div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="widget-box">
    	<div class="widget-header">
            <h4 class="widget-title">Secondary energy uses Losses [%]</h4>
            <div class="widget-toolbar">
    			<a href="#" data-action="collapse">
    				<i class="ace-icon fa fa-chevron-up"></i>
    			</a>
            </div>           
    		<div class="widget-toolbar">
                <a href="#" data-action="mix"  id="xlsAll"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                    <i class="ace-icon fa fa-file-excel-o green"></i>
                </a>
            </div>
            <div class="widget-toolbar">
                <a href="#" data-action="mix" id="resizeColumns" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                </a>
            </div>
            <div class="widget-toolbar">
                <a href="#" data-action="mix"  id="decDown"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Decrease decimal places">
                    <i class="ace-icon fa fa-arrow-circle-o-down orange"></i>
                </a>   
                <a href="#" data-action="mix"  id="decUp"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Increase decimal places">
                    <i class="ace-icon fa fa-arrow-circle-o-up nest"></i>
                </a>
    		</div>
    	</div>
            <div class="widget-body">
                <div class="widget-main">
                    <div>
                        <div id="jqxgrid_trans" ></div>
                    </div>
                </div>
            </div>
        </div>
     </div>
<?php
}
else{
    echo "<div class='jqx-validator-warning-label-big'>$no_case_selected $create_new_or_edit_exisiting_case</div>";
}
?>

<script type="text/javascript">
$(document).ready(function () {
    access();
    session = getSession();
    var casename = session.case;  
    var user = session.us;  
    if (typeof(casename) != 'undefined'){
        let xmlDoc = getXML(casename, user);
        var unit = getUnit(xmlDoc);
        var years = getYears(xmlDoc);
        let fuels = getFuels(xmlDoc);

        let series_fuels = getFuelSeries(fuels);
        let series_years = getYearsSeries(years);

        //let columns_years_editable = getYearsEditableColumns2(years);
        let $div = $("#jqxgrid_trans");
        let columns_years_editable = getColYr_e($div, years, 'p', '[%]');
  
        var theme = 'bootstrap';
        var myURL ='data/FED_losses_data.php';
                 
        var myURL2 ='data/FED_losses_data.php?trans=1';
        var sourcetrans = {
            url: myURL2,
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                {name: "name", type: "string"},
                {name: "1990", type: "string"},
                {name: "2000", type: "string"},
                {name: "2005", type: "string"},
                {name: "2010", type: "string"},
                {name: "2015", type: "string"},
                {name: "2020", type: "string"},
                {name: "2025", type: "string"},
                {name: "2030", type: "string"},
                {name: "2035", type: "string"},
                {name: "2040", type: "string"},
                {name: "2045", type: "string"},
                {name: "2050", type: "string"}            
            ]
        };   
        
        var dataAdapter2 = new $.jqx.dataAdapter(sourcetrans); 
    
        //setting za transponirani chart
        var settings_charttrans = {
            title:  '',
            description: '',
            enableAnimations: true,
            showLegend: true,
            theme: theme,
            padding: { left: 5, top: 5, right: 5, bottom: 5 },
            titlePadding: { left: 90, top: 0, right: 0, bottom: 10 },
            source: dataAdapter2,
            borderLineColor: 'transparent',
            categoryAxis:{
                text: 'Category Axis',
                textRotationAngle: 0,
                dataField: 'name',
                showTickMarks: true,
                tickMarksInterval: 1,
                tickMarksColor: '#888888',
                unitInterval: 1,
                showGridLines: false,
                gridLinesInterval: 1,
                gridLinesColor: '#888888',
                axisSize: 'auto',
            },
            colorScheme: 'scheme01',
            seriesGroups:
                [
                    {                                                                    
                        type: 'column',
                        valuesOnTicks: false,
                            columnsGapPercent:10,
                            seriesGapPercent: 5,
                            columnsMaxWidth:100,
                            columnsMinWidth:5,
                        formatSettings:
                        {
                            decimalPlaces: 1,
                            sufix: ' %'
                        },   
                        labels: {
                            visible: true,
                            verticalAlignment: 'center',
                            offset: { x: 0, y: 0 },
                            angle: 90
                        },
                        valueAxis:
                        {
                            unitInterval: 0,
                            minValue: 0,
                            maxValue: 'auto',
                            displayValueAxis: true,
                            description: '%',
                            axisSize: 'auto',
                            tickMarksColor: '#888888',
                            
                            formatSettings: {decimalPlaces: 0},
                        },
                        series: series_years
                    }
                ]
        };

        $('#jqxChart_trans').jqxChart(settings_charttrans);
       
       //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgrid_trans").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter2,
            editable: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_editable                  
        }); 


        let res = true;
        $("#resizeColumns").click(function () {
            if(res){
                $('#jqxgrid_trans').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgrid_trans').jqxGrid('autoresizecolumns');
            }
            res = !res;        
        });

            $("#xlsAll").click(function (e) {
                e.preventDefault();
                $("#jqxgrid_trans").jqxGrid('exportdata', 'xls', 'Losses');
            });
                
                //update za transpponirani grid
            $("#jqxgrid_trans").on('cellvaluechanged', function (event) {
                var args = event.args;
                var godina = args.datafield;
                var fuel = args.rowindex;
                var fuel1 = $("#jqxgrid_trans").jqxGrid("getcellvalue", fuel, "name"); 
                var value = args.value;
                rowdata = new Object();
                rowdata['action'] = 'saveData';
                //rowdata['fuel'] = fuel1;
                rowdata['fuel'] = fuel;
                rowdata['year'] = godina;
                rowdata['value'] = value;

                $.ajax({
                    url: myURL,
                    dataType: 'json',
                    type: 'POST',
                    data: rowdata, 
                    async:true,
                    complete: function(e) {
                        var serverResponce = e.responseJSON;
                        switch (serverResponce["type"]) {
                            case 'ERROR':
                                ShowErrorMessage(serverResponce["msg"]);
                                break;
                            case 'EXIST':
                                ShowWarningMessage(serverResponce["msg"]);
                                break;
                            case 'SUCCESS':
                                $('#jqxNotification').jqxNotification('closeAll'); 
                                ShowInfoMessage(serverResponce["msg"]);
                                localStorage.setItem("P1",  "changed");    
                                dataAdapter2.dataBind();
                                break;
                        }  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                        ShowErrorMessage(errorThrown);
                    }
                });
	        });
        $('#loadermain').hide(); 
        $("#showLog").click(function (e) {
            e.preventDefault();
            $('#log').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.LOSSES.title}</h4>
                    ${DEF.LOSSES.definition}
                </div>
            `);
            $('#log').toggle('slow');
        });

        $("#decUp").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d++;
            window.decimal = 'd' + parseInt(window.d);
            //$('#jqxgrid_trans').jqxGrid('updatebounddata', 'cells');
            $('#jqxgrid_trans').jqxGrid('refresh');
            $('#jqxgrid_trans').jqxGrid('refreshaggregates');
        });

        $("#decDown").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d--;
            window.decimal = 'd' + parseInt(window.d);
            // $('#jqxgrid_trans').jqxGrid('updatebounddata', 'cells');
            $('#jqxgrid_trans').jqxGrid('refresh');
            $('#jqxgrid_trans').jqxGrid('refreshaggregates');
        });

        $("#pngFs").click(function() {
            $("#jqxChart_trans").jqxChart('saveAsPNG', 'Losses shares.png',  getExportServer());
        }); 
    }
    else{
        $('#loadermain').hide(); 
    }
});
</script>
