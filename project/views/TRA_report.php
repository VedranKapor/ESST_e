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
        <small class="hidden-xs hidden-sm">Electricity supply planning</small>
        <small class="hidden-xs hidden-sm"><i class="ace-icon fa fa-angle-double-right"></i></small>
        <small class="hidden-xs hidden-sm">Analysis results</small>
    </h4>
</div>  

<?php
if (isset($_SESSION['case']))
{
?>

<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
        <li class="active">
			<a data-toggle="tab" href="#InstalledCap">
                <span class="hidden-md hidden-lg">Capacity</span>
                <span class="hidden-xs hidden-sm">Existing and committed capacity in system</span>
				<span class="badge badge-primary">MW</span>
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#newCap">
            <span class="hidden-md hidden-lg">New</span>
            <span class="hidden-xs hidden-sm">Additional Capacity Needed (without Reserve Capacity)</span>
				<span class="badge badge-warning">MW</span>
			</a>
		</li>
        <li>
			<a data-toggle="tab" href="#RC">
            <span class="hidden-md hidden-lg">Reserve</span>
            <span class="hidden-xs hidden-sm">Reserve capacity</span>
				<span class="badge badge-warning">MW</span>
			</a>
		</li>
        <li>
			<a data-toggle="tab" href="#instCap">
            <span class="hidden-md hidden-lg">TIC</span>
            <span class="hidden-xs hidden-sm">Total installed capacity in the system</span>
				<span class="badge badge-default">MW</i></span>
			</a>
        </li>
        <li>
			<a data-toggle="tab" href="#elGen">
            <span class="hidden-md hidden-lg">Generation</span>
            <span class="hidden-xs hidden-sm">Electricity generation</span>
                <span class="badge badge-danger">GWh</i></span>
                <!-- <i class="fa fa-bolt" aria-hidden="true"> -->
			</a>
		</li>
        <li>
			<a data-toggle="tab" href="#invCost">
            <span class="hidden-md hidden-lg">Investement</span>
            <span class="hidden-xs hidden-sm"><?php echo $investment_cost_for_added_cap?></span>
				<span class="badge badge-success"><i class="fa fa-money" aria-hidden="true"></i></span>
			</a>
        </li>

        <li>
			<a data-toggle="tab" href="#LCOE">
                
            <span class="hidden-md hidden-lg">LCOE</span>
                <span class="hidden-xs hidden-sm">Levelized Cost Of Electricity - LCOE</span>
				<span class="badge badge-success"><i class="fa fa-money" aria-hidden="true"></i></span>
			</a>
        </li>
        <li>
			<a data-toggle="tab" href="#AVGUC">
            <span class="hidden-md hidden-lg">ACoG</span>
                <span class="hidden-xs hidden-sm">Average Cost of Generation</span>
				<span class="badge badge-success"><i class="fa fa-money" aria-hidden="true"></i></span>
			</a>
        </li>
        <!-- <li>
			<a data-toggle="tab" href="#ensCap">
                 Additional capacity to satisfy ENS (hourly analysis)
				<span class="badge badge-info">MW</span>
			</a>
		</li> -->
	</ul>
	<div class="tab-content">

            <!-- installed capacity id = 6-->
        <div id="InstalledCap" class="tab-pane fade in active">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logIC" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title ">Installed capacity in system</h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogIC"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of Installed capacity">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsInstCap"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngInstCap"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>

                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="barChart" data-chartType="barChart" id-chart="6"  class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Bar Chart">
                                    <i class="ace-icon fa fa-bar-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="lineChart" data-chartType="lineChart" id-chart="6" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Line Chart">
                                    <i class="ace-icon fa fa-line-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="areaChart" data-chartType="areaChart" id-chart="6" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Area Chart">
                                    <i class="ace-icon fa fa-area-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart100" data-chartType="stackedChart100" id-chart="6" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart 100">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="6" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="6"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="rcIC" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_installed' style="width:100%; height:280px;"></div>
                                    <div id="jqxGrid_installed"></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- new capacity id = 1 -->
        <div id="newCap" class="tab-pane fade in">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logNC" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $new_capacity_needed; ?></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogNC"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of new capacity">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsNewCap"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngNewCap"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
                                <a href="#" data-action="mix" id="rcAC" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_power_output' style="width:100%; height:280px;"></div>
                                    <div id="jqxgrid_power_output_trans"></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- new capacity id = 9 -->
        <div id="RC" class="tab-pane fade in">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logRC" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">Reserve capacity</h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogRC"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of new reserve capacity">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsRC"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngRC"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="barChart" data-chartType="barChart" id-chart="9"  class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Bar Chart">
                                    <i class="ace-icon fa fa-bar-chart"></i>
                                </a>
                    
                                <a href="#" data-action="mix" id="lineChart" data-chartType="lineChart" id-chart="9" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Line Chart">
                                    <i class="ace-icon fa fa-line-chart"></i>
                                </a>
                
                                <a href="#" data-action="mix" id="areaChart" data-chartType="areaChart" id-chart="9" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Area Chart">
                                    <i class="ace-icon fa fa-area-chart"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart100" data-chartType="stackedChart100" id-chart="9" class="switchChart grey tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart 100">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="9" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>

                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="9"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="rcRC" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChartRC' style="width:100%; height:280px;"></div>
                                    <div id="jqxgridRC"></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--TIC id=3-->
        <div id="instCap" class="tab-pane fade in">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logTIC" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">Total installed capacity in the system</h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogTIC"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of Total Installed capacity">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsTIC"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngTIC"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
                                <a href="#" data-action="mix" id="rcTIC" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_TIC_output' style="width:100%; height:280px;"></div>
                                    <div id="jqxgrid_TIC_output_trans"></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- //el generation id=5-->
        <div id="elGen" class="tab-pane fade in">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logEG" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">Electricity generation</h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogEG"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of el generation">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsElGen"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngElGen"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
                                <a href="#" data-action="mix" id-chart="5"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="rcEG" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar ">
                                <div class="form-control input-sm" style="margin-top:4px" id='ddlUnits' ></div> 
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_elGen' style="width:100%; height:250px;"></div>
                                    <div id="jqxgrid_elGen_t"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- investment cost id=2-->
        <div id="invCost" class="tab-pane fade in">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logInvCost" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $investment_cost_for_added_cap; ?></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogInvCost"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of investment cost">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsInv"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngInv"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
                                <a href="#" data-action="mix" id="rcINV" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_investment' style="width:100%; height:280px;"></div>
                                    <div id="jqxgrid_investment_trans"></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--LCOE id=7-->
        <div id="LCOE" class="tab-pane fade in">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logLCOE" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">Levelized Cost Of Electricity - LCOE</h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogLCOE"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of Levelized Cost of Electricty">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsLCOE"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngLCOE"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="7"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="rcLCOE" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_LCOE' style="width:100%; height:280px;"></div>
                                    <div id="jqxGrid_LCOE"></div> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--AVGUC id=8-->
        <div id="AVGUC" class="tab-pane fade in">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="logAVGUC" style="display:none">
                        <div class='bs-callout bs-callout-default' >
                            <h4>Average Cost of Generation</h4>
                            Average Cost of Generation is value calculated for each year by considering all costs that occur in the system in that year (e.g. annualized investment cost of additional and reserve capacities, FOM of total capacity, VOM of total generation, annual fuel costs, annual CO2 costs) and total generation in that year.
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">Average Unit Cost</h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="showLogAVGUC"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton of Average unit cost">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix"  id="xlsAVGUC"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
                                    <i class="ace-icon fa fa-file-excel-o green"></i>
                                </a>
                                <a href="#" data-action="mix"  id="pngAVGUC"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
                                    <i class="ace-icon fa fa-file-image-o blue"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="8"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="rcAVGUC" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div>
                                    <div id='jqxChart_AVGUC' style="width:100%; height:280px;"></div>
                                    <div id="jqxGrid_AVGUC"></div> 
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
<script src="classes/js/Chart.Class.js" type="text/javascript"></script>

<script type="text/javascript" src="scripts/generateColors.js"></script>
<!-- <script type="text/javascript" src="scripts/tra_report.js"></script>  -->

<script type="text/javascript">
$(document).ready(function () {
    access();
    session = getSession();
    var casename = session.case;  
    var user = session.us;  
    if (typeof(casename) != 'undefined'){   
        var xmlDoc = getXML(casename, user);
        var unitXML = getUnit(xmlDoc);
        var years = getYears(xmlDoc);
        var elmix_fuels = getElMixFuels(xmlDoc);
        var currency = getCurrency(xmlDoc);

        var series_years = getYearsSeries(years);
        var series_elmix_fuels =  getElMixFuelSeriesWOImportExport(elmix_fuels);

        var series_elmix_fuels_IE =  getElMixFuelSeries(elmix_fuels);

        let yearsInd = getYearsIndexes(years);
        let brojKolona = yearsInd.length;

        // let columns_years_elGen = getYearsCoulmns(years, "GWh", brojKolona);
        // let columns_years_MW = getYearsCoulmns(years, "MW", brojKolona);
        // let columns_years_Inv = getYearsCoulmns(years, "millions of "+currency, brojKolona);
        //let columns_years_AVGUC = getYearsCoulmns(years, currency + "/MWh", brojKolona);

        let columns_years_elGen = getColYr_e('', years, 'd', '');

        // let columns_years_MW = getColYr_e('', years, 'd', 'MW');
        // let columns_years_Inv = getColYr_e('', years, 'd', "mil. "+currency);
        // let columns_years_AVGUC = getColYr_e('', years, 'd', currency + "/MWh");

        let columns_years_MW = getColYr_display_onlyDecimal( years, 'MW');
        let columns_years_Inv = getColYr_display_onlyDecimal( years, "mil. "+currency);
        let columns_years_AVGUC = getColYr_display_onlyDecimal(years, currency + "/MWh");

        let columns_years_LCOE = getGroupedLCOECoulmns(years, currency + "/MWh", brojKolona);
        let LCOE_groups = getLCOEGroups(years);
        var LCOE_groups_series =  getGroupLCOESeries(years,  elmix_fuels);

// console.log(columns_years_LCOE);
//console.log(LCOE_groups_series);

        var theme = 'bootstrap';

    //////////////////////////////////////////////////////////////////////////////////////////////INSTALLED CAPACITY
        var srcInstalled =  {
            url: 'data/TRA_report_data.php?action=installed_power&trans=0',
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
                { name: 'Oil_shale', type: 'number' },
                { name: 'Solar', type: 'number' },
                { name: 'Wind', type: 'number' },
                { name: 'Geothermal', type: 'number' },
                { name: 'Nuclear', type: 'number' },
                
        		]
        };   
        var daInstalled = new $.jqx.dataAdapter(srcInstalled);                    
        

        let chartIC = getChartSettings(title='', description='', daInstalled, series_elmix_fuels, 'MW');
        $('#jqxChart_installed').jqxChart(chartIC);

        var srcInstalledTrans =  {
            url: 'data/TRA_report_data.php?action=installed_power&trans=1',
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
           
        var daInstalledTrans = new $.jqx.dataAdapter(srcInstalledTrans);  

        $("#jqxGrid_installed").jqxGrid({
                autoheight: true,
                autorowheight: true,
                width: '100%',
                theme: theme,
                source: daInstalledTrans,
                editable: false,
                showstatusbar: true,
                columnsresize:true,
                showaggregates: true,
                selectionmode: 'multiplecellsadvanced',
                columns: columns_years_MW
        });

    /////////////////////////////////////////////////////////////////////////////////////////////ADDITIONAL CAPACITY

        var srcAddCap = {
            url: 'data/TRA_report_data.php?action=power_output&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            async: true,
        };   
        var daAddCap = new $.jqx.dataAdapter(srcAddCap);        

        let chartAC = getChartSettings(title='', description='', daAddCap, series_elmix_fuels, 'MW');
        $('#jqxChart_power_output').jqxChart(chartAC);


        var srcAddCapTrans = {
            url: 'data/TRA_report_data.php?action=power_output&trans=1',
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
        var daAddCapTrans = new $.jqx.dataAdapter(srcAddCapTrans);      


        $("#jqxgrid_power_output_trans").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daAddCapTrans,
            editable: false,
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_MW              
        });   
        
    /////////////////////////////////////////////////////////////////////////////////////////////RESERVE CAPACITY

    var srcRC = {
            url: 'data/TRA_report_data.php?action=RC&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            async: true,
        };   
        var daRC = new $.jqx.dataAdapter(srcRC);        
        
        let chartRC = getChartSettings(title='', description='', daRC, series_elmix_fuels, 'MW');
        $('#jqxChartRC').jqxChart(chartRC);

        var srcRCTrans = {
            url: 'data/TRA_report_data.php?action=RC&trans=1',
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
        var daRCTrans = new $.jqx.dataAdapter(srcRCTrans);  

        $("#jqxgridRC").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daRCTrans,
            editable: false,
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_MW              
        });  
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////INVESTMENT
        var srcInv = {
            url: 'data/TRA_report_data.php?action=investment&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
        };  
        var daInv = new $.jqx.dataAdapter(srcInv);    

        let chartINV = getChartSettings(title='', description='', daInv, series_elmix_fuels,  "mil. "+currency);
        $('#jqxChart_investment').jqxChart(chartINV);

        var srcInvTrans = {
            url: 'data/TRA_report_data.php?action=investment&trans=1',
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
        var daInvTrans = new $.jqx.dataAdapter(srcInvTrans); 

        $("#jqxgrid_investment_trans").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',      
            theme: theme,
            source: daInvTrans,
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_Inv
        }); //kraj inicijalizacije i settinga za trans grid     
                
    /////////////////////////////////////////////////////////////////////////////////////////////////////TOTAL INSTALLED POWER 
        var srcTIC = {
            url: 'data/TRA_report_data.php?action=TIC_output&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            async: true,
        };   

        var daTIC = new $.jqx.dataAdapter(srcTIC);                   


        // {
        //                 type: 'stepline',
        //                 series: [
        //                     { dataField: 'NP', displayText: 'Total needed power', color: '#0066cc', opacity: 0.8, lineWidth: 3 ,  dashStyle: '6,2' },
        //                     { dataField: 'RS', displayText: 'Reserve Capacity', color: '#FF0000', opacity: 0.8, lineWidth: 3 ,  dashStyle: '6,2' }
        //                 ]
        //             },
        let chartTIC = getChartSettings(title='', description='', daTIC, series_elmix_fuels, 'MW');
        $('#jqxChart_TIC_output').jqxChart(chartTIC);

        var srcTICTrans = {
            url: 'data/TRA_report_data.php?action=TIC_output&trans=1',
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
                
        var daTICTrans = new $.jqx.dataAdapter(srcTICTrans); 

        $("#jqxgrid_TIC_output_trans").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daTICTrans,
            editable: false,
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_MW
        }); 


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////EL GENERATION
        var elGen ={
            url: 'data/TRA_report_data.php?action=elDef&trans=0',
            //url: 'data/SecondaryEnergySupplies_data.php?trans=0',
            //url: 'data/SES_elmix_values_data.php',
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
                { name: 'ImportExport', type: 'number' },
                
        		]
        };   
        var dataAdapter_elGen = new $.jqx.dataAdapter(elGen);                     
                          
        let chartEG = getChartSettings(title='', description='', dataAdapter_elGen, series_elmix_fuels_IE, 'GWh');
        $('#jqxChart_elGen').jqxChart(chartEG);
     
        var elGen_t = {
            url: 'data/TRA_report_data.php?action=elDef&trans=1',
            //url: 'data/SecondaryEnergySupplies_data.php?trans=1',
            //url: 'data/SES_elmix_values_data.php?trans=1',
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
        var dataAdapter_elGen_t = new $.jqx.dataAdapter(elGen_t); 

        $("#jqxgrid_elGen_t").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter_elGen_t,
            editable: false,
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_elGen                  
        }); //kraj inicijalizacije i settinga za trans grid
 
        $('#loadermain').hide();  


        ////////////////////////////////////////////////LCOE//////////////////////////////////////////////////////////////////////

        
    //////////////////////////////////////////////////////////////////////////////////////////////LCOE
    var srcLCOE =  {
            url: 'data/TRA_report_data.php?action=lcoe&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            // datafields: [
            //     { name: 'year', type: 'string' },
        	// 	{ name: 'Hydro', type: 'number' },
        	// 	{ name: 'Coal', type: 'number' },
        	// 	{ name: 'Oil', type: 'number' },
        	// 	{ name: 'Gas', type: 'number' },
            //     { name: 'Biofuels', type: 'number' },
            //     { name: 'Peat', type: 'number' },
            //     { name: 'Waste', type: 'number' },
            //     { name: 'Oil_shale', type: 'number' },
            //     { name: 'Solar', type: 'number' },
            //     { name: 'Wind', type: 'number' },
            //     { name: 'Geothermal', type: 'number' },
            //     { name: 'Nuclear', type: 'number' },
                
        	// 	]
        };   
        var daLCOE = new $.jqx.dataAdapter(srcLCOE);  

        let chartLCOE = getChartSettings(title='', description='', daLCOE, series_elmix_fuels, currency + '/MWh', type='column');
        $('#jqxChart_LCOE').jqxChart(chartLCOE);
        
        // chart7.seriesGroups[0].labels.angle = 90;
        // chart7.update();

        var srcLCOETrans =  {
            url: 'data/TRA_report_data.php?action=lcoe&trans=1',
            root: 'data',
            datatype: 'json',
            cache: false,
        //     datafields: [
        //         { name: 'name', type: 'string' },
        // 		{ name: '1990', type: 'number' },
        // 		{ name: '2000', type: 'number' },
        //         { name: '2005', type: 'number' },
        // 		{ name: '2010', type: 'number' },
        //         { name: '2015', type: 'number' },
        // 		{ name: '2020', type: 'number' },
        //         { name: '2025', type: 'number' },
        //         { name: '2030', type: 'number' },
        //         { name: '2035', type: 'number' },
        //         { name: '2040', type: 'number' },
        //         { name: '2045', type: 'number' },
        //         { name: '2050', type: 'number' },
        // 		]
        }; 
           
        var daLCOETrans = new $.jqx.dataAdapter(srcLCOETrans);  


        $("#jqxGrid_LCOE").jqxGrid({
                autoheight: true,
                autorowheight: true,
                width: '100%',
                theme: theme,
                source: daLCOETrans,
                editable: false,
                showstatusbar: false,
                columnsresize:true,
                showaggregates: false,
                selectionmode: 'multiplecellsadvanced',
                columns: columns_years_LCOE,
                columngroups: LCOE_groups
        });


    //////////////////////////////////////////////////////////////////////////////////////////////Average unit cost
    //chart
    var srcAVGUC =  {
            url: 'data/TRA_report_data.php?action=avguc&trans=0',
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                { name: 'year', type: 'string' },
        		{ name: 'INV', type: 'number' },
        		{ name: 'FOM', type: 'number' },
        		{ name: 'VOM', type: 'number' },
        		{ name: 'FC', type: 'number' },
                { name: 'CO2', type: 'number' },
                { name: 'Total', type: 'number' }
        	]
        };   
        var daAVGUC = new $.jqx.dataAdapter(srcAVGUC);                    

        series = [
            { dataField:'INV', displayText:'Investment' },
            { dataField:'FOM', displayText:'Fixed operating cost' },
            { dataField:'VOM', displayText:'Variable operating cost' },
            { dataField:'FC', displayText:'Fuel cost' },
            { dataField:'CO2', displayText:'CO2 cost' }
        ]

        let chartAVGUC = getChartSettings(title='', description='', daAVGUC, series, currency + '/MWh');
        $('#jqxChart_AVGUC').jqxChart(chartAVGUC);

        var srcAVGUCTrans =  {
            url: 'data/TRA_report_data.php?action=avguc&trans=1',
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
           
        var daAVGUCTrans = new $.jqx.dataAdapter(srcAVGUCTrans);  

        $("#jqxGrid_AVGUC").jqxGrid({
                autoheight: true,
                autorowheight: true,
                width: '100%',
                theme: theme,
                source: daAVGUCTrans,
                editable: false,
                showstatusbar: true,
                columnsresize:true,
                showaggregates: true,
                selectionmode: 'multiplecellsadvanced',
                //columns: [ {text:'AVGC', datafield:'year', cellsalign: 'right', cellsformat: 'f2', align: 'right' }];
                columns: columns_years_AVGUC,
                //columngroups: LCOE_groups
        });


        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////XLS PNG

        $("#xlsInstCap").click(function() {
            $("#jqxGrid_installed").jqxGrid('exportdata', 'xls', 'ENS capacites');
            });
        $("#pngInstCap").click(function() {
            $("#jqxChart_installed").jqxChart('saveAsPNG', 'ENS capacites.png',  getExportServer());
        }); 

        $("#xlsTIC").click(function() {
            $("#jqxgrid_TIC_output_trans").jqxGrid('exportdata', 'xls', 'Total installed capacites');
            });
        $("#pngTIC").click(function() {
            $("#jqxChart_TIC_output").jqxChart('saveAsPNG', 'Total installed capacites.png',  getExportServer());
        }); 
        
        $("#xlsNewCap").click(function() {
            $("#jqxgrid_power_output_trans").jqxGrid('exportdata', 'xls', 'Additional system capacities');
        });
        $("#pngNewCap").click(function() {
            //$('#jqxChart_power_output').jqxChart('saveAsPNG', 'myChart.png');
            $("#jqxChart_power_output").jqxChart('saveAsPNG', 'Additional system capacities.png', getExportServer());
        });   

        $("#xlsRC").click(function() {
            $("#jqxgridRC").jqxGrid('exportdata', 'xls', 'Reserve system capacities');
        });
        $("#pngRC").click(function() {
            $("#jqxChartRC").jqxChart('saveAsPNG', 'Reserve system capacities.png', getExportServer());
        }); 

        $("#xlsInv").click(function() {
            $("#jqxgrid_investment_trans").jqxGrid('exportdata', 'xls', 'Investement costs for new capacities');
        });
        $("#pngInv").click(function() {
            $("#jqxChart_investment").jqxChart('saveAsPNG', 'Investement costs for new capacities.png',  getExportServer());
        });  
        
        $("#xlsElGen").click(function() {
            $("#jqxgrid_elGen_t").jqxGrid('exportdata', 'xls', 'Electricity deficit');
        });
        $("#pngElGen").click(function() {
            $("#jqxChart_elGen").jqxChart('saveAsPNG', 'Electricity deficit.png',  getExportServer());
        });  

        $("#xlsLCOE").click(function() {
            $("#jqxGrid_LCOE").jqxGrid('exportdata', 'xls', 'LCOE');
        });
        $("#pngLCOE").click(function() {
            $("#jqxChart_LCOE").jqxChart('saveAsPNG', 'LCOE.png',  getExportServer());
        });  
        
        $("#xlsAVGUC").click(function() {
            $("#jqxGrid_AVGUC").jqxGrid('exportdata', 'xls', 'AVGUC');
        });
        $("#pngAVGUC").click(function() {
            $("#jqxChart_AVGUC").jqxChart('saveAsPNG', 'AVGUC.png',  getExportServer());
        }); 

        ////////////////////////////////////////////////////////LOG////////////////////////////////////////////////////////////////////////////
        $("#showLogIC").click(function (e) {
            e.preventDefault();
            $('#logIC').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.IC.title}</h4>
                    ${DEF.IC.definition}
                </div>
            `);
            $('#logIC').toggle('slow');
        });

        $("#showLogNC").click(function (e) {
            e.preventDefault();
            $('#logNC').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.AC.title}</h4>
                    ${DEF.AC.definition}
                </div>
            `);
            $('#logNC').toggle('slow');
        });

        $("#showLogRC").click(function (e) {
            e.preventDefault();
            $('#logRC').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.RC.title}</h4>
                    ${DEF.RC.definition}
                </div>
            `);
            $('#logRC').toggle('slow');
        });

        $("#showLogTIC").click(function (e) {
            e.preventDefault();
            $('#logTIC').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.TIC.title}</h4>
                    ${DEF.TIC.definition}
                </div>
            `);
            $('#logTIC').toggle('slow');
        });

        $("#showLogEG").click(function (e) {
            e.preventDefault();
            $('#logEG').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.EG.title}</h4>
                    ${DEF.EG.definition}
                </div>
            `);
            $('#logEG').toggle('slow');
        });

        $("#showLogInvCost").click(function (e) {
            e.preventDefault();
            $('#logInvCost').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.INV.title}</h4>
                    ${DEF.INV.definition}
                </div>
            `);
            $('#logInvCost').toggle('slow');
        });


        $("#showLogLCOE").click(function (e) {
            e.preventDefault();
            $('#logLCOE').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.LCOE.title}</h4>
                    ${DEF.LCOE.definition}
                </div>
            `);
            $('#logLCOE').toggle('slow');
        });

        $("#showLogAVGUC").click(function (e) {
            e.preventDefault();
            $('#logAVGUC').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.AVGUC.title}</h4>
                    ${DEF.AVGUC.definition}
                </div>
            `);
            $('#logAVGUC').toggle('slow');
        });

        ////////////////////////////////////////////////////////CHART SWITCH//////////////////////////////////////////////////////////////////////



        let chart = {};
        chart['1'] = $('#jqxChart_power_output').jqxChart('getInstance');
        chart['2'] = $('#jqxChart_investment').jqxChart('getInstance');
        chart['3'] = $('#jqxChart_TIC_output').jqxChart('getInstance');
        chart['5'] = $('#jqxChart_elGen').jqxChart('getInstance');
        chart['6'] = $('#jqxChart_installed').jqxChart('getInstance');
        chart['7'] = $('#jqxChart_LCOE').jqxChart('getInstance');
        chart['8'] = $('#jqxChart_AVGUC').jqxChart('getInstance');
        chart['9'] = $('#jqxChartRC').jqxChart('getInstance');

        chart['7'].seriesGroups[0].labels.angle = 90;
        chart['7'].update();

        $(".switchChart").on('click', function (e) {
            e.preventDefault();
            var chartType = $(this).attr('data-chartType');
            var chartId = $(this).attr('id-chart');
            $('.widget-toolbar a').switchClass( "green", "grey" );
            $('#'+chartType).switchClass( "grey", "green" );
            chart[chartId].seriesGroups[0].type = CHART_TYPE[chartType];
            if(chartType == 'barChart'){
                chart[chartId].seriesGroups[0].labels.angle = 90;
            }else{
                chart[chartId].seriesGroups[0].labels.angle = 0;
            }
            chart[chartId].update(); 
        });
        $(".toggleLabels").on('click', function (e) {
            e.preventDefault();
            var chartId = $(this).attr('id-chart');
            chart[chartId].seriesGroups[0].labels.visible = !chart[chartId].seriesGroups[0].labels.visible;
            chart[chartId].update();    
        });


        ///////////////////////////////////////////////////////////UNIT CONVERTER/////////////////////////////////////////////////////////////////

        var srcUnits = [
            "ktoe",
            "Mtoe",
            "PJ",
            "GWh"
        ]

            //potrebno sanmo za el generation i iz clase se vraca u GWh jedinicama
        let unit = "GWh";
        $("#ddlUnits").jqxDropDownList({ 
            source: srcUnits, 
            theme: theme,  
            height: 16,
            width:65,
            autoDropDownHeight: true
        });
        //$("#ddlUnits").jqxDropDownList('selectItem', unit ); 
        $("#ddlUnits").jqxDropDownList('selectItem', 'GWh' );

        $('#ddlUnits').on('change', function (event){     
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var index = args.index;
                var item = args.item;
                // get item's label and value.
                var label = item.label;
                var value = item.value;
                var type = args.type; // keyboard, mouse or null depending on how the item was selected.
                //iz PJ
                if(unit == 'PJ' && value == 'ktoe'){ window.factor = ENERGY_CONVERTER['PJ_ktoe']; } 
                if(unit == 'PJ' && value == 'Mtoe'){ window.factor = ENERGY_CONVERTER['PJ_Mtoe']; }
                if(unit == 'PJ' &&  value == 'GWh'){ window.factor = ENERGY_CONVERTER['PJ_GWh']; }
                if(unit == 'PJ' &&  value == 'PJ'){ window.factor = ENERGY_CONVERTER['PJ_PJ']; }
                //iz ktoe
                if(unit == 'ktoe' && value == 'ktoe'){ window.factor = ENERGY_CONVERTER['ktoe_ktoe']; } 
                if(unit == 'ktoe' && value == 'Mtoe'){ window.factor =ENERGY_CONVERTER['ktoe_Mtoe']; }
                if(unit == 'ktoe' &&  value == 'GWh'){ window.factor = ENERGY_CONVERTER['ktoe_GWh']; }
                if(unit == 'ktoe' &&  value == 'PJ'){ window.factor = ENERGY_CONVERTER['ktoe_PJ']; }
                //iz Mtoe
                if(unit == 'Mtoe' && value == 'ktoe'){ window.factor = ENERGY_CONVERTER['Mtoe_ktoe']; } 
                if(unit == 'Mtoe' && value == 'Mtoe'){ window.factor = ENERGY_CONVERTER['Mtoe_Mtoe']; }
                if(unit == 'Mtoe' &&  value == 'GWh'){ window.factor = ENERGY_CONVERTER['Mtoe_Gwh']; }
                if(unit == 'Mtoe' &&  value == 'PJ'){ window.factor = ENERGY_CONVERTER['Mtoe_PJ']; }
                //iz GWh
                if(unit == 'GWh' && value == 'ktoe'){ window.factor = ENERGY_CONVERTER['GWh_ktoe']; } 
                if(unit == 'GWh' && value == 'Mtoe'){ window.factor = ENERGY_CONVERTER['GWh_Mtoe']; }
                if(unit == 'GWh' &&  value == 'GWh'){ window.factor = ENERGY_CONVERTER['GWh_GWh']; }
                if(unit == 'GWh' &&  value == 'PJ'){ window.factor = ENERGY_CONVERTER['GWh_PJ']; }
                $('#jqxgrid_elGen_t').jqxGrid('updatebounddata', 'cells');
            } 
        });

        ///////////////////////////////////////////////////resiying columns
        let resIC = true;
        $("#rcIC").click(function () {
            if(resIC){
                $('#jqxGrid_installed').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxGrid_installed').jqxGrid('autoresizecolumns');
            }
            resIC = !resIC;        
        });
        let resAC = true;
        $("#rcAC").click(function () {
            if(resAC){
                $('#jqxgrid_power_output_trans').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgrid_power_output_trans').jqxGrid('autoresizecolumns');
            }
            resAC = !resAC;        
        });
        let resRC = true;
        $("#rcRC").click(function () {
            if(resRC){
                $('#jqxgridRC').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgridRC').jqxGrid('autoresizecolumns');
            }
            resRC = !resRC;        
        });
        let resTIC = true;
        $("#rcTIC").click(function () {
            if(resTIC){
                $('#jqxgrid_TIC_output_trans').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgrid_TIC_output_trans').jqxGrid('autoresizecolumns');
            }
            resTIC = !resTIC;        
        });
        let resINV = true;
        $("#rcINV").click(function () {
            if(resINV){
                $('#jqxgrid_investment_trans').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgrid_investment_trans').jqxGrid('autoresizecolumns');
            }
            resINV = !resINV;        
        });
        let resEG = true;
        $("#rcEG").click(function () {
            if(resEG){
                $('#jqxgrid_elGen_t').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxgrid_elGen_t').jqxGrid('autoresizecolumns');
            }
            resEG = !resEG;        
        });
        let resLCOE = true;
        $("#rcLCOE").click(function () {
            if(resLCOE){
                $('#jqxGrid_LCOE').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxGrid_LCOE').jqxGrid('autoresizecolumns');
            }
            resLCOE = !resLCOE;        
        });
        let resAVGUC = true;
        $("#rcAVGUC").click(function () {
            if(resAVGUC){
                $('#jqxGrid_AVGUC').jqxGrid('autoresizecolumn', 'name');
            }
            else{
                $('#jqxGrid_AVGUC').jqxGrid('autoresizecolumns');
            }
            resAVGUC = !resAVGUC;        
        });


    }else{
            $( "#ace-settings-btn" ).trigger( "click" );
            $('#loadermain').hide();
        }
           
});
</script>










	
