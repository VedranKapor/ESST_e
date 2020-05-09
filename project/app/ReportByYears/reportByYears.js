
$(document).ready(function () {
    theme = 'bootstrap';
    session = getSession();
    var casename = session.case;  
    var user = session.us; 
    
    if (typeof(casename) != 'undefined'){   
        $("#cName").text(casename);
        let xmlDoc = getXML(casename, user);
        //var elmix_fuels = getElMixFuels(xmlDoc);
        //var series_elmix_fuels =  getElMixFuelSeries(elmix_fuels);
        var yearsArray = getYears(xmlDoc);
        var years = getYearsIndexes(yearsArray);
        let ElMix = getElMixFuels(xmlDoc);
        let Techs = getTechs(ElMix);
        Techs.push('Storage');
        
        $.ajax({
            method: 'GET',
            url: 'xml/'+ user + '/'+casename+'/hSimulation/RHG_yt.json',
            dataType: 'json',
            async:true,
            cache:false,
            complete: function(e) {
                var data = e.responseJSON;
                init(data)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown.msg);
                $('#minLoader_lcoe').hide();
            }
        });
    
        function init(data){

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
            $('#gridHG').jqxGrid(setHG);
    
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

            let chartHG = getChartSettings(title='', description='', daChartHG, serHG, 'GWh');
            $('#chartHG').jqxChart(chartHG);
    
            ////////////////////////////////////////EVENTS
            $("#xlsHG").click(function (e) {
                e.preventDefault();
                $("#gridHG").jqxGrid('exportdata', 'xls', 'Electricity generation');
            });
            $("#pngHG").click(function() {
                $("#chartHG").jqxChart('saveAsPNG', 'Electricity generation.png',  getExportServer());
            }); 
            $("#rcHG").click(function () {
                $('#gridHG').jqxGrid('autoresizecolumns');
            });
            var chart1 = $('#chartHG').jqxChart('getInstance');

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
            $('#gridCG').jqxGrid(setCG);

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

            let chartCG = getChartSettings(title='', description='', daChartCG, serHG, 'GWh');
            $('#chartCG').jqxChart(chartCG);

            ////////////////////////////////////////EVENTS
            $("#xlsCG").click(function (e) {
                e.preventDefault();
                $("#gridCG").jqxGrid('exportdata', 'xls', 'Electricity curtailment/underutilization');
            });
            $("#pngCG").click(function() {
                $("#chartCG").jqxChart('saveAsPNG', 'Electricity curtailment/underutilization.png',  getExportServer());
            }); 
            $("#rcCG").click(function () {
                $('#gridCG').jqxGrid('autoresizecolumns');
            });
            var chart2 = $('#chartCG').jqxChart('getInstance');

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
            $('#gridUD').jqxGrid(setUD);

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

            let chartUD = getChartSettings(title='', description='', daChartUD, serUD, 'GWh');
            $('#chartUD').jqxChart(chartUD);

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

    }

   } else{
        $("#cName").text("Please select a case study!");
   }
   $('#loadermain').hide();  
}); 