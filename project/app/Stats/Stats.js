$(document).ready(function () {
    access();
    
    session = getSession();
    var casename = session.case;  
    var user = session.us; 
   
    if (typeof(casename) != 'undefined'){   
        let STATS = FileExists('STATS.json', user, casename);

        if(STATS){
            let theme = 'bootstrap';
            $("#cName").text(casename);
            let xmlDoc = getXML(casename, user);
            var yearsArray = getYears(xmlDoc);
            var years = getYearsIndexes(yearsArray);
    
            let ElMix = getElMixFuels(xmlDoc);
            let Techs = getTechs(ElMix);
            Techs.push('Storage');
    
            let chart = {};
            
            let data;
    
            let def = {
                'M': 12,
                'W': 52,
                'D': 365
            };
    
            let chartDef = {
                'M': {
                    'minValue': 0,
                    'maxValue': 11
                    ,
                    'unitInterval': 1
                },
                'W': {
                    'minValue': 8,
                    'maxValue': 15,
                    'unitInterval': 7
                },
                'D': {
                    'minValue': 168,
                    'maxValue': 193,
                    'unitInterval': 24
                },
            }
    
            var container = $('#ddlYears');
            $.each( years, function( key, value ) {
                container.append('<option value="'+value+'" id="'+value+'">'+value+'</option>');
            });
    
            var year = $( "#ddlYears" ).val();
    
             $( "#btnGenerate" ).on( "click", function() {
                $('.loaderStatsM').show(); 
                $('.loaderStatsW').show(); 
                $('.loaderStatsD').show(); 
                year = $( "#ddlYears" ).val();
                setTimeout(function () { 
                    init(data['M'], year, 'M', 'Month'); 
                    init(data['W'], year, 'W', 'Week');
                    init(data['D'], year, 'D', 'Day');
                }, 300);
            });
    
            $.ajax({
                method: 'GET',
                url: 'xml/'+ user + '/'+casename+'/hSimulation/RHG_yt.json',
                dataType: 'json',
                async:true,
                cache:false,
                complete: function(e) {
                    var data = e.responseJSON;
                    initY(data)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    ShowErrorMessage(errorThrown.msg);
                    $('#minLoader_lcoe').hide();
                }
            });
    
            $.ajax({
                method: 'GET',
                url: 'xml/'+ user + '/'+casename+'/hSimulation/STATS.json',
                dataType: 'json',
                async:true,
                cache:false,
                complete: function(e) {
                    data = e.responseJSON;
                    init(data['M'], year, 'M', 'Month');
                    init(data['W'], year, 'W', 'Week');
                    init(data['D'], year, 'D', "Day");
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    ShowErrorMessage(errorThrown.msg);
                    $('#minLoader_lcoe').hide();
                }
            });
    
            function init(data, year, type, label) {
                let outHG = [];
                let outCG = [];
                let outUD = [];
                let outChartHG = [];
                let outChartCG = [];
                let outChartUD = [];
    
                let serHG = [];
                let serUD = [];
                let colYears = [];
    
                let datafields = [];
    
                let srcHG = {};
                let srcCG = {}
                let srcGridHG = {};
                let srcChartHG = {};
                let srcUD = {};
    
                // $('#chartHG').empty();
                // $('#chartCG').empty();
                // $('#chartUD').empty();
                ///////////////////////////////////////////////HG
                colYears = getColStats( def[type], 'GWh', label)
                $.each( Techs, function( id, tech ) {
                    if (tech != 'ImportExport'){
                        serHG.push({ dataField:tech, displayText: tech, fillColor:color_obj[tech], lineWidth: 1.5, opacity: 0.8 })
                        let tmpHG = {};
                        let tmpCG = {};
                        tmpHG['name'] = tech;
                        tmpCG['name'] = tech;
                        for(let i=1; i<=def[type]; i++){
                            tmpHG[i] = data['HG'][year][tech][i] / 1000;  //da dobijemo GWh
                            tmpCG[i] = data['CG'][year][tech][i] / 1000;  //da dobijemo GWh
                        }
                        outHG.push(tmpHG);
                        outCG.push(tmpCG);
                    }
                });
        
                datafields.push( {name: "name", type: "string"});
                let tmpUD = {};
                tmpUD['name'] = 'Unserved demand';
                for(let i=1; i<=def[type]; i++){
                    datafields.push( {name: i, type: "string"},);
                    let tmpHG = {};
                    let tmpCG = {}; 
                    let tmpUDch = {};
    
                    tmpHG['period'] = i;
                    tmpCG['period'] = i;
                    tmpUD[i] = data['UD'][year][i] / 1000;  //da dobijemo GWh
                    tmpUDch['period'] = i;
                    tmpUDch['UD'] = data['UD'][year][i]/1000; // da dobijemo GWh
                    $.each( Techs, function( id, tech ) {
                        if (tech != 'ImportExport'){
                            tmpHG[tech] = data['HG'][year][tech][i] / 1000;  //da dobijemo GWh
                            tmpCG[tech] = data['CG'][year][tech][i] / 1000;  //da dobijemo GWh
                        }
                    });
                    outChartHG.push(tmpHG);
                    outChartCG.push(tmpCG);
                    outChartUD.push(tmpUDch);
                }
                outUD.push(tmpUD);
        
                srcGridHG =  {
                    localdata: outHG,
                    datatype: 'json',
                    async: true, 
                    cache: false,       
                    datafields:datafields      
                };  
        
                let daHG = new $.jqx.dataAdapter(srcGridHG);
                
                setHG = {
                    autoheight: true,
                    width: '100%',
                    theme: theme,   
                    rowsheight:24,
                    columnsresize: true,  
                    showaggregates: true,  
                    showstatusbar: true,
                    statusbarheight: 30,   
                    source: daHG,
                    selectionmode: 'multiplecellsadvanced',
                    columns: colYears
                };
                $('#gridHG'+type).jqxGrid(setHG);        
                //////////////////////////////////////////CHART
                srcChartHG =  {
                    localdata: outChartHG,
                    datatype: 'json',
                    async: true, 
                    cache: false,
                }; 
                let daChartHG = new $.jqx.dataAdapter(srcChartHG);
        
                let min = chartDef[type]['minValue'];
                let max = chartDef[type]['maxValue'];
                let interval = chartDef[type]['unitInterval']
                
                let legendLayout = { left: 95, top: 5, width: '100%', height: 200, flow: 'horizontal' };
                let chartHG;
                if(type =='M'){
                    chartHG  = getChartSettings(title='', description='', daChartHG, serHG, 'GWh', 'spline', datafield='period',legendLayout)
                }else{
                    chartHG =  getPeriodChart(title='', description='', daChartHG, serHG, 'GWh', 'spline', datafield='period', labels=true, min, max, interval,legendLayout);
                }
               
                $(`#chartHG${type}`).jqxChart(chartHG);
                chart[type+'1'] = $(`#chartHG${type}`).jqxChart('getInstance');
               // console.log('gotov HG chart')
                /////////////////////////////////////////////////////////////////////////////////CHG
        
                srcCG =  {
                    localdata: outCG,
                    datatype: 'json',
                    async: true, 
                    cache: false,       
                    datafields:datafields      
                };  
        
                let daCG = new $.jqx.dataAdapter(srcCG);
        
                setCG = {
                    autoheight: true,
                    width: '100%',
                    theme: theme,   
                    rowsheight:24,
                    columnsresize: true,  
                    showaggregates: true,  
                    showstatusbar: true,
                    statusbarheight: 30,   
                    source: daCG,
                    selectionmode: 'multiplecellsadvanced',
                    columns: colYears
                };
    
                $(`#gridCG${type}`).jqxGrid(setCG);
               // console.log('gotov CG grid')
        
                //////////////////chart CG
        
                srcChartCG =  {
                    localdata: outChartCG,
                    datatype: 'json',
                    async: true, 
                    cache: false,
                }; 
                let daChartCG = new $.jqx.dataAdapter(srcChartCG);
    
                //let chartCG =  getPeriodChart(title='', description='', daChartCG, serHG, 'GWh', 'spline', datafield='period', labels=false, min, max, interval);
                let chartCG;
                if(type =='M'){
                    chartCG  = getChartSettings(title='', description='', daChartCG, serHG, 'GWh', 'spline', datafield='period',legendLayout)
                }else{
                    chartCG =  getPeriodChart(title='', description='', daChartCG, serHG, 'GWh', 'spline', datafield='period', labels=true, min, max, interval,legendLayout);
                }
                $('#chartCG'+type).jqxChart(chartCG);
                chart[type+'2'] = $('#chartCG'+type).jqxChart('getInstance');
                //console.log('gotov CG chart')
                /////////////////////////////////////////////////////////////////////////////////UD
        
                srcUD =  {
                    localdata: outUD,
                    datatype: 'json',
                    async: true, 
                    cache: false,       
                    datafields:datafields      
                };  
        
                let daUD = new $.jqx.dataAdapter(srcUD);
        
                setUD = {
                    autoheight: true,
                    width: '100%',
                    theme: theme,   
                    rowsheight:24,
                    columnsresize: true,  
                    showaggregates: false,  
                    showstatusbar: false,
                    statusbarheight: 30,   
                    source: daUD,
                    selectionmode: 'multiplecellsadvanced',
                    columns: colYears
                };
                $('#gridUD'+type).jqxGrid(setUD);
        
                //////////////////chart CG
        
                srcChartUD =  {
                    localdata: outChartUD,
                    datatype: 'json',
                    async: true, 
                    cache: false,
                }; 
                let daChartUD = new $.jqx.dataAdapter(srcChartUD);
                serUD.push({dataField:'UD', displayText:'Unserved demand', fillColor:color_obj['Unserved demand'],lineColor:color_obj['Unserved demand'], lineWidth: 1.5, opacity: 0.8});
        
                //let chartUD =  getPeriodChart(title='', description='', daChartUD, serUD, 'GWh', 'spline', datafield='period', labels=false, min, max, interval)
                
                let chartUD;
                if(type =='M'){
                    chartUD  = getChartSettings(title='', description='', daChartUD, serUD, 'GWh', 'spline', datafield='period', legendLayout)
                }else{
                    chartUD =  getPeriodChart(title='', description='', daChartUD, serUD, 'GWh', 'spline', datafield='period', labels=true, min, max, interval, legendLayout)
                }
    
                $('#chartUD'+type).jqxChart(chartUD);
                chart[type+'3'] = $('#chartUD'+type).jqxChart('getInstance');
    
                //console.log('gotov UD')
                   ////////////////////////////////////////EVENTS HG
                $("#xlsHG"+type).click(function (e) {
                    e.preventDefault();
                    $("#gridHG"+type).jqxGrid('exportdata', 'xls', 'Electricity generation');
                });
                $("#pngHG"+type).click(function() {
                    $("#chartHG"+type).jqxChart('saveAsPNG', 'Electricity generation.png',  getExportServer());
                }); 
                // $("#rcHG"+type).click(function () {
                //     $('#gridHG'+type).jqxGrid('autoresizecolumns');
                // });
                let resHG = {};
                resHG[type] = true;
                $("#rcHG"+type).click(function () {
                    console.log(resHG[type]);
                    if(resHG[type]){
                        $('#gridHG'+type).jqxGrid('autoresizecolumn', 'name');
                    }
                    else{
                        $('#gridHG'+type).jqxGrid('autoresizecolumns');
                    }
                    resHG[type] = !resHG[type];        
                });
                ////////////////////////////////////////EVENTS CG
                $("#xlsCG"+type).click(function (e) {
                    e.preventDefault();
                    $("#gridCG"+type).jqxGrid('exportdata', 'xls', 'Electricity curtailment underutilization');
                });
                $("#pngCG"+type).click(function() {
                    $("#chartCG"+type).jqxChart('saveAsPNG', 'Electricity curtailment/underutilization.png',  getExportServer());
                }); 
                // $("#rcCG"+type).click(function () {
                //     $('#gridCG'+type).jqxGrid('autoresizecolumns');
                // });
                let resCG = {};
                resCG[type] = true;
                $("#rcCG"+type).click(function () {
                    console.log(resHG[type]);
                    if(resCG[type]){
                        $('#gridCG'+type).jqxGrid('autoresizecolumn', 'name');
                    }
                    else{
                        $('#gridCG'+type).jqxGrid('autoresizecolumns');
                    }
                    resCG[type] = !resCG[type];        
                });
                ////////////////////////////////////////EVENTS
                $("#xlsUD"+type).click(function (e) {
                    e.preventDefault();
                    $("#gridUD"+type).jqxGrid('exportdata', 'xls', 'Unserved demand');
                });
                $("#pngCG"+type).click(function() {
                    $("#chartCG"+type).jqxChart('saveAsPNG', 'Unserved demand.png',  getExportServer());
                }); 

                // $("#rcUD"+type).click(function () {
                //     $('#gridUD'+type).jqxGrid('autoresizecolumns');
                // });
                let resUD = {};
                resUD[type] = true;
                $("#rcUD"+type).click(function () {
                    console.log(resHG[type]);
                    if(resUD[type]){
                        $('#gridUD'+type).jqxGrid('autoresizecolumn', 'name');
                    }
                    else{
                        $('#gridUD'+type).jqxGrid('autoresizecolumns');
                    }
                    resUD[type] = !resUD[type];        
                });

                let resIC = true;
                $("#rcUD"+type).click(function () {
                    if(resIC){
                        $('#gridUD'+type).jqxGrid('autoresizecolumn', 'name');
                    }
                    else{
                        $('#gridUD'+type).jqxGrid('autoresizecolumns');
                    }
                    resIC = !resIC;        
                });
        
                /////////////////////////////////////////////////////////////////////////////PAGE EVENTS////////////////////////////////////////////////////////////////////////////
                $(".showLog"+type).click(function (e) {
                    e.preventDefault();
                    $('#log'+type).html(`
                        <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                            <h4>${DEF[type].title}</h4>
                            ${DEF[type].definition}
                        </div>
                    `);
                    $('#log'+type).toggle('slow');
                });
    
                $(".decUp"+type).on('click', function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    window.d++;
                    window.decimal = 'd' + parseInt(window.d);
                    $('#gridHG'+type).jqxGrid('refresh');
                    $('#gridHG'+type).jqxGrid('refreshaggregates');
                    $('#gridCG'+type).jqxGrid('refresh');
                    $('#gridCG'+type).jqxGrid('refreshaggregates');
                    $('#gridUD'+type).jqxGrid('refresh');
                    $('#gridUD'+type).jqxGrid('refreshaggregates');
        
                });
                
                $(".decDown"+type).on('click', function(e){
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    window.d--;
                    window.decimal = 'd' + parseInt(window.d);
                    $('#gridHG'+type).jqxGrid('refresh');
                    $('#gridHG'+type).jqxGrid('refreshaggregates');
                    $('#gridCG'+type).jqxGrid('refresh');
                    $('#gridCG'+type).jqxGrid('refreshaggregates');
                    $('#gridUD'+type).jqxGrid('refresh');
                    $('#gridUD'+type).jqxGrid('refreshaggregates');
    
                });
    
                $('.loaderStats'+type).hide(); 
                console.log('gotov events')
            }
    
            function initY(data){
    
                ///////////////////////////////////////////////HG

                let outHG = [];
                let outCG = [];
                let colYears = getColYr_e('', yearsArray, 'd', 'GWh')  
                let serHG = [];
                let datafields = [];
    
                $.each( Techs, function( id, tech ) {
                    if (tech != 'ImportExport'){
                        serHG.push({ dataField:tech, displayText: tech, fillColor:color_obj[tech] })
                        let tmpHG = {};
                        let tmpCG = {};
                        tmpHG['name'] = tech;
                        tmpCG['name'] = tech;
                        $.each( years, function( idy, year ) {
                            tmpHG[year] = data['HG'][year][tech] / 1000;  //da dobijemo GWh
                            tmpCG[year] = data['CG'][year][tech] / 1000;  //da dobijemo GWh
                        });
                        outHG.push(tmpHG);
                        outCG.push(tmpCG);
                    }
                });
    
                datafields.push( {name: "name", type: "string"});
                $.each( years, function( idy, year ) {
                    datafields.push( {name: year, type: "string"},);
                });
    
                srcGridHG =  {
                    localdata: outHG,
                    datatype: 'json',
                    async: true, 
                    cache: false,       
                    datafields:datafields      
                };  
    
                var daHG = new $.jqx.dataAdapter(srcGridHG);
    
                setHG = {
                    autoheight: true,
                    width: '100%',
                    theme: theme,   
                    rowsheight:24,
                    columnsresize: true,  
                    showaggregates: true,  
                    showstatusbar: true,
                    statusbarheight: 30,   
                    source: daHG,
                    selectionmode: 'multiplecellsadvanced',
                    columns: colYears
                };
                $('#gridHGY').jqxGrid(setHG);
        
                //////////////////////////////////////////CHART
                let outChartHG = [];
    
                $.each( data['HG'], function( year, obj ) {
                    let tmpHG = {};
                    tmpHG['year'] = year;
                    $.each( data['HG'][year], function( tech, val ) {
                        tmpHG[tech] = val/1000; // da dobijemo GWh
                    });
                    outChartHG.push(tmpHG);
                });
    
                srcChartHG =  {
                    localdata: outChartHG,
                    datatype: 'json',
                    async: true, 
                    cache: false,
                }; 
                var daChartHG = new $.jqx.dataAdapter(srcChartHG);

                let legendLayout = { left: 95, top: 5, width: '100%', height: 200, flow: 'horizontal' };
    
                let chartHG = getChartSettings(title='', description='', daChartHG, serHG, 'GWh', type= 'stackedcolumn', datafield='year', legendLayout);
                $('#chartHGY').jqxChart(chartHG);
        
                ////////////////////////////////////////EVENTS
                $("#xlsHGY").click(function (e) {
                    e.preventDefault();
                    $("#gridHGY").jqxGrid('exportdata', 'xls', 'Electricity generation');
                });
                $("#pngHGY").click(function() {
                    $("#chartHGY").jqxChart('saveAsPNG', 'Electricity generation.png',  getExportServer());
                }); 
                // $("#rcHGY").click(function () {
                //     $('#gridHGY').jqxGrid('autoresizecolumns');
                // });
                let resHGY = true;
                $("#rcHGY").click(function () {
                    console.log(resHGY);
                    if(resHGY){
                        $('#gridHGY').jqxGrid('autoresizecolumn', 'name');
                    }
                    else{
                        $('#gridHGY').jqxGrid('autoresizecolumns');
                    }
                    resHGY = !resHGY;        
                });
                chart['Y1'] = $('#chartHGY').jqxChart('getInstance');
    
                /////////////////////////////////////////////////////////////////////////////////CHG
    
                srcCG =  {
                    localdata: outCG,
                    datatype: 'json',
                    async: true, 
                    cache: false,       
                    datafields:datafields      
                };  
    
                var daCG = new $.jqx.dataAdapter(srcCG);
    
                setCG = {
                    autoheight: true,
                    width: '100%',
                    theme: theme,   
                    rowsheight:24,
                    columnsresize: true,  
                    showaggregates: true,  
                    showstatusbar: true,
                    statusbarheight: 30,   
                    source: daCG,
                    selectionmode: 'multiplecellsadvanced',
                    columns: colYears
                };
                $('#gridCGY').jqxGrid(setCG);
    
                //////////////////chart CG
                let outChartCG = [];
    
                $.each( data['CG'], function( year, obj ) {
                    let tmpCG = {};
                    tmpCG['year'] = year;
                    $.each( data['CG'][year], function( tech, val ) {
                        tmpCG[tech] = val/1000; // da dobijemo GWh
                    });
                    outChartCG.push(tmpCG);
                });
    
                srcChartCG =  {
                    localdata: outChartCG,
                    datatype: 'json',
                    async: true, 
                    cache: false,
                }; 
                var daChartCG = new $.jqx.dataAdapter(srcChartCG);
    
                let chartCG = getChartSettings(title='', description='', daChartCG, serHG, 'GWh', type= 'stackedcolumn', datafield='year', legendLayout);
                $('#chartCGY').jqxChart(chartCG);
    
                ////////////////////////////////////////EVENTS
                $("#xlsCGY").click(function (e) {
                    e.preventDefault();
                    $("#gridCGY").jqxGrid('exportdata', 'xls', 'Electricity curtailment/underutilization');
                });
                $("#pngCGY").click(function() {
                    $("#chartCGY").jqxChart('saveAsPNG', 'Electricity curtailment/underutilization.png',  getExportServer());
                }); 
                // $("#rcCGY").click(function () {
                //     $('#gridCGY').jqxGrid('autoresizecolumns');
                // });
                let resCGY = true;
                $("#rcCGY").click(function () {
                    if(resCGY){
                        $('#gridCGY').jqxGrid('autoresizecolumn', 'name');
                    }
                    else{
                        $('#gridCGY').jqxGrid('autoresizecolumns');
                    }
                    resCGY = !resCGY;        
                });

                chart['Y2'] = $('#chartCGY').jqxChart('getInstance');
    
                /////////////////////////////////////////////////////////////////////////////////UD
                let outUD = [];
                let colUDYears = getColUD( yearsArray, 'GWh'); 
                let serUD = [];
    
                let tmpUD = {};
                tmpUD['name'] = 'Unserved demand';
                $.each( years, function( idy, year ) {
                    tmpUD[year] = data['UD'][year] / 1000;  //da dobijemo GWh
                });
                outUD.push(tmpUD);
    
                srcUD =  {
                    localdata: outUD,
                    datatype: 'json',
                    async: true, 
                    cache: false,       
                    datafields:datafields      
                };  
    
                var daUD = new $.jqx.dataAdapter(srcUD);
    
                setUD = {
                    autoheight: true,
                    width: '100%',
                    theme: theme,   
                    rowsheight:24,
                    columnsresize: true,  
                    showaggregates: true,  
                    showstatusbar: true,
                    statusbarheight: 30,   
                    source: daUD,
                    selectionmode: 'multiplecellsadvanced',
                    columns: colUDYears
                };
                $('#gridUDY').jqxGrid(setUD);
    
                //////////////////chart CG
                let outChartUD = [];
    
                $.each( data['UD'], function( year, val ) {
                    let tmpUD = {};
                    tmpUD['year'] = year;
                    tmpUD['UD'] = val/1000; // da dobijemo GWh
                    outChartUD.push(tmpUD);
                });
    
                srcChartUD =  {
                    localdata: outChartUD,
                    datatype: 'json',
                    async: true, 
                    cache: false,
                }; 
                var daChartUD = new $.jqx.dataAdapter(srcChartUD);
                serUD.push({dataField:'UD', displayText:'Unserved demand', fillColor:color_obj['Unserved demand'],lineColor:color_obj['Unserved demand']});
    
                let chartUD = getChartSettings(title='', description='', daChartUD, serUD, 'GWh', type= 'stackedcolumn', datafield='year', legendLayout);
                $('#chartUDY').jqxChart(chartUD);
    
                ////////////////////////////////////////EVENTS
                $("#xlsUDY").click(function (e) {
                    e.preventDefault();
                    $("#gridUDY").jqxGrid('exportdata', 'xls', 'Unserved demand');
                });
                $("#pngCGY").click(function() {
                    $("#chartCGY").jqxChart('saveAsPNG', 'Unserved demand.png',  getExportServer());
                }); 
                // $("#rcUDY").click(function () {
                //     $('#gridUDY').jqxGrid('autoresizecolumns');
                // });
                let resUD = true;
                $("#rcUDY").click(function () {
                    console.log('UD res', resUD)
                    if(resUD){
                        $('#gridUDY').jqxGrid('autoresizecolumn', 'name');
                    }
                    else{
                        $('#gridUDY').jqxGrid('autoresizecolumns');
                    }
                    resUD = !resUD;        
                });

                chart['Y3'] = $('#chartUDY').jqxChart('getInstance');
    
            /////////////////////////////////////////////////////////////////////////////PAGE EVENTS////////////////////////////////////////////////////////////////////////////
            $(".showLogY").click(function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $('#logY').html(`
                    <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                        <h4>${DEF.Y.title}</h4>
                        ${DEF.Y.definition}
                    </div>
                `);
                $('#logY').toggle('slow');
            });
    
            $(".decUpY").on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                window.d++;
                window.decimal = 'd' + parseInt(window.d);
                $('#gridHGY').jqxGrid('refresh');
                $('#gridHGY').jqxGrid('refreshaggregates');
                $('#gridCGY').jqxGrid('refresh');
                $('#gridCGY').jqxGrid('refreshaggregates');
                $('#gridUDY').jqxGrid('refresh');
                $('#gridUDY').jqxGrid('refreshaggregates');
            });
        
            $(".decDownY").on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                window.d--;
                window.decimal = 'd' + parseInt(window.d);
                $('#gridHGY').jqxGrid('refresh');
                $('#gridHGY').jqxGrid('refreshaggregates');
                $('#gridCGY').jqxGrid('refresh');
                $('#gridCGY').jqxGrid('refreshaggregates');
                $('#gridUDY').jqxGrid('refresh');
                $('#gridUDY').jqxGrid('refreshaggregates');
            });
            }

            ///////////////////////////////////////////////////////EVENTS 
            $(".toggleLabels").on('click', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var chartId = $(this).attr('id-chart');
                console.log(chartId);
                chart[chartId].seriesGroups[0].labels.visible = !chart[chartId].seriesGroups[0].labels.visible;
                chart[chartId].update();    
            });
    
            $(".switchChart").on('click', function (e) {
                e.preventDefault();
                e.stopImmediatePropagation();
    
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
        }else{
            //nije izvrsena hSimnualcija i nema STATS file
            $("#msgStat").show();
        }
    } 
    else{
        $("#cName").text("Please select a case study!");
    }
    $('#loadermain').hide();  
}); 