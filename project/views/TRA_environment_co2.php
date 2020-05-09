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
            echo "<span style='color:#A80000 ;'>Please select a case!</span><br>";
        }?>
        <small class="hidden-xs hidden-sm"><?php echo $environmental_impact; ?>   <i class="ace-icon fa fa-angle-double-right"></i></small>
        <small class="hidden-xs hidden-sm"><?php echo $co2_emissions;?></small>
    </h4>
</div>  

<?php
if (isset($_SESSION['case']))
{
?>


<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
		<li class="active">
			<a data-toggle="tab" href="#emissionCO2">
            <span  class="hidden-xs hidden-sm">System CO<sub>2</sub> emissions</span>
                <span class="badge badge-danger">CO<sub>2</sub></span>
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#costCo2">
                <span  class="hidden-xs hidden-sm">System CO<sub>2</sub> costs</span>
				<span class="badge badge-success"><i class="fa fa-money" aria-hidden="true"></i>CO<sub>2</sub></span>
			</a>
		</li>

    </ul>
    

	<div class="tab-content">

        <div id="emissionCO2" class="tab-pane fade in active">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="log_co2" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $co2_emissions; ?></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="showLog_co2"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsCO2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngCO2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="barChart" data-chartType="barChart" id-chart="1"  class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Bar Chart">
                                    <i class="ace-icon fa fa-bar-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="lineChart" data-chartType="lineChart" id-chart="1" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Line Chart">
                                    <i class="ace-icon fa fa-line-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="areaChart" data-chartType="areaChart" id-chart="1" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Area Chart">
                                    <i class="ace-icon fa fa-area-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart100" data-chartType="stackedChart100" id-chart="1" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart 100">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="1" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="1"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#"  data-action="mix" id="resizeColumns_co2" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                            <!-- <div class="widget-toolbar">
                                <a href="#" id="decDown"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Decrease decimal places">
                                    <i class="ace-icon fa fa-arrow-circle-o-down orange"></i>
                                </a>   
                                <a href="#"   id="decUp"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Increase decimal places">
                                    <i class="ace-icon fa fa-arrow-circle-o-up nest"></i>
                                </a> 
                            </div>-->

                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_energy_output' style="width:100%; height:250px;"></div>
                                    <div id="jqxgrid_energy_output_trans"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="costCo2" class="tab-pane fade">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="log_co2c" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">System CO<sub>2</sub> costs</h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="showLog_co2c"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsCO2Cost"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngCO2Cost"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="barChart" data-chartType="barChart" id-chart="2"  class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Bar Chart">
                                    <i class="ace-icon fa fa-bar-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="lineChart" data-chartType="lineChart" id-chart="2" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Line Chart">
                                    <i class="ace-icon fa fa-line-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="areaChart" data-chartType="areaChart" id-chart="2" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Area Chart">
                                    <i class="ace-icon fa fa-area-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart100" data-chartType="stackedChart100" id-chart="2" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart 100">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="2" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="2"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="resizeColumns_c" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChartCarbonCost' style="width:100%; height:250px;"></div>
                                    <div id="jqxgridCarbonCost"></div>
                                </div>
                            </div>
                        </div>
                    </div>
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



<script type="text/javascript" src="scripts/generateColors.js"></script>
<script src="classes/js/Chart.Class.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function () {
    access();
    session = getSession();
    var casename = session.case;  
    var user = session.us;  
    if (typeof(casename) != 'undefined')
    {
        var xmlDoc = getXML(casename, user);
        var unit = getUnit(xmlDoc);
        var years = getYears(xmlDoc);
        var elmix_fuels = getElMixFuels(xmlDoc);
        var currency = getCurrency(xmlDoc);

        var series_elmix_fuels =  getElMixFuelSeriesWOImportExport(elmix_fuels);

        // var columns_years = getYearsColumnsRes(years, 'kton');
        // var columns_years_costs = getYearsColumnsRes(years, '1000' + currency);
        let $div = $('#jqxChart_energy_output');
        let $div1 = $('#jqxChartCarbonCost');
        let columns_years = getColYr_display_onlyDecimal( years, 'kton')
        let columns_years_costs = getColYr_display_onlyDecimal( years, 'mil. ' + currency)

        //definisi prvi data adapter za grid i chart po sektorima
        var theme='bootstrap';

        var energy_output = {
            url: 'data/TRA_environment_co2_data.php?action=emissions&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
        };   
        var dataAdapter_energy_output = new $.jqx.dataAdapter(energy_output);                     

        let chartSettings = getChartSettings(title='', description='', dataAdapter_energy_output, series_elmix_fuels, 'kton');
        $('#jqxChart_energy_output').jqxChart(chartSettings);

        //drugi data adapter za transponirane vrijednosti, po godinama
        var energy_output_trans =
        {
            url: 'data/TRA_environment_co2_data.php?action=emissions&trans=1',
            root: 'data',
            datatype: 'json',
            cache: false,
                             datafields: [
                       { name: 'name', type: 'string' },
               		{ name: '1990', type: 'number' },
               		{ name: '2000', type: 'number' },
                       { name: '2005', type: 'number' },
               		{ name: '2010', type: 'number' },
                       { name: '2015', type: 'number' },
               		{ name: '2020', type: 'number' },
                       { name: '2025', type: 'number' },
                       { name: '2030', type: 'number' },
                       { name: '2035', type: 'number' },
                       { name: '2040', type: 'number' },
                       { name: '2045', type: 'number' },
                       { name: '2050', type: 'number' },
               		]
	       };  
            var dataAdapter_energy_output_trans = new $.jqx.dataAdapter(energy_output_trans);     
            
       //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgrid_energy_output_trans").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter_energy_output_trans,
            editable: false,
            selectionmode: 'multiplecellsadvanced',
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            columns: columns_years
        });
                
        $("#pngCO2").click(function () {
            // call the export server to create a PNG image
            $('#jqxChart_energy_output').jqxChart('saveAsPNG', 'CO2Emissions.png',  getExportServer());
        });
        $("#xlsCO2").click(function() {
            $("#jqxgrid_energy_output_trans").jqxGrid('exportdata', 'xls', 'CO2 emissions in the system');
        });
        
        let res = true;
        $("#resizeColumns_co2").click(function () {
            if(res){
                $('#jqxgrid_energy_output_trans').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgrid_energy_output_trans').jqxGrid('autoresizecolumns');
            }
            res = !res;        
        });
        // $("#decUp").on('click', function(e){
        //     e.preventDefault();
        //     e.stopImmediatePropagation();
        //     window.d++;
        //     window.decimal = 'd' + parseInt(window.d);
        //     $('#jqxgrid_energy_output_trans').jqxGrid('refresh');
        //     $('#jqxgrid_energy_output_trans').jqxGrid('refreshaggregates');
        // });

        // $("#decDown").on('click', function(e){
        //     e.preventDefault();
        //     e.stopImmediatePropagation();
        //     window.d--;
        //     window.decimal = 'd' + parseInt(window.d);
        //     $('#jqxgrid_energy_output_trans').jqxGrid('refresh');
        //     $('#jqxgrid_energy_output_trans').jqxGrid('refreshaggregates');
        // });

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////corbon content costs

           //dataadapter za energy output by years
           var carbonCost = {
            url: 'data/TRA_environment_co2_data.php?action=costs&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
        };   
        var daCarbonCost = new $.jqx.dataAdapter(carbonCost);                     
    
            
        let chartSettings2 = getChartSettings(title='', description='', daCarbonCost, series_elmix_fuels, 'mil. ' + currency);
        $('#jqxChartCarbonCost').jqxChart(chartSettings2);


             //drugi data adapter za transponirane vrijednosti, po godinama
        var carbonCost_t = {
            url: 'data/TRA_environment_co2_data.php?action=costs&trans=1',
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                   { name: 'name', type: 'string' },
               	    { name: '1990', type: 'number' },
               	    { name: '2000', type: 'number' },
                   { name: '2005', type: 'number' },
               	    { name: '2010', type: 'number' },
                   { name: '2015', type: 'number' },
               	    { name: '2020', type: 'number' },
                   { name: '2025', type: 'number' },
                   { name: '2030', type: 'number' },
                   { name: '2035', type: 'number' },
                   { name: '2040', type: 'number' },
                   { name: '2045', type: 'number' },
                   { name: '2050', type: 'number' },
               	]
	       };  
        var daCarbonCost_t = new $.jqx.dataAdapter(carbonCost_t);        
       //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgridCarbonCost").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daCarbonCost_t,
            editable: false,
            selectionmode: 'multiplecellsadvanced',
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            columns: columns_years_costs
        });
                
        $("#pngCO2Cost").click(function () {
            // call the export server to create a PNG image
            $('#jqxChartCarbonCost').jqxChart('saveAsPNG', 'CO2EmissionsCosts.png',  getExportServer());
        });
        $("#xlsCO2Cost").click(function() {
            $("#jqxgridCarbonCost").jqxGrid('exportdata', 'xls', 'CO2 emissions costs in the system');
        });

        let resC = true;
        $("#resizeColumns_c").click(function () {
            if(resC){
                $('#jqxgridCarbonCost').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgridCarbonCost').jqxGrid('autoresizecolumns');
            }
            resC = !resC;        
        });


        $('#loadermain').hide();   

        let chart = {};
        chart['1'] = $('#jqxChart_energy_output').jqxChart('getInstance');
        chart['2'] = $('#jqxChartCarbonCost').jqxChart('getInstance');


        $(".switchChart").on('click', function (e) {
            e.preventDefault();
            var chartType = $(this).attr('data-chartType');
            var chartId = $(this).attr('id-chart');
            $('.widget-toolbar a').switchClass( "green", "grey" );
            $('#'+chartType).switchClass( "grey", "green" );
            chart[chartId].seriesGroups[0].type = CHART_TYPE[chartType];
            chart[chartId].update();  
            // switch(chartId) {
            //     case '1':
            //         chart1.seriesGroups[0].type = CHART_TYPE[chartType];
            //         chart1.update();  
            //     break;
            //     case '2':
            //         chart2.seriesGroups[0].type = CHART_TYPE[chartType];
            //         chart2.update();  
            //     break;
            // }
        });
        $(".toggleLabels").on('click', function (e) {
            e.preventDefault();
            var chartId = $(this).attr('id-chart');
            chart[chartId].seriesGroups[0].labels.visible = !chart[chartId].seriesGroups[0].labels.visible;
            chart[chartId].update();    
        });

        $("#showLog_co2").click(function (e) {
            e.preventDefault();
            $('#log_co2').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.CO2_S.title}</h4>
                    ${DEF.CO2_S.definition}
                </div>
            `);
            $('#log_co2').toggle('slow');
        }); 
        $("#showLog_co2c").click(function (e) {
            e.preventDefault();
            $('#log_co2c').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.CO2_C.title}</h4>
                    ${DEF.CO2_C.definition}
                </div>
            `);
            $('#log_co2c').toggle('slow');
        });      
    }else{
        $( "#ace-settings-btn" ).trigger( "click" );
        $('#loadermain').hide();
    }  
});
</script>

