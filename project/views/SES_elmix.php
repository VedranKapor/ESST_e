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
        <small class="hidden-xs hidden-sm"><?php echo $secondary_energy_supplies; ?>   <i class="ace-icon fa fa-angle-double-right"></i></small>
        <small class="hidden-xs hidden-sm"><?php echo $el_gen;?></i></small>
    </h4>
</div>  

<?php
if (isset($_SESSION['case']))
{
?>
<div class="tabbable">
	<ul class="nav nav-tabs" id="myTab">
		<li class="active">
			<a data-toggle="tab" href="#elmix">
				<i class="green ace-icon fa fa-home bigger-120"></i>
				Electricity mix
			</a>
		</li>
		<li>
			<a data-toggle="tab" href="#eff">
                <?php echo $technology_efficiency; ?>
				<span class="badge badge-warning">%</span>
			</a>
		</li>
        <li>
			<a data-toggle="tab" href="#resCap">
                <?php echo $reserve_capacity?>
				<span class="badge badge-warning">%</span>
			</a>
        </li>
        <li>
			<a data-toggle="tab" href="#carbon">
                Carbon cost
				<span class="badge badge-success"><i class="fa fa-money" aria-hidden="true"></i></span>
			</a>
		</li>
        <li>
			<a data-toggle="tab" href="#discount">
                Discount rate
				<span class="badge badge-info">%</span>
			</a>
		</li>
    </ul>
    

	<div class="tab-content">


		<div id="elmix" class="tab-pane fade in active">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="log_elmix" style="display:none">
                    </div>
                </div>
            </div>
             <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $fuel_shares_for_el_gen . ' ' . $by_fuels; ?></h4>
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
                                <a href="#" data-action="mix"  id="pngSesmix"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
                                <a href="#" data-action="mix" id="stackedChart" data-chartType="stackedChart" id-chart="1" class="switchChart green tooltip-info" data-toggle="tooltip" data-placement="top" title="Stacked Column Chart">
                                    <i class="ace-icon fa fa-bars"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id-chart="1"  class="toggleLabels esst tooltip-info" data-toggle="tooltip" data-placement="top" title="Labels on/off">
                                <i class="fa fa-tags" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div >
                                    <div id='jqxChart' style="width:100%; height:280px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    
            </div>
            <div class="widget-box">
            	<div class="widget-header">
                    <h4 class="widget-title"><?php echo $fuel_shares_for_el_gen.' '. 'table view'; ?></h4>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-up "></i>
                        </a>
                    </div>
                    <div class="widget-toolbar">
                        <a href="#" data-action="mix"  id="xlsElMix"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export data to excel">
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
                        <!-- <div class="row"> -->
                            <div id="jqxgrid_trans" ></div>
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>


		<div id="eff" class="tab-pane fade">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="log_eff" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $technology_efficiency . ' [%]'; ?></td></h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="showLog_eff"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="resizeColumns_eff" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <!-- <div class="row">
                                    <div class="col-md-12"> -->
                                        <div id="jqxgrid_Eff" ></div>
                                    <!-- </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>

        
		<div id="resCap" class="tab-pane fade">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="log_rc" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title"><?php echo $reserve_capacity . '[ %]'; ?></td></h4>

                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="showLog_rc"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="resizeColumns_rc" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="jqxgrid_resCapTotal"></div>
                                <div id="jqxgrid_resCap"></div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="carbon" class="tab-pane fade">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="log_cc" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">Carbon content cost</td></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="showLog_cc"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="resizeColumns_cc" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="jqxgridCarbon"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="discount" class="tab-pane fade">
            <div id="row">
                <div class="col-lg-12">
                    <div  id="log_dr" style="display:none">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">Discount rate</td></h4>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="showLog_dr"  class=" tooltip-info"  data-toggle="tooltip" data-placement="top" title="Definiton">
                                    <i class="fa fa-question-circle-o warning" aria-hidden="true"></i>
                                </a>
                            </div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="mix" id="resizeColumns_dr" class=" tooltip-info" data-toggle="tooltip" data-placement="top" title="Resize columns">
                                    <i class="fa fa-arrows-h nest" aria-hidden="true"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div id="jqxGridDiscountRate"></div>
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
<script type="text/javascript">

$(document).ready(function () {
    session = getSession();
var casename = session.case;  
var user = session.us;  
if (typeof(casename) != 'undefined')
   {
        let xmlDoc = getXML(casename, user);
        var unit = getUnit(xmlDoc);
        var currency = getCurrency(xmlDoc);
        var years = getYears(xmlDoc);
        let fuels = getFuels(xmlDoc);
        var elmix_fuels = getElMixFuels(xmlDoc);

        let series_fuels = getFuelSeries(fuels);
        let series_years = getYearsSeries(years);
        let series_elmix_fuels =  getElMixFuelSeries(elmix_fuels);
        // let yearsInd = getYearsIndexes(years);
        // let brojKolona = yearsInd.length;
        //let columns_years_editable = getYearsEditableColumns2(years);
       

        let $div = $("#jqxgrid_trans");
        let columns_years_editable = getColYr_e($div, years, 'p', '[%]');

        let $div1 = $("#jqxgridCarbon");
        //let columns_years_editable_carbon = getYearsEditableColumns(years, currency + "/ton");
        let columns_years_editable_carbon = getColYr_e_simple( years, currency + "/ton");

        //definisi prvi data adapter za grid i chart po sektorima
        var theme = 'bootstrap';
     

        var myURL ='data/SES_elmix_data.php';

        var myURL3 ='data/SES_elmix_data.php?trans=0';
        var source =
        {
            url: myURL3,
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
        	// 	{ name: 'Peat', type: 'number' },
            //     { name: 'Waste', type: 'number' },
            //     { name: 'OilShale', type: 'number' },
            //     { name: 'Solar', type: 'number' },
            //     { name: 'Wind', type: 'number' },
            //     { name: 'Geothermal', type: 'number' },
            //     { name: 'Nuclear', type: 'number' },
            //     { name: 'ImportExport', type: 'number' },
        	// ]
        };   

        var dataAdapter = new $.jqx.dataAdapter(source);                                         


        let chartSettings = getChartSettings(title='', description='', dataAdapter, series_elmix_fuels, '%');
        $('#jqxChart').jqxChart(chartSettings);
        var chart1 = $('#jqxChart').jqxChart('getInstance');

        //drugi data adapter za transponirane vrijednosti, po godinama
        var myURL2 ='data/SES_elmix_data.php?trans=1';
        var sourcetrans =
        {
            url: myURL2,
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
           var dataAdapter2 = new $.jqx.dataAdapter(sourcetrans);  
       //setting i inicijalizacija za transponirani grid po godinamma
        $("#jqxgrid_trans").jqxGrid({
                autoheight: true,
                autorowheight: true,
                width: '100%',
                theme: theme,
                source: dataAdapter2,
                editable: true,
                 showstatusbar: true,
                showaggregates: true,
                columnsresize:true,
                selectionmode: 'multiplecellsadvanced',
                columns: columns_years_editable
        }); //kraj inicijalizacije i settinga za trans grid

            $("#jqxgrid_trans").on('cellvaluechanged', function (event) {
                //$('#loadermain').show();
                var args = event.args;
                var godina = args.datafield;
                var fuel = args.rowindex;
                var value = args.value;
                var oldvalue = args.oldvalue;
                var fuelname = $('#jqxgrid_trans').jqxGrid('getcellvalue', fuel, "name");
                if(fuelname == 'Import/Export') { fuelname = 'ImportExport';}
                if(fuelname == 'Oil shale') { fuelname = 'OilShale';}
                rowdata = new Object();
                rowdata['action'] = 'saveData';
                //rowdata['fuel'] = fuel;
                rowdata['fuel'] = fuelname;
                rowdata['year'] = godina;
                rowdata['value'] = value;

                //if (value>=0 || fuelname == 'Import/Export'){

                if (value>=0  || fuelname == 'ImportExport' ){
                    var sum=0;
                    var sumShares = $("#jqxgrid_trans").jqxGrid('getcolumnaggregateddata', godina, ['sum']);
                    valid = sumShares.sum - oldvalue + value;
                    var add = 100-valid; 
                            $.ajax({
                            url: myURL,
                            dataType: 'json',
                            type: 'POST',
                            data: rowdata, 
                            complete: function(e) {
                                $('#loadermain').hide(); 
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
                                        dataAdapter.dataBind();     
                                        ///dataAdapter2.dataBind();
                                         localStorage.setItem("P1",  "changed");
                                        break;
                                    }  
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    //console.log(errorThrown);
                                    ShowErrorMessage(errorThrown);
                                }
                            
                            }); //krj ajaxa       
                    }
                    else{
                        //$('#loadermain').hide();
                        ShowErrorMessage("Fuel share for " + fuelname + " in " + godina + " is less then zero!");
                    }
            }); 

            $("#xlsElMix").click(function (e) {
                e.preventDefault();
                $("#jqxgrid_trans").jqxGrid('exportdata', 'xls', 'Secondary Energy Supply electricity mix');
            });

            $("#pngSesmix").click(function() {
                $("#jqxChart").jqxChart('saveAsPNG', 'Electricity mix.png',  getExportServer());
            }); 

            $("#showLog").click(function (e) {
                e.preventDefault();
                $('#log_elmix').html(`
                    <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                        <h4>${DEF.ELMIX.title}</h4>
                        ${DEF.ELMIX.definition}
                    </div>
                `);
                $('#log_elmix').toggle('slow');
            });

        $(".switchChart").on('click', function (e) {
            e.preventDefault();
            var chartType = $(this).attr('data-chartType');
            $('.widget-toolbar a').switchClass( "green", "grey" );
            $('#'+chartType).switchClass( "grey", "green" );
            chart1.seriesGroups[0].type = CHART_TYPE[chartType];
            if(chartType == 'barChart'){
                chart1.seriesGroups[0].labels.angle = 90;
            }else{
                chart1.seriesGroups[0].labels.angle = 0;
            }
            chart1.update();    
        });
        $(".toggleLabels").on('click', function (e) {
            e.preventDefault();
            chart1.seriesGroups[0].labels.visible = !chart1.seriesGroups[0].labels.visible;
            chart1.update();    
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
        $("#decUp").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d++;
            //decimal = 'd' + parseInt(d);
            //console.log(window.d);
            window.decimal = 'd' + parseInt(window.d);
            $('#jqxgrid_trans').jqxGrid('updatebounddata', 'cells');
        });
        $("#decDown").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d--;
            //decimal = 'd' + parseInt(d);
            window.decimal = 'd' + parseInt(window.d);
            $('#jqxgrid_trans').jqxGrid('updatebounddata', 'cells');
        });


    /////////////////////////////////////////////////////////////////////////////////////efficiency
    var myURL2 ='data/SES_elmix_data.php?action=efficiency';
    var source_eff =
    {
        url: myURL2,
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
    var daEff = new $.jqx.dataAdapter(source_eff);   

    $("#jqxgrid_Eff").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daEff,
            editable: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            //columns: columns_years_editable1 
            columns: columns_years_editable
    });

    $("#jqxgrid_Eff").on('cellvaluechanged', function (event) {
        $('#loadermain').show();
        var args = event.args;
        var godina = args.datafield;
        var fuel = args.rowindex;
        var value = args.value;
        var fuelname = $('#jqxgrid_Eff').jqxGrid('getcellvalue', fuel, "name");
        if(fuelname == 'Oil shale') { fuelname = 'OilShale';}
        rowdata = new Object();
        rowdata['action'] = 'saveEff';
        //rowdata['fuel'] = fuel;
        rowdata['fuel'] = fuelname;
        rowdata['year'] = godina;
        rowdata['value'] = value;
       
        var fuelname = $('#jqxgrid_Eff').jqxGrid('getcellvalue', fuel, "name");
        if (value>=0 || fuelname == 'Import/Export'){
            $.ajax({
                url: myURL,
                dataType: 'json',
                type: 'POST',
                data: rowdata, 
                complete: function(e) {
                    $('#loadermain').hide(); 
                    var serverResponce = e.responseJSON;
                    switch (serverResponce["type"]) {
                        case 'ERROR':
                            ShowErrorMessage(serverResponce["msg"]);
                            break;
                        case 'SUCCESS':
                            $('#jqxNotification').jqxNotification('closeLast'); 
                            ShowInfoMessage(serverResponce["msg"]);
                            localStorage.setItem("P1",  "changed");
                            break;
                        }  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        ShowErrorMessage(errorThrown);
                    }
                }); //krj ajaxa       
            }
            else{
                $('#loadermain').hide();
                ShowErrorMessage("Technology efficiency for " + fuelname + " in " + godina + " is less then zero!");
            }
    }); 
    $("#showLog_eff").click(function (e) {
        e.preventDefault();
        $('#log_eff').html(`
            <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                <h4>${DEF.EFF.title}</h4>
                ${DEF.EFF.definition}
            </div>
        `);
        $('#log_eff').toggle('slow');
    });


    let resEff = true;
    $("#resizeColumns_eff").click(function () {
        if(resEff){
            $('#jqxgrid_Eff').jqxGrid('autoresizecolumn', 'name');
        }
        else{
            $('#jqxgrid_Eff').jqxGrid('autoresizecolumns');
        }
        resEff = !resEff;        
    });


    //resrve Capacity
    var myURL2 ='data/SES_elmix_data.php?action=reserveCapacity';
    var source_resCap =
    {
        url: myURL2,
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
    var daResCap = new $.jqx.dataAdapter(source_resCap);   

    $("#jqxgrid_resCap").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daResCap,
            editable: true,
            showstatusbar: true,
            showaggregates: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_editable 
    });

    $("#jqxgrid_resCap").on('cellvaluechanged', function (event) {
        $('#loadermain').show();
        var args = event.args;
        var godina = args.datafield;
        var fuel = args.rowindex;
        var value = args.value;
        //console.log(godina, fuel, value);
        var fuelname = $('#jqxgrid_resCap').jqxGrid('getcellvalue', fuel, "name");
        rowdata = new Object();
        rowdata['action'] = 'saveResCap';
        rowdata['fuel'] = fuelname;
        rowdata['year'] = godina;
        rowdata['value'] = value;
        
        if (value>=0 || fuelname == 'Import/Export'){
            $.ajax({
                url: myURL,
                dataType: 'json',
                type: 'POST',
                data: rowdata, 
                complete: function(e) {
                    $('#loadermain').hide(); 
                    var serverResponce = e.responseJSON;
                    switch (serverResponce["type"]) {
                        case 'ERROR':
                            ShowErrorMessage(serverResponce["msg"]);
                            break;
                        case 'SUCCESS':
                            $('#jqxNotification').jqxNotification('closeLast'); 
                            ShowInfoMessage(serverResponce["msg"]);
                            localStorage.setItem("P1",  "changed");
                            break;
                        }  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        ShowErrorMessage(errorThrown);
                    }
                }); //krj ajaxa       
            }
            else{
                $('#loadermain').hide();
                ShowErrorMessage("Technology efficiency for " + fuelname + " in " + godina + " is less then zero!");
            }
    }); 

    //resrve Capacity Total
    var myURL2 ='data/SES_elmix_data.php?action=reserveCapacityTotal';
    var source_ResCapTotal =
    {
        url: myURL2,
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
    var daResCapTotal = new $.jqx.dataAdapter(source_ResCapTotal);   

    $("#jqxgrid_resCapTotal").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daResCapTotal,
            editable: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_editable 
    });

    $("#jqxgrid_resCapTotal").on('cellvaluechanged', function (event) {
        $('#loadermain').show();
        var args = event.args;
        var godina = args.datafield;
        var fuel = args.rowindex;
        var value = args.value;
        rowdata = new Object();
        rowdata['action'] = 'saveResCapTotal';
        rowdata['year'] = godina;
        rowdata['value'] = value;
        if (value >=0 || fuelname == 'Import/Export'){
            $.ajax({
                url: myURL,
                dataType: 'json',
                type: 'POST',
                data: rowdata, 
                complete: function(e) {
                    $('#loadermain').hide(); 
                    var serverResponce = e.responseJSON;
                    switch (serverResponce["type"]) {
                        case 'ERROR':
                            ShowErrorMessage(serverResponce["msg"]);
                            break;
                        case 'SUCCESS':
                            $('#jqxNotification').jqxNotification('closeLast'); 
                            ShowInfoMessage(serverResponce["msg"]);
                            break;
                        }  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        ShowErrorMessage(errorThrown);
                    }
                }); //krj ajaxa       
            }
            else{
                $('#loadermain').hide();
                ShowErrorMessage("Technology efficiency for " + fuelname + " in " + godina + " is less then zero!");
            }
    }); 


    $("#showLog_rc").click(function (e) {
        e.preventDefault();
        $('#log_rc').html(`
            <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                <h4>${DEF.RC.title}</h4>
                ${DEF.RC.definition}
            </div>
        `);
        $('#log_rc').toggle('slow');
    });



    let resRc = true;
    $("#resizeColumns_rc").click(function () {
        if(resRc){
            $('#jqxgrid_resCap').jqxGrid('autoresizecolumn', 'name');
            $('#jqxgrid_resCapTotal').jqxGrid('autoresizecolumn', 'name');
        }
        else{
            $('#jqxgrid_resCap').jqxGrid('autoresizecolumns');
            $('#jqxgrid_resCapTotal').jqxGrid('autoresizecolumns');
        }
        resRc = !resRc;        
    });



            /////////////////////////////////////////////////////////////////////CARBON CONTENT///////////////////////////////////////////////////////////////////
        

    var myURL2 ='data/SES_elmix_data.php?action=carbonCost';
    var source_CarbonContent =
    {
        url: myURL2,
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
    var daCarbonContent = new $.jqx.dataAdapter(source_CarbonContent);   

    $("#jqxgridCarbon").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daCarbonContent,
            editable: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_editable_carbon 
    });

    $("#jqxgridCarbon").on('cellvaluechanged', function (event) {
        $('#loadermain').show();
        var args = event.args;
        var godina = args.datafield;
        var value = args.value;

        //console.log(args);
        rowdata = new Object();
        rowdata['action'] = 'saveCarbonCosts';
        rowdata['year'] = godina;
        rowdata['value'] = value;
        
        if (value >=0 ){
            $.ajax({
                url: myURL,
                dataType: 'json',
                type: 'POST',
                data: rowdata, 
                complete: function(e) {
                    $('#loadermain').hide(); 
                    var serverResponce = e.responseJSON;
                    console.log(serverResponce);
                    switch (serverResponce["type"]) {
                        case 'ERROR':
                            ShowErrorMessage(serverResponce["msg"]);
                            break;
                        case 'SUCCESS':
                            $('#jqxNotification').jqxNotification('closeLast'); 
                            ShowInfoMessage(serverResponce["msg"]);
                            localStorage.setItem("P1",  "changed");
                            break;
                        }  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        ShowErrorMessage(errorThrown);
                    }
                }); //krj ajaxa       
            }
            else{
                $('#loadermain').hide();
                ShowErrorMessage("Carbon cost in " + godina + " is less then zero!");
            }
    }); 
    $("#showLog_cc").click(function (e) {
        e.preventDefault();
        $('#log_cc').html(`
            <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                <h4>${DEF.CC.title}</h4>
                ${DEF.CC.definition}
            </div>
        `);
        $('#log_cc').toggle('slow');
    });


    let resCc = true;
    $("#resizeColumns_cc").click(function () {
        if(resCc){
            $('#jqxgridCarbon').jqxGrid('autoresizecolumn', 'name');
        }
        else{
            $('#jqxgridCarbon').jqxGrid('autoresizecolumns');
        }
        resCc = !resCc;        
    });

                /////////////////////////////////////////////////////////////////////DISCOUNT RATE//////////////////////////////////////////////////////////////////
        
    //resrve Capacity Total
    var source_DiscountRate =
    {
        url: 'data/SES_elmix_data.php?action=discountRate',
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
    var daDiscountRate = new $.jqx.dataAdapter(source_DiscountRate);   

    $("#jqxGridDiscountRate").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: daDiscountRate,
            editable: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns_years_editable 
    });

    $("#jqxGridDiscountRate").on('cellvaluechanged', function (event) {
        $('#loadermain').show();
        var args = event.args;
        var godina = args.datafield;
        var value = args.value;

        //console.log(args);
        rowdata = new Object();
        rowdata['action'] = 'saveDiscountRate';
        rowdata['year'] = godina;
        rowdata['value'] = value;
        
        if (value >=0 ){
            $.ajax({
                url: myURL,
                dataType: 'json',
                type: 'POST',
                data: rowdata, 
                complete: function(e) {
                    $('#loadermain').hide(); 
                    var serverResponce = e.responseJSON;
                    switch (serverResponce["type"]) {
                        case 'ERROR':
                            ShowErrorMessage(serverResponce["msg"]);
                            break;
                        case 'SUCCESS':
                            $('#jqxNotification').jqxNotification('closeLast'); 
                            ShowInfoMessage(serverResponce["msg"]);
                            localStorage.setItem("P1",  "changed");
                            break;
                        }  
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        ShowErrorMessage(errorThrown);
                    }
                }); //krj ajaxa       
            }
            else{
                $('#loadermain').hide();
                ShowErrorMessage("Discount rate in " + godina + " is less then zero!");
            }
    }); 

    $("#showLog_dr").click(function (e) {
        e.preventDefault();
        $('#log_dr').html(`
            <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                <h4>${DEF.DR.title}</h4>
                ${DEF.DR.definition}
            </div>
        `);
        $('#log_dr').toggle('slow');
    });


    let resDr = true;
    $("#resizeColumns_dr").click(function () {
        if(resDr){
            $('#jqxGridDiscountRate').jqxGrid('autoresizecolumn', 'name');
        }
        else{
            $('#jqxGridDiscountRate').jqxGrid('autoresizecolumns');
        }
        resDr = !resDr;        
    });
    $('#loadermain').hide();  

    }else{
            $( "#ace-settings-btn" ).trigger( "click" );
            $('#loadermain').hide();
        }
});
</script>
