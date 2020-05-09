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
        <small class="hidden-xs hidden-sm"><?php echo $final_energy_demand; ?>   <i class="ace-icon fa fa-angle-double-right"></i></small>
        <small class="hidden-xs hidden-sm"><?php echo $fuel_shares . ' [%]' ;?>  <i class="ace-icon fa fa-angle-double-right"></i></small>
        <small class="hidden-xs hidden-sm"><b><?php echo $_SESSION['sector'];?></b></small>
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
                <h4 class="widget-title"><?php echo  $final_energy_demand .' '. $fuel_shares; ?></h4>
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
                    <div>
                        <div id='jqxChart' style="width:100%; height:280px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="widget-box">
    	<div class="widget-header">
    		<h4 class="widget-title"><?php echo $final_energy_demand.' '. 'table view'; ?></h4>
    		<div class="widget-toolbar">
                <a href="#" data-action="collapse">
                    <i class="ace-icon fa fa-chevron-up "></i>
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
<?php
if (isset($_SESSION['case'])){
?>

<script type="text/javascript" src="scripts/generateColors.js"></script>
<script src="classes/js/Chart.Class.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {
    access();
    session = getSession();
    var casename = session.case;  
    var user = session.us;  
    if (casename != '') {
        var theme = 'bootstrap';

        let xmlDoc = getXML(casename, user);
        var years = getYears(xmlDoc);
        let fuels = getFuels(xmlDoc);

        let series_fuels = getFuelSeries(fuels);
        let $div = $("#jqxgrid_trans");
        let columns_years_editable = getColYr_e($div, years, 'p', '[%]');
        //let columns_years_editable = getYearsEditableColumns2(years);

        var sector = getUrlVars()["sector"];
        var myURL ='data/FED_fuelshares_data.php?sector='+sector;
        var myURL3 ='data/FED_fuelshares_data.php?sector='+sector+'&trans=0';
        var source = {
            url: myURL3,
            root: 'data',
            datatype: 'json',
            cache: false,
            datafields: [
                { name: 'year', type: 'string' },
        		{ name: 'Electricity', type: 'number' },
        		{ name: 'Coal', type: 'number' },
        		{ name: 'Oil', type: 'number' },
        		{ name: 'Gas', type: 'number' },
                { name: 'Biofuels', type: 'number' },
        		{ name: 'Heat', type: 'number' },
                { name: 'Peat', type: 'number' },
                { name: 'Waste', type: 'number' },
                { name: 'OilShale', type: 'number' },
        	]
        };   
        var dataAdapter = new $.jqx.dataAdapter(source);             
        let chartSettings = getChartSettings(title='', description='', dataAdapter, series_fuels, '%');
        $('#jqxChart').jqxChart(chartSettings);
        var chart1 = $('#jqxChart').jqxChart('getInstance');

        //drugi data adapter za transponirane vrijednosti, po godinama
        var myURL2 ='data/FED_fuelshares_data.php?sector='+sector+'&trans=1';
        var sourcetrans = {
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

        $("#jqxgrid_trans").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter2,
            editable: true,
            showstatusbar: true,
            showaggregates: true,
            selectionmode: 'multiplecellsadvanced',
            showstatusbar: true,
            columnsresize:true,
            columns: columns_years_editable,     
        }); 
                
                //update za transpponirani grid
        $("#jqxgrid_trans").on('cellvaluechanged', function (event) {
            var args = event.args;
            var godina = args.datafield;
            var fuel = args.rowindex;
            //var fuel1 = $("#jqxgrid_trans").jqxGrid("getcellvalue", fuel, "name"); 
            var value = args.value;
            var oldvalue = args.oldvalue;
            rowdata = new Object();
            rowdata['action'] = 'saveData';
            rowdata['fuel'] = fuel;
            rowdata['year'] = godina;
            rowdata['value'] = value;
            if (value>=0){
                var sum=0;
                var sumShares = $("#jqxgrid_trans").jqxGrid('getcolumnaggregateddata', godina, ['sum']);
                valid = sumShares.sum - oldvalue + value;
                var add = 100-valid; 
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
                                            dataAdapter.dataBind();     
                                           // dataAdapter2.dataBind();
                                            //alert(sum_is_less_than_100+add +to_some_fuel);
                                            break;
                                }  
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(errorThrown);
                                ShowErrorMessage(errorThrown);
                            }
                        
                        });        
	        
            }
            else{
                ShowErrorMessage(' Value of fuel shares must be positive number!');
            }
            }); 

        $("#showLog").click(function (e) {
            e.preventDefault();
            $('#log').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.FES.title}</h4>
                    ${DEF.FES.definition}
                </div>
            `);
            $('#log').toggle('slow');
        });
        // $("#resizeColumns").click(function () {
        //     $('#jqxgrid_trans').jqxGrid('autoresizecolumns');
        // });
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
            $("#jqxgrid_trans").jqxGrid('exportdata', 'xls', 'Final energy demand fuel shares');
        });
        $("#decUp").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d++;
            window.decimal = 'd' + parseInt(window.d);
            $('#jqxgrid_trans').jqxGrid('refresh');
            $('#jqxgrid_trans').jqxGrid('refreshaggregates');
        });

        $("#decDown").on('click', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            window.d--;
            window.decimal = 'd' + parseInt(window.d);
            $('#jqxgrid_trans').jqxGrid('refresh');
            $('#jqxgrid_trans').jqxGrid('refreshaggregates');
            //$('#jqxgrid_trans').jqxGrid('updatebounddata', 'cells');
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

        $("#pngFs").click(function() {
            $("#jqxChart").jqxChart('saveAsPNG', 'Technology shares.png',  getExportServer());
        }); 
    } 
    $('#loadermain').hide(); 
   
});
</script>

<?php
}
?>
<script>$('#loadermain').hide();</script>
    
