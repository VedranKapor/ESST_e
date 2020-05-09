
$(document).ready(function () {
    theme = 'bootstrap';
    session = getSession();
    var casename = session.case;  
    var user = session.us; 
    
    if (typeof(casename) != 'undefined'){   
        $("#cName").text(casename);
        let xmlDoc = getXML(casename, user);
        var yearsArray = getYears(xmlDoc);
        var years = getYearsIndexes(yearsArray);

        let ElMix = getElMixFuels(xmlDoc);
        let Techs = getTechs(ElMix);
        Techs.push('Storage');

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

        var srcChunk = [ "Month", "Week", "Day"];
        $("#dllChunk").jqxDropDownList({ source: srcChunk,selectedIndex: 0, theme:theme, width: 150, height: 30, autoDropDownHeight: true});
        var chunk = $("#dllChunk").jqxDropDownList('getSelectedItem').value;

        var daYears = new $.jqx.dataAdapter(years);
        $("#ddlYears").jqxDropDownList({
            source: daYears,
            selectedIndex: 0,
            theme: theme,
            displayMember: 'year',
            height: 30,
            width:150,
            autoDropDownHeight: true
         });
         var year = $("#ddlYears").jqxDropDownList('getSelectedItem').value;

         $( "#btnGenerate" ).on( "click", function() {
            $('.loaderStats').show(); 
            year = $("#ddlYears").jqxDropDownList('getSelectedItem').value;
            chunk = $("#dllChunk").jqxDropDownList('getSelectedItem').value;
            if(chunk == 'Month'){
                setTimeout(function () { init(data['M'], year, 'M', 'Month'); }, 300);
                
            }
            if(chunk == 'Week'){
                //init(data['W'], year, 'W', "Week");
                setTimeout(function () { init(data['W'], year, 'W', 'Week'); }, 300);
            }
            if(chunk == 'Day'){
                console.log(data['D'])
                //init(data['D'], year, 'D', "Day");
                setTimeout(function () { init(data['D'], year, 'D', 'Day'); }, 300);
            }
           // $('.loaderStats').hide(); 
            console.log('end');
        });

        $.ajax({
            method: 'GET',
            url: 'xml/'+ user + '/'+casename+'/hSimulation/STATS.json',
            dataType: 'json',
            async:true,
            cache:false,
            complete: function(e) {
                data = e.responseJSON;
                if(chunk == 'Month'){
                    init(data['M'], year, 'M', 'Month');
                }
                if(chunk == 'Week'){
                    init(data['W'], year, 'W', "Week");
                }
                if(chunk == 'Day'){
                    init(data['D'], year, 'D', "Day");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown.msg);
                $('#minLoader_lcoe').hide();
            }
        });

        function init(data, year, type, label) {
            console.log('calling');               
            
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
            console.log('osiceno');
            ///////////////////////////////////////////////HG

            colYears = getColStats( def[type], 'GWh', label)
    
            $.each( Techs, function( id, tech ) {
                if (tech != 'ImportExport'){
                    serHG.push({ dataField:tech, displayText: tech, fillColor:color_obj[tech] })
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

            console.log('Data ready');
    
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
            $('#gridHG').jqxGrid(setHG);

            console.log('gotov HG grid')
        
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
            
            let chartHG =  getPeriodChart(title='', description='', daChartHG, serHG, 'GWh', type= 'spline', datafield='period', labels=false, min, max, interval);
            $('#chartHG').jqxChart(chartHG);
            var chart1 = $('#chartHG').jqxChart('getInstance');
            console.log('gotov HG chart')
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
            $('#gridCG').jqxGrid(setCG);
            console.log('gotov CG grid')
    
            //////////////////chart CG
    
            srcChartCG =  {
                localdata: outChartCG,
                datatype: 'json',
                async: true, 
                cache: false,
            }; 
            let daChartCG = new $.jqx.dataAdapter(srcChartCG);

            let chartCG =  getPeriodChart(title='', description='', daChartCG, serHG, 'GWh', type= 'spline', datafield='period', labels=false, min, max, interval);
            $('#chartCG').jqxChart(chartCG);
            var chart2 = $('#chartCG').jqxChart('getInstance');
            console.log('gotov CG chart')
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
            $('#gridUD').jqxGrid(setUD);
    
            //////////////////chart CG
            
            // $.each( data['UD'][year], function( period, val ) {
            //     let tmpUD = {};
            //     tmpUD['period'] = period;
            //     tmpUD['UD'] = val/1000; // da dobijemo GWh
            //     outChartUD.push(tmpUD);
            // });
    
            srcChartUD =  {
                localdata: outChartUD,
                datatype: 'json',
                async: true, 
                cache: false,
            }; 
            let daChartUD = new $.jqx.dataAdapter(srcChartUD);
            serUD.push({dataField:'UD', displayText:'Unserved demand', fillColor:color_obj['Unserved demand'],lineColor:color_obj['Unserved demand']});
    
            let chartUD =  getPeriodChart(title='', description='', daChartUD, serUD, 'GWh', type= 'spline', datafield='period', labels=false, min, max, interval)
            $('#chartUD').jqxChart(chartUD);
            console.log('gotov UD')
               ////////////////////////////////////////EVENTS HG
            $("#xlsHG").click(function (e) {
                e.preventDefault();
                console.log('hg xls')
                $("#gridHG").jqxGrid('exportdata', 'xls', 'Electricity generation');
            });
            $("#pngHG").click(function() {
                $("#chartHG").jqxChart('saveAsPNG', 'Electricity generation.png',  getExportServer());
            }); 
            $("#rcHG").click(function () {
                $('#gridHG').jqxGrid('autoresizecolumns');
            });
            ////////////////////////////////////////EVENTS CG
            $("#xlsCG").click(function (e) {
                e.preventDefault();
                console.log('cg xls')
                $("#gridCG").jqxGrid('exportdata', 'xls', 'Electricity curtailment underutilization');
            });
            $("#pngCG").click(function() {
                $("#chartCG").jqxChart('saveAsPNG', 'Electricity curtailment/underutilization.png',  getExportServer());
            }); 
            $("#rcCG").click(function () {
                $('#gridCG').jqxGrid('autoresizecolumns');
            });
            ////////////////////////////////////////EVENTS
            $("#xlsUD").click(function (e) {
                e.preventDefault();
                $("#gridUD").jqxGrid('exportdata', 'xls', 'Unserved demand');
            });
            $("#pngCG").click(function() {
                $("#chartCG").jqxChart('saveAsPNG', 'Unserved demand.png',  getExportServer());
            }); 
            $("#rcUD").click(function () {
                $('#gridUD').jqxGrid('autoresizecolumns');
            });
            var chart3 = $('#chartUD').jqxChart('getInstance');

            /////////////////////////////////////////////////////////////////////////////PAGE EVENTS////////////////////////////////////////////////////////////////////////////
            $("#showLog").click(function (e) {
                e.preventDefault();
                $('#log').html(`
                    <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                        <h4>${DEF.REP.title}</h4>
                        ${DEF.REP.definition}
                    </div>
                `);
                $('#log').toggle('slow');
            });

            $(".decUp").on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                window.d++;
                window.decimal = 'd' + parseInt(window.d);
                $('#gridHG').jqxGrid('refresh');
                $('#gridHG').jqxGrid('refreshaggregates');
                $('#gridCG').jqxGrid('refresh');
                $('#gridCG').jqxGrid('refreshaggregates');
                $('#gridUD').jqxGrid('refresh');
                $('#gridUD').jqxGrid('refreshaggregates');
            });
            
            $(".decDown").on('click', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                window.d--;
                window.decimal = 'd' + parseInt(window.d);
                $('#gridHG').jqxGrid('refresh');
                $('#gridHG').jqxGrid('refreshaggregates');
                $('#gridCG').jqxGrid('refresh');
                $('#gridCG').jqxGrid('refreshaggregates');
                $('#gridUD').jqxGrid('refresh');
                $('#gridUD').jqxGrid('refreshaggregates');
            });

            $(".switchChart").on('click', function (e) {
                e.preventDefault();

                var chartType = $(this).attr('data-chartType');
                var chartId = $(this).attr('id-chart');
                $('.widget-toolbar a').switchClass( "green", "grey" );
                $('#'+chartType).switchClass( "grey", "green" );
                switch(chartId) {
                    case '1':
                        chart1.seriesGroups[0].type = CHART_TYPE[chartType];
                        if(chartType == 'barChart'){
                            chart1.seriesGroups[0].labels.angle = 90;
                        }else{
                            chart1.seriesGroups[0].labels.angle = 0;
                        }
                        chart1.update();  
                    break;
                    case '2':
                        chart2.seriesGroups[0].type = CHART_TYPE[chartType];
                        if(chartType == 'barChart'){
                            chart2.seriesGroups[0].labels.angle = 90;
                        }else{
                            chart2.seriesGroups[0].labels.angle = 0;
                        }
                        chart2.update();  
                    break;
                    case '3':
                        chart3.seriesGroups[0].type = CHART_TYPE[chartType];
                        if(chartType == 'barChart'){
                            chart3.seriesGroups[0].labels.angle = 90;
                        }else{
                            chart3.seriesGroups[0].labels.angle = 0;
                        }
                        chart3.update();  
                    break;
                    case '4':
                        chart4.seriesGroups[0].type = CHART_TYPE[chartType];
                        if(chartType == 'barChart'){
                            chart4.seriesGroups[0].labels.angle = 90;
                        }else{
                            chart4.seriesGroups[0].labels.angle = 0;
                        }
                        chart4.update();  
                    break;
                    case '5':
                        chart5.seriesGroups[0].type = CHART_TYPE[chartType];
                        if(chartType == 'barChart'){
                            chart5.seriesGroups[0].labels.angle = 90;
                        }else{
                            chart5.seriesGroups[0].labels.angle = 0;
                        }
                        chart5.update();  
                    break;
                    default:
                }
            });
            $('.loaderStats').hide(); 
            console.log('gotov events')
        }

    } 
    else{
        $("#cName").text("Please select a case study!");
    }
    $('#loadermain').hide();  
}); 