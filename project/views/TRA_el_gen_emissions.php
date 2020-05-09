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
        <small class="hidden-xs hidden-sm"><?php echo $emissions_from_el_gen;?></small>
    </h4>
</div>  

<?php
if (isset($_SESSION['case']))
{
?>
<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
        <li class="active">
			<a data-toggle="tab" href="#co2">
                <span class="hidden-xs hidden-sm"> <?php echo $emissions_from_el_gen ; ?> </span>
				<span class="badge badge-danger">CO<sub>2</sub></span>
			</a>
		</li>
        <li >
			<a data-toggle="tab" href="#so2">
            <span  class="hidden-xs hidden-sm"><?php echo $emissions_from_el_gen ; ?></span>
				<span class="badge badge-primary">SO<sub>2</sub></span>
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#nox">
            <span  class="hidden-xs hidden-sm"><?php echo $emissions_from_el_gen; ?></span>
				<span class="badge badge-warning">NO<sub>X</sub></span>
			</a>
		</li>

        <li>
			<a data-toggle="tab" href="#pm">
            <span  class="hidden-xs hidden-sm"><?php echo $emissions_from_el_gen; ?></span>
				<span class="badge badge-success">PM</span>
			</a>
        </li>
        <!-- <li>
			<a data-toggle="tab" href="#systemCo2">
                Emission from system
				<span class="badge badge-danger">CO<sub>2</sub></span>
			</a>
        </li> -->
	</ul>
	<div class="tab-content">
        <div id="co2" class="tab-pane fade in active">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logCO2" style="display:none">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $emissions_from_el_gen . ' - CO<sub>2</sub>'; ?></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogCO2"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of CO>sub>2</sub> emissions">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsCo2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngCo2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="barChart" data-chartType="barChart" id-chart="3"  class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Bar Chart">
                                    <i class="ace-icon fa fa-bar-chart"></i>
                                </a>
                    
                                <a href="#" data-action="mix" id="lineChart" data-chartType="lineChart" id-chart="3" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Line Chart">
                                    <i class="ace-icon fa fa-line-chart"></i>
                                </a>
                
                                <a href="#" data-action="mix" id="areaChart" data-chartType="areaChart" id-chart="3" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Area Chart">
                                    <i class="ace-icon fa fa-area-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart100" data-chartType="stackedChart100" id-chart="3" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart 100">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="3" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="3"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>

                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="resizeColumns_co2" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_co2' style="width:100%; height:250px;"></div>
                                    <div id="jqxgrid_co2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="so2" class="tab-pane fade in ">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logSO2" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $emissions_from_el_gen . ' - SO<sub>2</sub>'; ?></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogSO2"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of SO<sub>2</sub> emissions">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                            <a href="#" data-action="mix"  id="xlsSo2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngSo2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
                                <a href="#" data-action="mix" id="resizeColumns_so2" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_so2' style="width:100%; height:250px;"></div>
                                    <div id="jqxgrid_so2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="nox" class="tab-pane fade in ">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logNOX" style="display:none">
  
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $emissions_from_el_gen . ' - NO<sub>X</sub>'; ?></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogNOX"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton NOx emissions">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsNox"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngNox"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
                                <a href="#" data-action="mix" id="resizeColumns_nox" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_nox' style="width:100%; height:250px;"></div>
                                    <div id="jqxgrid_nox"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 

        <div id="pm" class="tab-pane fade in ">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logPM" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $emissions_from_el_gen . ' - PM'; ?></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogPM"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of other emissions">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsPm"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngPm"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                            <a href="#" data-action="mix" id="barChart" data-chartType="barChart" id-chart="4"  class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Bar Chart">
                                    <i class="ace-icon fa fa-bar-chart"></i>
                                </a>
                    
                                <a href="#" data-action="mix" id="lineChart" data-chartType="lineChart" id-chart="4" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Line Chart">
                                    <i class="ace-icon fa fa-line-chart"></i>
                                </a>
                
                                <a href="#" data-action="mix" id="areaChart" data-chartType="areaChart" id-chart="4" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Area Chart">
                                    <i class="ace-icon fa fa-area-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart100" data-chartType="stackedChart100" id-chart="4" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart 100">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="4" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="4"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="resizeColumns_pm" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_pm' style="width:100%; height:250px;"></div>
                                    <div id="jqxgrid_pm"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!-- 
        <div id="systemCo2" class="tab-pane fade in ">
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">System CO<sub>2</sub> emissions</h4>
                            <div class="widget-toolbar">
                            <a href="#" data-action="mix"  id="xlsSystemCO2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngSystemCO2"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="barChart" data-chartType="barChart" id-chart="5"  class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Bar Chart">
                                    <i class="ace-icon fa fa-bar-chart"></i>
                                </a>
                    
                                <a href="#" data-action="mix" id="lineChart" data-chartType="lineChart" id-chart="5" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Line Chart">
                                    <i class="ace-icon fa fa-line-chart"></i>
                                </a>
                
                                <a href="#" data-action="mix" id="areaChart" data-chartType="areaChart" id-chart="5" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Area Chart">
                                    <i class="ace-icon fa fa-area-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart100" data-chartType="stackedChart100" id-chart="5" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart 100">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="5" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" id="resizeColumns_pm" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_systemCO2' style="width:100%; height:250px;"></div>
                                    <div id="jqxGrid_systemCO2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

    </div> 
</div> 
 

 <?php
}
else{
    echo "<div class='jqx-validator-warning-label-big'>$no_case_selected $create_new_or_edit_exisiting_case</div>";
}
?>

<script src="classes/js/Chart.Class.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {

session = getSession();
access();
var casename = session.case;  
var user = session.us;  

if (typeof(casename) != 'undefined')   {
    var theme = 'bootstrap';
    var xmlDoc = getXML(casename, user);
    var unit = getUnit(xmlDoc);
    var years = getYears(xmlDoc);
    var elmix_fuels = getElMixFuels(xmlDoc);
    var currency = getCurrency(xmlDoc);

    var series_years = getYearsSeries(years);
    var series_elmix_fuels =  getElMixFuelSeriesWOImportExport(elmix_fuels);
    //var columns_years = getYearsColumnsRes(years, 'kton');

    let columns_years = getColYr_display_onlyDecimal(years, 'kton')


        
    //dataadapter za energy output by years
    var so2 = {
        url: 'data/TRA_el_gen_emissions_data.php?action=SO2&trans=0',
        root: 'data',
        datatype: 'json',
        cache: true,
        datafields: [
            { name: 'year', type: 'string' },
    		{ name: 'Hydro', type: 'number' },
    		{ name: 'Coal', type: 'number' },
    		{ name: 'Oil', type: 'number' },
    		{ name: 'Gas', type: 'number' },
            { name: 'Biofuels', type: 'number' },
            { name: 'Peat', type: 'number' },
            { name: 'Waste', type: 'number' },
            { name: 'OilShale', type: 'number' },
            { name: 'Solar', type: 'number' },
            { name: 'Wind', type: 'number' },
            { name: 'Geothermal', type: 'number' },
            { name: 'Nuclear', type: 'number' },
            
    		]
    };   
    var dataAdapter_so2 = new $.jqx.dataAdapter(so2);                     

    let jqxChart_so2 = getChartSettings(title='', description='', dataAdapter_so2, series_elmix_fuels, 'kton');
    $('#jqxChart_so2').jqxChart(jqxChart_so2);
    

    //drugi data adapter za transponirane vrijednosti, po godinama
    var so2_trans = {
        url: 'data/TRA_el_gen_emissions_data.php?action=SO2&trans=1',
        root: 'data',
        datatype: 'json',
        cache: true,
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
    var dataAdapter_so2_trans = new $.jqx.dataAdapter(so2_trans); 
     
    //setting i inicijalizacija za transponirani grid po godinamma
    $("#jqxgrid_so2").jqxGrid({
        autoheight: true,
        autorowheight: true,
        width: '100%',
        theme: theme,
        source: dataAdapter_so2_trans,
        editable: false,
        selectionmode: 'multiplecellsadvanced',
        showstatusbar: true,
        showaggregates: true,
        columnsresize:true,
        columns: columns_years  
    });
              
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////NOX
           var nox =
            {
            url: 'data/TRA_el_gen_emissions_data.php?action=NOX&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                { name: 'year', type: 'string' },
        		{ name: 'Hydro', type: 'number' },
        		{ name: 'Coal', type: 'number' },
        		{ name: 'Oil', type: 'number' },
        		{ name: 'Gas', type: 'number' },
                { name: 'Biofuels', type: 'number' },
                { name: 'Peat', type: 'number' },
                { name: 'Waste', type: 'number' },
                { name: 'OilShale', type: 'number' },
                { name: 'Solar', type: 'number' },
                { name: 'Wind', type: 'number' },
                { name: 'Geothermal', type: 'number' },
                { name: 'Nuclear', type: 'number' },
                
        		]
        };   
        var dataAdapter_nox = new $.jqx.dataAdapter(nox);                     
    
        //drugi data adapter za transponirane vrijednosti, po godinama
        var nox_trans =
        {
            url: 'data/TRA_el_gen_emissions_data.php?action=NOX&trans=1',
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
            var dataAdapter_nox_trans = new $.jqx.dataAdapter(nox_trans); 
  
    
    let nox_chart = getChartSettings(title='', description='', dataAdapter_nox, series_elmix_fuels, 'kton');
    $('#jqxChart_nox').jqxChart(nox_chart);

    var dataAdapter_nox_trans = new $.jqx.dataAdapter(nox_trans);
                       
    //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgrid_nox").jqxGrid(
            {
                autoheight: true,
                autorowheight: true,
                width: '100%',
                theme: theme,
                source: dataAdapter_nox_trans,
                editable: false,
                selectionmode: 'multiplecellsadvanced',
                showstatusbar: true,
                showaggregates: true,
                columnsresize:true,
                columns: columns_years
                });
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////CO2             
 var co2 =
            {
            url: 'data/TRA_el_gen_emissions_data.php?action=CO2&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                { name: 'year', type: 'string' },
        		{ name: 'Hydro', type: 'number' },
        		{ name: 'Coal', type: 'number' },
        		{ name: 'Oil', type: 'number' },
        		{ name: 'Gas', type: 'number' },
                { name: 'Biofuels', type: 'number' },
                { name: 'Peat', type: 'number' },
                { name: 'Waste', type: 'number' },
                { name: 'OilShale', type: 'number' },
                { name: 'Solar', type: 'number' },
                { name: 'Wind', type: 'number' },
                { name: 'Geothermal', type: 'number' },
                { name: 'Nuclear', type: 'number' },
                
        		]
        };   
        var dataAdapter_co2 = new $.jqx.dataAdapter(co2);   
        
        
        let co2_chart = getChartSettings(title='', description='', dataAdapter_co2, series_elmix_fuels, 'kton');
        $('#jqxChart_co2').jqxChart(co2_chart);
    
        //drugi data adapter za transponirane vrijednosti, po godinama
        var co2_trans =
        {
            url: 'data/TRA_el_gen_emissions_data.php?action=CO2&trans=1',
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
    var dataAdapter_co2_trans = new $.jqx.dataAdapter(co2_trans); 
  
            
           
    //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgrid_co2").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter_co2_trans,
            editable: false,
            selectionmode: 'multiplecellsadvanced',
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            columns: columns_years
        });
                
   ////////////////////////////////////////////////////////////////////////////////////////////////////PM             
        var pm =
            {
            url: 'data/TRA_el_gen_emissions_data.php?action=PM&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                { name: 'year', type: 'string' },
        		{ name: 'Hydro', type: 'number' },
        		{ name: 'Coal', type: 'number' },
        		{ name: 'Oil', type: 'number' },
        		{ name: 'Gas', type: 'number' },
                { name: 'Biofuels', type: 'number' },
                { name: 'Peat', type: 'number' },
                { name: 'Waste', type: 'number' },
                { name: 'OilShale', type: 'number' },
                { name: 'Solar', type: 'number' },
                { name: 'Wind', type: 'number' },
                { name: 'Geothermal', type: 'number' },
                { name: 'Nuclear', type: 'number' },
                
        		]
        };   
        var dataAdapter_pm = new $.jqx.dataAdapter(pm);                     
     
        let pm_chart = getChartSettings(title='', description='', dataAdapter_pm, series_elmix_fuels, 'kton');
        $('#jqxChart_pm').jqxChart(pm_chart);

        var pm_trans =
        {
            url: 'data/TRA_el_gen_emissions_data.php?action=PM&trans=1',
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
           
    var dataAdapter_pm_trans = new $.jqx.dataAdapter(pm_trans);

            
    //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgrid_pm").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter_pm_trans,
            editable: false,
            selectionmode: 'multiplecellsadvanced',
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            columns: columns_years 
        });
            

            let resCO2 = true;
            $("#resizeColumns_co2").click(function () {
                if(resCO2){
                    $('#jqxgrid_co2').jqxGrid('autoresizecolumn', 'name');
                }
                else{
                    $('#jqxgrid_co2').jqxGrid('autoresizecolumns');
                }
                resCO2 = !resCO2;        
            });
            let resSO2 = true;
            $("#resizeColumns_so2").click(function () {
                if(resSO2){
                    $('#jqxgrid_so2').jqxGrid('autoresizecolumn', 'name');
                }
                else{
                    $('#jqxgrid_so2').jqxGrid('autoresizecolumns');
                }
                resSO2 = !resSO2;        
            });
            let resNOX = true;
            $("#resizeColumns_nox").click(function () {
                if(resNOX){
                    $('#jqxgrid_nox').jqxGrid('autoresizecolumn', 'name');
                }
                else{
                    $('#jqxgrid_nox').jqxGrid('autoresizecolumns');
                }
                resNOX = !resNOX;        
            });
            let resPM = true;
            $("#resizeColumns_pm").click(function () {
                if(resPM){
                    $('#jqxgrid_pm').jqxGrid('autoresizecolumn', 'name');
                }
                else{
                    $('#jqxgrid_pm').jqxGrid('autoresizecolumns');
                }
                resPM = !resPM;        
            });





            $("#xlsCo2").click(function() {
                $("#jqxgrid_co2").jqxGrid('exportdata', 'xls', 'CO2 emissions');
            });
            $("#pngCo2").click(function() {
                $("#jqxChart_co2").jqxChart('saveAsPNG', 'CO2 emissions.png',  getExportServer());
            }); 

            $("#xlsSo2").click(function() {
                $("#jqxgrid_so2").jqxGrid('exportdata', 'xls', 'SO2 emissions');
            });
            $("#pngSo2").click(function() {
                $("#jqxChart_so2").jqxChart('saveAsPNG', 'SO2 emissions.png',  getExportServer());
            }); 

            $("#xlsNox").click(function() {
                $("#jqxgrid_nox").jqxGrid('exportdata', 'xls', 'NOx emissions');
            });
            $("#pngNox").click(function() {
                $("#jqxChart_nox").jqxChart('saveAsPNG', 'NOx emissions.png',  getExportServer());
            }); 

            $("#xlsPm").click(function() {
                $("#jqxgrid_pm").jqxGrid('exportdata', 'xls', 'PM emissions');
            });
            $("#pngPm").click(function() {
                $("#jqxChart_pm").jqxChart('saveAsPNG', 'PM_emissions.png',  getExportServer());
            }); 

            // $("#xlsSystemCO2").click(function() {
            //     $("#jqxGrid_systemCO2").jqxGrid('exportdata', 'xls', 'System CO2 emissions');
            // });
            // $("#pngSystemCO2").click(function() {
            //     $("#jqxChart_systemCO2").jqxChart('saveAsPNG', 'System_CO2_emissions.png',  getExportServer());
            // }); 
            

        $('#loadermain').hide();  

        let chart = {};
        chart['1'] = $('#jqxChart_so2').jqxChart('getInstance');
        chart['2'] = $('#jqxChart_nox').jqxChart('getInstance');
        chart['3'] = $('#jqxChart_co2').jqxChart('getInstance');
        chart['4'] = $('#jqxChart_pm').jqxChart('getInstance');
        //var chart5 = $('#jqxChart_systemCO2').jqxChart('getInstance');


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
            //     case '3':
            //         chart3.seriesGroups[0].type = CHART_TYPE[chartType];
            //         chart3.update();  
            //     break;
            //     case '4':
            //         chart4.seriesGroups[0].type = CHART_TYPE[chartType];
            //         chart4.update();  
            //     break;
            //     case '5':
            //         chart5.seriesGroups[0].type = CHART_TYPE[chartType];
            //         chart5.update();  
            //     break;
            //     default:
            // }
        });
        $(".toggleLabels").on('click', function (e) {
            e.preventDefault();
            var chartId = $(this).attr('id-chart');
            chart[chartId].seriesGroups[0].labels.visible = !chart[chartId].seriesGroups[0].labels.visible;
            chart[chartId].update();    
        });


        $("#showLogCO2").click(function (e) {
            e.preventDefault();
            $('#logCO2').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.CO2.title}</h4>
                    ${DEF.CO2.definition}
                </div>
            `);
            $('#logCO2').toggle('slow');
        });

        $("#showLogNOX").click(function (e) {
            e.preventDefault();
            $('#logNOX').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.NOX.title}</h4>
                    ${DEF.NOX.definition}
                </div>
            `);
            $('#logNOX').toggle('slow');
        });

        $("#showLogSO2").click(function (e) {
            e.preventDefault();
            $('#logSO2').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.SO2.title}</h4>
                    ${DEF.SO2.definition}
                </div>
            `);
            $('#logSO2').toggle('slow');
        });

        $("#showLogPM").click(function (e) {
            e.preventDefault();
            $('#logPM').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.PM.title}</h4>
                    ${DEF.PM.definition}
                </div>
            `);
            $('#logPM').toggle('slow');
        });

    }else{
            $( "#ace-settings-btn" ).trigger( "click" );
            $('#loadermain').hide();
        }        
  });
</script>
 