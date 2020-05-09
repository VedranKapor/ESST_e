
$(document).ready(function () {
    theme = 'bootstrap';
    session = getSession();
    var casename = session.case;  
    var user = session.us; 

    if (typeof(casename) != 'undefined'){   
        let xmlDoc = getXML(casename, user);
        let currency = getCurrency(xmlDoc);
        var elmix_fuels = getElMixFuels(xmlDoc);
        var series_elmix_fuels =  getElMixFuelSeriesWOImportExport(elmix_fuels);
        var yearsArray = getYears(xmlDoc);
        var years = getYearsIndexes(yearsArray);
        $("#cName").text(casename);

        var source ={
           url: 'data/DE_technology_data.php?casename='+casename,
           root: 'data',
           datatype: 'json',
           cache: false,
           datafields: [
               { name: 'year', type: 'string' },
               { name: 'technology', type: 'string' },
               { name: 'Installed_power', type: 'number' },
               { name: 'Capacity_factor', type: 'number' },
               { name: 'Lifetime', type: 'number' },
               { name: 'Construction_time', type: 'number' },
               { name: 'CO2', type: 'number' },
               { name: 'NOX', type: 'number' },
               { name: 'SO2', type: 'number' },
               { name: 'PM', type: 'number' },
               { name: 'Fuel_cost', type: 'number' },
               { name: 'Investment_cost', type: 'number' },
               { name: 'Operating_cost_fixed', type: 'number' },
               { name: 'Operating_cost_variable', type: 'number' },
            ]
       };   

       var dataAdapter = new $.jqx.dataAdapter(source);
       //console.log(dataAdapter);

       var columnsrenderer = function (value) {
          
        return '<div style="text-align: center; margin-top: 12px; word-wrap:normal; white-space:normal;">' + value + '</div>';
        }

        var cellsrenderer = function (row, column, value, defaulthtml, columnproperties) {
            //console.log('value ', typeof(value))
            if ( value == 0) {
               // console.log("0 ", ' type ', typeof(value) ,' row ', row,  ' column ', column,  ' value ', value)
                value = $.jqx.dataFormat.formatnumber(value, 'd2');
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i>' + value + '</span>';
           }
           else if ( value < 0) {
                  //  console.log('minus');
                value = $.jqx.dataFormat.formatnumber(value, 'd2');
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';"><i class="fa fa-exclamation-triangle danger" aria-hidden="true"></i>' + value + '</span>';
            }
            else if (column == 'Capacity_factor' &&  (value<0||value>100)) {
                onsole.log('CF');
                value = $.jqx.dataFormat.formatnumber(value, 'd2');
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';"><i class="fa fa-exclamation-triangle danger" aria-hidden="true"></i>' + value + '</span>';
            }
            else if(value == "null"){
               // console.log("null ", ' row ', row,  ' column ', column,  ' value ', value)
                value = $.jqx.dataFormat.formatnumber(20000000, 'd2');
                return '<span style="margin: 4px;  float:right; color: #438EB9; font-weight:bold" >'+value+'</span>';
            }

            else if(value == ""){
               // console.log("prazno ", ' row ', row,  ' column ', column,  ' value ', value)
                value = $.jqx.dataFormat.formatnumber(10000, 'd2');
                return '<span style="margin: 4px; float:right; ">'+value+'</span>';
            }

            else if(isNaN(value)){
                //console.log("NaN ", ' row ', row,  ' column ', column,  ' value ', value)
                value = $.jqx.dataFormat.formatnumber(30000, 'd2');
                return '<span style="margin: 4px; float:right;"><i class="fa fa-exclamation-triangle red" aria-hidden="true"></i>' + value + '</span>';
            } 
            else if(typeof(value)==='string'){
               // console.log("NaN ", ' row ', row,  ' column ', column,  ' value ', value)
                value = $.jqx.dataFormat.formatnumber(30000, 'd2');
                return '<span style="margin: 4px; float:right;"><i class="fa fa-exclamation-triangle red" aria-hidden="true"></i>' + value + '</span>';
            } 
        }

       let height = $( window ).height() -245 ;
       $("#jqxgrid_daTechs").jqxGrid({
            // autoheight: true,
            // autorowheight: true,
            showfilterrow: false,
            filterable: true,
            sortable: true,
            columnsheight: 60,
            columnsresize:true, 
            //rowsheight: 25,    
            //columnsautoresize:true,
            altrows: true,
            groupable: true,
            showgroupsheader: true,
            height: height,
            width: '100%',
            theme: theme,
            source: dataAdapter,
            editable: true,
            selectionmode: 'multiplecellsadvanced',
            columns: [
               { text: 'Year',                                              datafield: 'year',                      align: 'center',cellalign: 'left',      pinned: true, editable: false  ,maxwidth: 155, minwidth: 100  , renderer: columnsrenderer    },
               { text: 'Technology',                                        datafield: 'technology',                align: 'center',cellalign: 'left',      pinned: true, editable: false  ,maxwidth: 155, minwidth: 100 , renderer: columnsrenderer  },
               { text: 'Installed power <br>[MW]',                          datafield: 'Installed_power',           align: 'center',  cellsalign: 'right', columngroup: 'TD',maxwidth: 155 ,minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'Capacity factor <br>[%]',                           datafield: 'Capacity_factor',           align: 'center',  cellsalign: 'right', columngroup: 'TD',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'Lifetime <br>[years]',                              datafield: 'Lifetime',                  align: 'center',  cellsalign: 'right', columngroup: 'TD',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'Construction time <br>[years]',                     datafield: 'Construction_time',         align: 'center',  cellsalign: 'right', columngroup: 'TD',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'CO2 <br>removal factor [%]',                        datafield: 'CO2',                       align: 'center',  cellsalign: 'right', columngroup: 'EF',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'NOX <br>[g/kWh]',                                   datafield: 'NOX',                       align: 'center',  cellsalign: 'right', columngroup: 'EF',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'SO2 <br>[g/kWh]',                                   datafield: 'SO2',                       align: 'center',  cellsalign: 'right', columngroup: 'EF',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'PM <br>[g/kWh]',                                    datafield: 'PM',                        align: 'center',  cellsalign: 'right', columngroup: 'EF',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'Fuel cost <br>['+currency+'/GJ]',                   datafield: 'Fuel_cost',                 align: 'center',  cellsalign: 'right', columngroup: 'CD',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'Investment cost <br>['+currency+'/kW]',             datafield: 'Investment_cost',           align: 'center',  cellsalign: 'right', columngroup: 'CD',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'Operating cost fixed <br>['+currency+'/kW/yr]',     datafield: 'Operating_cost_fixed',      align: 'center',  cellsalign: 'right', columngroup: 'CD',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
               { text: 'Operating cost variable <br>['+currency+'/MWh]',    datafield: 'Operating_cost_variable',   align: 'center',  cellsalign: 'right', columngroup: 'CD',maxwidth: 155, minwidth: 100, cellsformat: 'd2', cellsrenderer: cellsrenderer, renderer: columnsrenderer },
            //    { text: 'Efficiency',              datafield: 'Efficiency',             align: 'center',  cellsalign: 'right' ,minwidth: 100    },
           ],
           columngroups: 
           [
             { text: 'Technical Details', align: 'center', name: 'TD' },
             { text: 'Emisions factors',  align: 'center', name: 'EF' },
             { text: 'Cost data', align: 'center', name: 'CD' }
           ]
       });

       ///CHART
        var daInvestment = new $.jqx.dataAdapter(source, {
            beforeLoadComplete: function (records) {
                // get data records.
                var records = dataAdapter.records;                
                let srcInv = [];
                $.each( years, function( id, year ) {
                    let tmp = {};
                    tmp['year'] = year;
                    $.each( records, function( id, obj ) {
                        if(year == obj['year']){
                            //console.log(obj['technology'], obj[LabelID]);;
                            tmp[obj['technology']] = obj['Investment_cost'];
                        }
                    });
                    srcInv.push(tmp);
                });
                return srcInv;
            },
            loadError: function (jqXHR, status, error) {}
        });

        let chartSettings = getChartSettings(title='', description='', daInvestment, series_elmix_fuels, '');
        $('#jqxchart_daTechs').jqxChart(chartSettings);

        // SAVE event GRID
        $("#btnSave").on('click', function(e){
            e.preventDefault();
            let data = dataAdapter.records;     
            var inputFields = ['year', 'technology', 'Installed_power', 'Capacity_factor', 'Load_factor', 'Lifetime','Construction_time', 'CO2', "NOX", "SO2","PM", "Other", "Fuel_cost", "Investment_cost", "Operating_cost_fixed","Operating_cost_variable", "Efficiency"];
            var daMain = JSON.stringify(data, inputFields);
            let Data = JSON.parse(daMain);
            $.each(Data, function (id, obj) {
                $.each(obj, function (name, val) {
                    if(val ==''){
                        //console.log(name)
                        Data[id][name] = 0;
                    }
                   
                })
            });
           
            $.ajax({
                url: "app/TechData/TechData.php",
                dataType: 'json',
                type: 'POST',
                data:{casename:casename, action:'saveTech', data: JSON.stringify(Data)},
                async:true,
                complete: function(e) {
                    var serverResponce = e.responseJSON;
                    switch (serverResponce["type"]) {
                        case 'ERROR':
                            ShowErrorMessage(serverResponce["msg"]);
                            break;
                        case 'SUCCESS':
                            $('#jqxNotification').jqxNotification('closeLast'); 
                            ShowInfoMessage(serverResponce["msg"]);
                            $("#msgBasic").html("Data have been saved.").removeClass("jqx-validator-info-label").addClass("jqx-validator-success-label").show();
                            localStorage.setItem("P1",  "changed");
                            break;
                    }  
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    ShowErrorMessage(errorThrown);
                }
            }); 
        });

        //Filter event
        // $("#jqxgrid_daTechs").on("filter", function (event) {
        //     var filterinfo = $("#jqxgrid_daTechs").jqxGrid('getfilterinformation');
        //     console.log(filterinfo);
        // });   

        var srcTechs = [
            {"LabelName": "Installed power", "LabelID": "Installed_power"},
            {"LabelName": "Capacity_factor", "LabelID": "Capacity_factor"},
            {"LabelName": "Lifetime", "LabelID": "Lifetime"},
            {"LabelName": "Construction time", "LabelID": "Construction_time"},

            {"LabelName": "Investment cost", "LabelID": "Investment_cost"},
            {"LabelName": "Fuel cost", "LabelID": "Fuel_cost"},

            {"LabelName": "Operating cost fixed", "LabelID": "Operating_cost_fixed"},
            {"LabelName": "Operating cost variable", "LabelID": "Operating_cost_variable"},
            {"LabelName": "PM", "LabelID": "PM"},
            {"LabelName": "SO2", "LabelID": "SO2"},
            {"LabelName": "NOX", "LabelID": "NOX"}
        ];

        $("#ddlTech").jqxDropDownList({
            selectedIndex: 0,
            source: srcTechs,
            displayMember: "LabelName",
            valueMember: "LabelID",
            height: 19,
            width: 220,
            autoDropDownHeight: true,
            theme: theme
        });

        $("#ddlTech").jqxDropDownList('selectItem', 'Investment_cost' ); 

        $('#ddlTech').on('change', function (event) {     
            var args = event.args;
            if (args) {
                // index represents the item's index.                      
                var index = args.index;
                var item = args.item;
                // get item's label and value.
                var label = item.label;
                var value = item.value;
                var type = args.type; // keyboard, mouse or null depending on how the item was selected.
                var rows = $('#jqxgrid_daTechs').jqxGrid('getrows');
                let src = [];
                $.each( years, function( id, year ) {
                    let tmp = {};
                    tmp['year'] = year;
                    $.each( rows, function( id, obj ) {
                        if(year == obj['year']){
                            //console.log(obj['technology'], obj[value]);;
                            tmp[obj['technology']] = obj[value];
                        }
                    });
                    src.push(tmp);
                });
                var chart = $('#jqxchart_daTechs').jqxChart('getInstance');
                chart.source = src;
                chart.update();  
            } 
        });

        $("#showLog").click(function (e) {
            e.preventDefault();
            $('#log').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.TECH.title}</h4>
                    ${DEF.TECH.definition}
                </div>
            `);
            $('#log').toggle('slow');
        });

        var chart1 = $('#jqxchart_daTechs').jqxChart('getInstance');
        
        $(".toggleLabels").on('click', function (e) {
            e.preventDefault();
            chart1.seriesGroups[0].labels.visible = !chart1.seriesGroups[0].labels.visible;
            chart1.update();    
        });
        
        $(".switchChart").on('click', function (e) {
            e.preventDefault();
           
            var chartType = $(this).attr('data-chartType');
            var chartId = $(this).attr('id-chart');
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

        //GROUP event
        $("#jqxgrid_daTechs").on("groupschanged", function (event) {
            // event arguments.
            var args = event.args;
            // type of change. Possible values: Add, Remove, Clear, Insert
            var type = args.type;
            // group index. The index of the added, removed or inserted group. If the type is "Clear", -1 is passed.
            var groupIndex = args.index;
            // groups array.
            var groups = args.groups;
            //console.log(type, groupIndex, groups)
        });  

        //cellvaluechanged
        $("#jqxgrid_daTechs").on('cellvaluechanged', function (event) {
            $("#msgBasic").html("Data have been changed.").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
        });

        //exxcel export
        $("#xlsAll").click(function (e) {
            e.preventDefault();
            $("#jqxgrid_daTechs").jqxGrid('exportdata', 'xls', 'Technology, environment and finance data');
        });

        let res=true;
        $("#resizeColumns").click(function () {
            if(res){
                $('#jqxgrid_daTechs').jqxGrid('autoresizecolumn', 'year');
            }
            else{
                $('#jqxgrid_daTechs').jqxGrid('autoresizecolumns');
            }
            res = !res;        
        });

   } else{
        $("#cName").text("Please select a case study!");
   }
   $('#loadermain').hide();  
}); 