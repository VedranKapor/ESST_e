<?php
require "../config.php";
require '../classes/EsstCase.php';
require '../lang/en.php';



if (isset($_SESSION['case'])){
    $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
    if(file_exists($filepath)){
        $esstCase = new EsstCase($_SESSION['case']);
        $unit = $esstCase->getUnit();
    }
}
?>
<div class="page-header">
    <h4>
        <?php echo $case_name.': '; 
        if(isset($_SESSION['case'])){
            echo "<span style='color:#A80000 ;'>" .$_SESSION['case']."</span><br>";
                        echo '<small>'.$total_energy_supplies.'<i class="ace-icon fa fa-angle-double-right"></i></small>';
            echo '<small class="hidden-xs hidden-sm">'.$el_gen .'</i></small>';
        }
        else {
            echo "<span style='color:#A80000 ;'>Please select a case!</span><br>";
                        echo '<small>'.$total_energy_supplies.'<i class="ace-icon fa fa-angle-double-right"></i></small>';
            echo '<small class="hidden-xs hidden-sm">'.$el_gen .'</i></small>';
        }?>

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
                <h4 class="widget-title"><?php echo $el_gen_from_each_fuel ?></h4>
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
            <h4 class="widget-title"><?php echo $el_gen_from_each_fuel.' '. 'table view'; ?></h4>

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
            <div class="widget-toolbar ">
                <div class="form-control input-sm" style="margin-top:4px" id='ddlUnits' ></div> 
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


<script src="classes/js/Chart.Class.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function () {
    access();
    session = getSession();
    var casename = session.case;  
    var user = session.us;  
    if (typeof(casename) != 'undefined')
    {
            
        //definisi prvi data adapter za grid i chart po sektorima
        var theme = 'bootstrap';
        let xmlDoc = getXML(casename, user);
        var unit = getUnit(xmlDoc);
        var years = getYears(xmlDoc);
        var elmix_fuels = getElMixFuels(xmlDoc);

        let yearsInd = getYearsIndexes(years);
        let brojKolona = yearsInd.length;

        let series_years = getYearsSeries(years);
        let series_elmix_fuels =  getElMixFuelSeries(elmix_fuels);

        //
        let $div = $("#jqxgrid_trans");
        let years_columns = getColYr_e($div, years, 'd', '');
       // let years_columns = getYearsCoulmns(years, '', brojKolona);

        var myURL ='data/SES_elmix_values_data.php';
        var source =
        {
            url: myURL,
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
                { name: 'ImportExport', type: 'number' },
                
        		]
        };   
        var dataAdapter = new $.jqx.dataAdapter(source);     

        let chartSettings = getChartSettings(title='', description='', dataAdapter, series_elmix_fuels, unit);
        $('#jqxChart').jqxChart(chartSettings);
        var chart1 = $('#jqxChart').jqxChart('getInstance');  
       
            
        //drugi data adapter za transponirane vrijednosti, po godinama
        var myURL2 ='data/SES_elmix_values_data.php?trans=1';
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

        $("#jqxgrid_trans").jqxGrid(
            {
                autoheight: true,
                autorowheight: true,
                width: '100%',
                theme: theme,
                source: dataAdapter2,
                editable: false,
                showstatusbar: true,
                showaggregates: true,
                columnsresize:true,
                //disabled: true,
                selectionmode: 'multiplecellsadvanced',
                columns: years_columns                  
                }); 
                
    var srcUnits = [
        "ktoe",
        "Mtoe",
        "PJ",
        "GWh"
    ]

    $("#ddlUnits").jqxDropDownList({ 
        source: srcUnits, 
        theme: theme,  
        height: 16,
        width:65,
        autoDropDownHeight: true
    });
    $("#ddlUnits").jqxDropDownList('selectItem', unit ); 

    $('#ddlUnits').on('change', function (event)
    {     
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

            $('#jqxgrid_trans').jqxGrid('refresh');
            $('#jqxgrid_trans').jqxGrid('refreshaggregates');

        } 
    });
        $("#showLog").click(function (e) {
            e.preventDefault();
            $('#log').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.SESV.title}</h4>
                    ${DEF.SESV.definition}
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

        $("#pngFs").click(function(e) {
            e.preventDefault();
            $("#jqxChart").jqxChart('saveAsPNG', 'Secondary energy supplies.png',  getExportServer());
        }); 
   // var jedinica = $("#ddlUnits").jqxDropDownList('getSelectedItem').value;     
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
            $("#jqxgrid_trans").jqxGrid('exportdata', 'xls', 'Electricity supply structure');
        });


    $('#loadermain').hide();   

 
    }else{
            $( "#ace-settings-btn" ).trigger( "click" );
            $('#loadermain').hide();
        }        
});
</script>
