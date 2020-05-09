<?php
// session_start();
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
            }
        ?> 
        </p>
        <small class="hidden-xs hidden-sm" lang="en">Final energy consumption </small>
        <small class="hidden-xs hidden-sm"><i class="ace-icon fa fa-angle-double-right"></i></small>
        <small class="hidden-xs hidden-sm"  lang="en"><?php echo $by_sectors;?></small>
    </h4>
</div>    

<?php
if (isset($_SESSION['case'])){
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
                <h4 class="widget-title" lang="en">Final energy consumption by energy consumers</h4>
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
                    <a href="#" data-action="mix"  id="pngFed"  class="grey tooltip-info"  data-toggle="tooltip" data-placement="top" title="Export chart as png">
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
    		<h4 class="widget-title" lang="en">Final energy consumption by energy consumers [table view]</h4>
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
                        <div id="grid" ></div>
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
let session = getSession();
var casename = session.case;  
var user = session.us;  
if (typeof(casename) != 'undefined'){
    let xmlDoc = getXML(casename, user);
    unit = getUnit(xmlDoc);
    var theme = 'bootstrap';

    var years = getYears(xmlDoc);
    var sectors = getSectors(xmlDoc);
    let series_sectors = getSectorSeries(sectors);

    let $div = $('#grid');
    //let columns_years_editable1 = getYearsEditableColumns(years, unit);
    let columns_years_editable1 = getColYr_e($div, years, 'd', unit);
    //console.log(columns_years_editable1);

    var myURL ='data/FED_bysectors_data.php'; 
    var myURL3 ='data/FED_bysectors_data.php?trans=0'; 

    var source = {
        url: myURL3,
        root: 'data',
        datatype: 'json',
        cache: false,
        //datafields: sectors_final
        autoBind: true
    };   

    var dataAdapter = new $.jqx.dataAdapter(source);          

    let chartSettings = getChartSettings(title='', description='', dataAdapter, series_sectors, unit);
    $('#jqxChart').jqxChart(chartSettings);
    var chart1 = $('#jqxChart').jqxChart('getInstance');

      
    var myURL2 ='data/FED_bysectors_data.php?trans=1';
    var sourcetrans =
    {
        url: myURL2,
        root: 'data',
        datatype: 'json',
        cache: false,
        autoBind: true,
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
            {name: "2050", type: "string"}            ]
	}; 
    var dataAdapter2 = new $.jqx.dataAdapter(sourcetrans);                   
          
    $("#grid").jqxGrid({
        autoheight: true,
        autorowheight: true,
        width: '100%',
        theme: theme,
        source: dataAdapter2,
        editable: true,
        showstatusbar: true,
        showaggregates: true,
        columnsresize:true,
        columnsautoresize: true,
        selectionmode: 'multiplecellsadvanced',
        columns: columns_years_editable1,                  
    }); 

    //$('#grid').jqxGrid('autoresizecolumn', 'name'); 

    


    // let cellvaluechanged = function (paste, event) {
    //     console.log(event, paste);
    //     var args = event.args;
    //     var godina = args.datafield;
    //     var sektor = args.rowindex;
    //     //var sektor1 = $("#grid").jqxGrid("getcellvalue", sektor, "name"); 
    //     var value = args.value;
    //     rowdata = new Object();
    //     rowdata['action'] = 'saveData';
    //     rowdata['sector'] = sektor;
    //     rowdata['year'] = godina;
    //     rowdata['value'] = value;
    //     if(value >= 0 ){
    //         $.ajax({
    //             url: myURL,
    //             dataType: 'json',
    //             type: 'POST',
    //             async: true,
    //             data: rowdata, 
    //             complete: function(e) {
    //                 var serverResponce = e.responseJSON;
    //                     switch (serverResponce["type"]) {
    //                         case 'ERROR':
    //                             ShowErrorMessage(serverResponce["msg"]);
    //                             break;
    //                         case 'EXIST':
    //                             ShowWarningMessage(serverResponce["msg"]);
    //                             break;
    //                         case 'SUCCESS':
    //                             $('#jqxNotification').jqxNotification('closeAll'); 
    //                             ShowInfoMessage(serverResponce["msg"]);
    //                             localStorage.setItem("P1",  "changed");
    //                             if(!paste){
    //                                 dataAdapter.dataBind(); 
    //                             }
                                    
    //                             //dataAdapter2.dataBind();
    //                             //$('#loadermain').hide();
    //                             break;
    //                 }  
    //             },
    //             error: function(jqXHR, textStatus, errorThrown) {
    //                 console.log(errorThrown);
    //                 ShowErrorMessage(errorThrown);
    //             }
    //         });  
    //     }      
    //     else{
    //          ShowErrorMessage("Final energy conumption share for " + sektor + " in " + godina + " is less then zero!");
    //     } 
    // }
    // let paste = false
    // $("#grid").on('cellvaluechanged', cellvaluechanged.bind(this, paste));

    
    // $("#grid").bind('keydown', function (event) {
    //     var ctrlDown = false, ctrlKey = 17, cmdKey = 91, vKey = 86, cKey = 67;
    //     var key = event.charCode ? event.charCode : event.keyCode ? event.keyCode : 0;
    //     if (key == vKey) {
    //         //$("#else-grid-json").off('cellvaluechanged');
    //         paste = true;
    //         console.log('pase ', paste);
    //         $("#grid").on('cellvaluechanged', cellvaluechanged.bind(this,paste));
    //         // setTimeout(function(){ 
    //         //     cellvaluechanged();
    //         //     $("#else-grid-json").on('cellvaluechanged', cellvaluechanged);
    //         //  }, 1500);
    //     }
    // });
                


    // let i = 0;
    $("#grid").on('cellvaluechanged', function (event) {
        //$('#loadermain').show();
        
        var args = event.args;
        var godina = args.datafield;
        var sektor = args.rowindex;
        //var sektor1 = $("#grid").jqxGrid("getcellvalue", sektor, "name"); 
        var value = args.value;
        rowdata = new Object();
        rowdata['action'] = 'saveData';
        rowdata['sector'] = sektor;
        rowdata['year'] = godina;
        rowdata['value'] = value;
        if(value >= 0 ){
            $.ajax({
                url: myURL,
                dataType: 'json',
                type: 'POST',
                async: true,
                data: rowdata, 
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
                                //dataAdapter2.dataBind();
                                //$('#loadermain').hide();
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
             ShowErrorMessage("Final energy conumption share for " + sektor + " in " + godina + " is less then zero!");
        } 
	});//kraj cell edita


    $('#loadermain').hide();

    $("#showLog").click(function (e) {
        e.preventDefault();
        $('#log').html(`
            <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                <h4>${DEF.FED.title}</h4>
                ${DEF.FED.definition}
            </div>
        `);
        $('#log').toggle('slow');
    });

    $("#decUp").on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        window.d++;
        window.decimal = 'd' + parseInt(window.d);
        $('#grid').jqxGrid('refresh');
        $('#grid').jqxGrid('refreshaggregates');
    });

    $("#decDown").on('click', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        window.d--;
        window.decimal = 'd' + parseInt(window.d);
        $('#grid').jqxGrid('refresh');
        $('#grid').jqxGrid('refreshaggregates');
    });

    $(".toggleLabels").on('click', function (e) {
        e.preventDefault();
        chart1.seriesGroups[0].labels.visible = !chart1.seriesGroups[0].labels.visible;
        chart1.update();    
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

    $("#pngFed").click(function() {
        $("#jqxChart").jqxChart('saveAsPNG', 'Final energy demand.png',  getExportServer());
    }); 

    let res = true;
    $("#resizeColumns").click(function () {
        if(res){
            $('#grid').jqxGrid('autoresizecolumn', 'name');
        }
        else{
            $('#grid').jqxGrid('autoresizecolumns');
        }
        res = !res;        
    });

    $("#xlsAll").click(function (e) {
        e.preventDefault();
        $("#grid").jqxGrid('exportdata', 'xls', 'Final energy demand');
    });
    
    }else{
        $( "#ace-settings-btn" ).trigger( "click" );
        $('#loadermain').hide();
    }
});
</script>



