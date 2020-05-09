var theme='bootstrap';
function getDispatch(casename, user, year) {
    var result="";
    $.ajax({
        //url:'xml/' + user + '/' +casename+'/hSimulation/dispatch.json',
        url:'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
        async: false,  
        cache: false,
        success:function(data) {
            result =  data['DISPATCH'][year]; 
        }
    });
    return result;
} 
function genDataJSON(casename, user) {
    var result="";
    $.ajax({
        url:'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
        async: false,  
        cache: false,
        success:function(data) {
            result =  data; 
        }
    });
    return result;
} 
// //provjerava da li postoji results json
// function FileExists(file, user, casename) {
//     var  check = 0;
//     $.ajax({
//         url:'xml/'+ user + '/'+ casename+'/hSimulation/'+file,
//         type:'HEAD',
//         async: false,
//         error: function() {
//         check = 0;
//         },
//         success: function() {
//         check = 1;
//         }
//     });
//     return check;
// }
function patternChangedMsg(){
    $("#patternChanged").html("<b class='esst'> Phase 2  </b>Hourly patterns have been changed, please run simulation (Calculate)!").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
    $("#hInfo").slideDown(500);
}
function etaChangedMsg(){
    $("#etaChanged").html("Efficiencies have been changed, please run simulation (Calculate)!").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
    $("#hInfo").slideDown(500);
}
function cfChangedMsg(){
    $("#etaChanged").html("<b class='esst' >Phase 1  </b>Capacity factor in phase 1 is changed, please syncronize and run simulation (Calculate)!").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
    $("#hInfo").slideDown(500);
}
function mdChangedMsg(){
    $("#mdChanged").html(" <b class='esst'> Phase 2  </b>Maintenance duration, size or FOR have been changed, please run simulation (Calculate)!").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
    $("#hInfo").slideDown(500);
}
function stgChangedMsg(){
    $("#stgChanged").html(" <b class='esst'> Phase 2  </b>Storage parametetrs have been changed, please run simulation (Calculate)!").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
    $("#hInfo").slideDown(500);
}
function dispatchChangedMsg(){
    $("#dispatchChanged").html(" <b class='esst'> Phase 2  </b> Dispatch order have been changed, please run simulation (Calculate)!").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
    $("#hInfo").slideDown(500);
}
function syncChangedMsg(){
    $("#syncChanged").html("<b class='esst'> Phase 2  </b>Syncronization has been done, please run simulation (Calculate)! <b class='danger'>Syncronization process reset your dispatch order!</b>").removeClass("jqx-validator-success-label").addClass("jqx-validator-info-label").show();
    $("#hInfo").slideDown(500);
}
function initCalcMsg(){
    $("#initCalc").html("Run initial simulation (Calculate)!").removeClass("jqx-validator-success-label").addClass("jqx-validator-warning-label").show();
    $("#hInfo").slideDown(500);
}
function initPatternMsg(){
    $("#initPattern").html("For hourly simulation you need to input hourly patterns for solar, wind, hydro and demand.").removeClass("jqx-validator-success-label").addClass("jqx-validator-warning-label").show();
    $("#hInfo").slideDown(500);
}
function phase1ChangedMsg(){
    $("#phase1Changed").html("<b class='red'> Phase 1  </b>User input changes detected, please syncronize first and then run simulation (Calculate)!").removeClass("jqx-validator-info-label").addClass("jqx-validator-danger-label").show();
    $("#hInfo").slideDown(500);
}
function validationChangedMsg(msg){
    $("#validationChanged").html("<b class='red'> Validation input Phase 1  </b>" + msg).removeClass("jqx-validator-info-label").addClass("jqx-validator-danger-label").show();
    $("#hInfo").slideDown(500);
}
function clearMsg(){

    $("#hInfo").hide();

    $("#initCalc").hide();   
    $("#initPattern").hide();
 
    $("#etaChanged").hide();   
    $("#cfChanged").hide();   
    $("#mdChanged").hide();
    $("#stgChanged").hide();
    $("#patternChanged").hide();

    $("#dispatchChanged").hide();

    $("#syncChanged").hide();
    $("#validationChanged").hide();
}
function getInfoData(user, casename){
   clearMsg();
   $(".noPattern").removeClass("disableForm");
   $(".noCalc").removeClass("disableForm");
   
    var infoData = [];
    // var HourlyData = FileExists('HDP.json', user, casename);
    // var ResultData = FileExists('RHDcp.json', user, casename);
    var HourlyData = FileExists('HGp.json', user, casename);
    var ResultData = FileExists('RHDcp.json', user, casename);

    if (!HourlyData){
        infoData['HourlyData'] = 0;
        initPatternMsg()
        $(".noPattern").addClass("disableForm");
        $(".noCalc").addClass("disableForm");
    }
    else{
        infoData['HourlyData'] = 1;
    }
    if (!ResultData){
        infoData['ResultData'] = 0;
        initCalcMsg();
        $(".noCalc").addClass("disableForm");
    }
    else{
        infoData['ResultData'] = 1;
        // $("#rptByYears").show();
        // $("#Stats").show();
    }

    let pattern = localStorage.getItem("pattern");
    let eta = localStorage.getItem("eta");
    let md = localStorage.getItem("md");
    let dispatch = localStorage.getItem("dispatch");
    let sync = localStorage.getItem("sync");

    let p1 = localStorage.getItem("P1");

    if(pattern=="changed"){
        patternChangedMsg();
    }
    if(eta=="changed"){
        etaChangedMsg()
    }
    if(md=="changed"){
        mdChangedMsg();
    }
    if(dispatch=="changed"){
        dispatchChangedMsg();
    }
    if(sync=="changed"){
        syncChangedMsg();
    }
    if(p1=="changed" && HourlyData){
        phase1ChangedMsg();
        $('#btnCalc').addClass('disableForm');
    }
    // if(cs=="changed"){
    //     caseChangedMsg();
    // }
    return infoData;
}
function initPageTitle(casename){
    $(".page-header p").text(case_name);
    $(".page-header #casename").text(casename);
    $(".page-header  #page").text(hourly_analysis);
    $(".page-header #page1").text("Display data");
}
function initDisplayData(user, casename){
    //console.log('korisnik za inti display '+ user);
    $('.page_title').css('visibility', 'visible');

    $("#gridExcellExport").click(function () {
        $("#jqxGrid").jqxGrid('exportdata', 'xls', 'jqxGrid');           
    });

    //pokupi izabrane vrijednosti iz ddl-ova            
    var chartSelection = $("#ddlTech").jqxDropDownList('getSelectedItem').value;
    var label = $("#ddlTech").jqxDropDownList('getSelectedItem').label;
    var year = $("#ddlYears").jqxDropDownList('getSelectedItem').value;
    var index = $("#ddlYears").jqxDropDownList('getSelectedItem').index;

    $("#ddlTech").on('change', function (event){     
        //console.log(event);
        var item = event.args.item;
        chartSelection = item.value;
        label = item.label;
    });
    $("#ddlYears").on('change', function (event){     
         var item = event.args.item;
         year = item.value;
         index = item.index;
    });  
        
    //inicijalizacije nested grid
    var grid = new Grid(casename, user);   
    var daGrid = new $.jqx.dataAdapter(grid.srcGrid);
	daGrid.dataBind();
	var recordi = daGrid.records;
    $('#jqxGrid').jqxGrid(grid.GridSetting(daGrid, recordi)); 
    
    //inicijalizacija grafa
    var chart = new Chart(chartSelection, year, casename, user);
    var seriesDispatchTech = chart.getseriesDispatchTech();
    var daChart = new $.jqx.dataAdapter(chart.srcChart, { async: true, autoBind: true, loadComplete: function () { $('#jqxLoader').jqxLoader('close'); },loadError: function (xhr, status, error) { alert('Error loading "' + source.url + '" : ' + error); } });            
            
    //zavisi koji setting za chart uzimamo
    // if(chartSelection!='MHG'&&chartSelection!='DHG' &&chartSelection!='HM' &&chartSelection!='TAIC' &&chartSelection!='MCoS'){
    //     $('#jqxChart').jqxChart(chart.ChartSettingSingle(daChart,chartSelection, year, label));
    // }
    // else{
    //     $('#jqxChart').jqxChart(chart.ChartSettingMultiple(daChart,chartSelection,year, label,seriesDispatchTech));
    // }

    $('#jqxChart').jqxChart(chart.ChartSettingSingle(daChart,chartSelection, year, label));

    //refresh chart
    $('#btnChartGenerate').on('click', function() {
        //$('.loadermainLink').show();
        var chartInstance = $('#jqxChart').jqxChart('getInstance');
        chart.Refresh(daChart,chartSelection, year, label, index, chartInstance);
    }); 
}
function HDataGridValidation(dataAdapter){
    var errorType = [];
    var hours = [];
    $("#jqxHDataGrid").jqxGrid('hidevalidationpopups');
    var year = $("#ddlYearsHD").jqxDropDownList('getSelectedItem').value;
    $.each( dataAdapter, function(i, element ) {
        $.each( ['solar', 'wind', 'hydro', 'demand'], function( j, tech ) {
            if(element[tech] < 0){
                var error = new Object();
                error['Year'] = year;
                error['Value'] = element[tech];
                error['Hour'] = element['hour'];
                error['Column'] = tech;
                error['Type'] = 'Value must be positive number';
                error['rowid'] = element['uid'];
                errorType.push(error);
                hours.push(element['hour']);
            }
        });
    });
    return hours
}
function saveHData(allYears, yearsArr){
    $('.loadermainLink').show();
    //$("#btnSaveEta").on('click', function(e){
    //$("#btnSaveEta").unbind().click(function() {
  
    var HData = $('#jqxHDataGrid').jqxGrid('getrows');
    var daHData = JSON.stringify(HData, ['Hour', 'Demand', 'Solar', 'Wind', 'Hydro' ]);
    var year = $("#ddlYearsHD").jqxDropDownList('getSelectedItem').value;
    var errorType = HDataGridValidation(HData);

    if(Object.keys(errorType).length === 0){
        $.ajax({
            url: 'app/hSimulation/HourlyAnalysis.php',
            dataType: 'json',
            type: 'POST',
            data: {data: daHData, year:year,allYears:allYears,yearsArr:yearsArr, action:'saveHData' },
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
                        $('#jqxNotification').jqxNotification('closeLast'); 
                        //ShowInfoMessage(serverResponce["msg"]);
                        localStorage.setItem("pattern",  "changed");
                        $(".noPattern").removeClass("disableForm");
                        patternChangedMsg();
                        $("#initPattern").hide();
                        if(!allYears){
                            $("#msgBasic").html("Data is saved for year <b>" + year + "</b>!").removeClass("jqx-validator-error-label").addClass("jqx-validator-success-label").show();
                        }else{
                            $("#msgBasic").html("Data is saved for all years!").removeClass("jqx-validator-error-label").addClass("jqx-validator-success-label").show();
                        }
                        
                        $('.loadermainLink').hide();
                        break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
            }
        }); 
    }
    else{
        $("#msgBasic").html("Values in <span class='esst'>" + errorType +"</span> are not positive, data is not saved!").removeClass("jqx-validator-success-label").addClass("jqx-validator-error-label").show();
        // for (var i = 0; i < errorType.length; i++) {
        //     $("#jqxHDataGrid").jqxGrid('showvalidationpopup', errorType[i]['rowid'], errorType[i]['Column'], errorType[i]['Type']);
        // }
    }
}
function FORMOR(user, casename){

    $.ajax({
        url:'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
        async: true,  
        cache: false,
        success:function(data){
            let DATA = {};
            DATA['MUS'] = data['MUS'];
            DATA['MDId'] = data['MD'];
            DATA['FOR'] = data['FOR']; 
            //console.log(MUS);
            init(DATA);
        }
    });

    function init(DATA){
        let srcInv = [];
        $.each( DATA['MUS'], function( name, value ) {
            let tmp = {};
            //console.log(obj['technology'], obj[LabelID]);;
            tmp['Tech'] = name;
            tmp['MUS'] = value;
            tmp['MDId'] = DATA['MDId'][name];
            tmp['FOR'] = DATA['FOR'][name];
            srcInv.push(tmp);
        });

        let duration= [
            {"id":0, "name":"No maintenance"},
            {"id":2, "name":"2 weeks"},
            {"id":4, "name":"4 weeks"}
        ]
        var ddlSource = {
            localdata: JSON.stringify(duration),
            datatype: "json",
            datafields:
            [
                { name: 'id', type: 'number' },
                { name: 'name', type: 'string' }
            ],
        };
        var daFuels = new $.jqx.dataAdapter(ddlSource, {autoBind: true});
        
        var src ={
            datatype: 'json',
            datafields: [
                // name - determines the field's name.
                // value - the field's value in the data source.
                // values - specifies the field's values.
                // values.source - specifies the foreign source. The expected value is an array.
                // values.value - specifies the field's value in the foreign source. 
                // values.name - specifies the field's name in the foreign source. 
                // When the adapter is loaded, each record will have a field called "Country". The "Country" for each record comes from the countriesAdapter where the record's "countryCode" from gridAdapter matches to the "value" from countriesAdapter. 
                { name: 'Tech', type: 'string'},
                { name: 'MUS', type: 'number'},
                { name: 'FOR', type: 'number'},
                { name: 'MD', value: 'MDId', values: { source: daFuels.records, value: 'id', name: 'name' } },
                { name: 'MDId', type: 'number'}
                // { name: 'Country', value: 'countryCode', values: { source: countriesAdapter.records, value: 'value', name: 'label' } },
                // { name: 'countryCode', type: 'string'}
               
            ],
            localdata:srcInv
        };  

        var dataAdapter = new $.jqx.dataAdapter(src);

        var ddlEditor = function(row, value, editor) {
            editor.jqxDropDownList({ source: daFuels, displayMember: 'name', valueMember: 'id' });
        }
        //dataAdapter.dataBind();
        $("#jqxGridFORMOR").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter,
            editable: true,
            // showstatusbar: true,
            // showaggregates: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: [
              { text: 'Technology', datafield: 'Tech', width: 120, pinned: true, align: 'left', },
              { text: 'Unit size [MW]', datafield: 'MUS', cellsalign: 'right',cellsformat: 'd2', align: 'right', columntype: 'numberinput',},
              { text: 'Maintenance duration [Weeks]', datafield: 'MDId',  displayfield: 'MD', cellsalign: 'right', align: 'right', columntype: 'dropdownlist',  createeditor: ddlEditor},
              { text: 'Forced outage rate [%]', datafield: 'FOR', cellsalign: 'right',cellsformat: 'd2', align: 'right', columntype: 'numberinput',},
            ] 
        
    });
    }
}
function saveFORMOR() {
    $('#loadermain').show(); 

    var data  = $('#jqxGridFORMOR').jqxGrid('getrows');

    FORMORData = {};
    FORMORData['MUS'] = {};
    FORMORData['MD'] = {};
    FORMORData['FOR'] = {};
    $.each( data, function( id, obj ) {
        FORMORData['MUS'][obj['Tech']] = obj['MUS'];
        FORMORData['MD'][obj['Tech']] = obj['MDId'];
        FORMORData['FOR'][obj['Tech']] = obj['FOR'];
    });

    var url='app/hSimulation/HourlyAnalysis.php';
    $.ajax({
           type: "POST",
           url: url,
           dataType: 'json',
           data: {data: JSON.stringify(FORMORData), action:'saveFORMOR' },
           async:true,
           complete: function(e) {
                $('#loadermain h4').text('');
                $('#loadermain').hide(); 
                var serverResponce = e.responseJSON;
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        break;
                    case 'SUCCESS': 
                        ShowInfoMessage(serverResponce["msg"]);
                        localStorage.setItem("md",  "changed");
                        mdChangedMsg();
                        $('#FORMOR').modal('toggle');
                        break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
                $('#loadermain').hide(); 
            }
    });
    return false;
}
function Calculate(user, casename, maintenance, storage){
   $('#loadermain h4').text('Calculation in progress, please wait');
   $('#loadermain').show();
    $.ajax({
          method: 'POST',
          url: 'app/hSimulation/HourlyAnalysis.php',
          data: {case:casename, action:'Calculate', maintenance: maintenance, storage: storage },
          async: true,
          cache: false,
          dataType: 'json',
          complete: function(e) {
            $('#loadermain h4').text('');
            $('#loadermain').hide();
            var serverResponce = e.responseJSON;
            switch (serverResponce.type) {
                case 'ERROR':
                    ShowErrorMessage(serverResponce.msg);
                    break;
                case 'EXIST':
                    ShowWarningMessage(serverResponce.msg);
                    break;
                case 'SUCCESS':                    
                    clearLocalStorageMsgP2();
                    // localStorage.setItem("eta",  null);
                    // localStorage.setItem("md",  null);
                    // localStorage.setItem("dispatch",  null);                      
                    // localStorage.setItem("pattern",  null);
                    // localStorage.setItem("cf",  null);
                    getInfoData(user, casename);                 
                    initDisplayData(user, casename);  
                    $("#jqxNotification").jqxNotification("closeAll");
                    ShowSuccessMessage(serverResponce.msg, true);                   
                    break;
                case 'WARNING':
                    ShowDefaultMessage( serverResponce.msg, true);
                    break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
            }
    });  
}
function adjustCF(user, casename,  storage, maintenance){
    $('#loadermain h4').text("Adjusting CF in accordance with hourly pattern..." );
    $('#loadermain').show();
    $.ajax({
          method: 'POST',
          url: 'app/hSimulation/HourlyAnalysis.php',
          data: {case:casename, action:'AdjustCF', maintenance: maintenance, storage: storage },
          async: true,
          cache: false,
          dataType: 'json',
          complete: function(e) {
            $('#loadermain h4').text('');
            $('#loadermain').hide();
            var serverResponce = e.responseJSON;
            switch (serverResponce.type) {
                case 'ERROR':
                    ShowErrorMessage(serverResponce.msg);
                    break;
                case 'EXIST':
                    ShowWarningMessage(serverResponce.msg);
                    break;
                case 'SUCCESS':
                    //getInfoData(user, casename);                      
                    //initDisplayData(user, casename);  
                    localStorage.setItem("P1",  "changed");
                    phase1ChangedMsg();                      
                    $("#jqxNotification").jqxNotification("closeAll");;
                    ShowSuccessMessage(serverResponce.msg, true);
                    break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
            }
    });  
}
function checkInputPhase1(){
    $('#loadermain h4').text("Checking inputs from phase 1!" );
    $('#loadermain').show();
    $.ajax({
          method: 'POST',
          url: 'app/hSimulation/HourlyAnalysis.php',
          data: {action:'CheckInputPhase1' },
          async: true,
          cache: false,
          dataType: 'json',
          complete: function(e) {
            $('#loadermain h4').text('');
            $('#loadermain').hide();
            var serverResponce = e.responseJSON;
            switch (serverResponce.type) {
                case 'ERROR':
                    ShowErrorMessage(serverResponce.msg);
                    break;
                case 'EXIST':
                    ShowWarningMessage(serverResponce.msg);
                    break;
                case 'SUCCESS':                        
                    $("#jqxNotification").jqxNotification("closeAll");;
                    ShowSuccessMessage(serverResponce.msg, true);
                    break;
                case 'WARNING':
                    validationChangedMsg(serverResponce.msg)
                    //ShowDefaultMessage( serverResponce.msg, true);
                    break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
            }
    });  
}
function sync(){
    $('#loadermain h4').text("Syncing changes from phase 1!" );
    $('#loadermain').show();
    $.ajax({
          method: 'POST',
          url: 'app/hSimulation/HourlyAnalysis.php',
          data: {action:'sync' },
          async: true,
          cache: false,
          dataType: 'json',
          complete: function(e) {
            $('#loadermain h4').text('');
            $('#loadermain').hide();
            var serverResponce = e.responseJSON;
            switch (serverResponce.type) {
                case 'ERROR':
                    ShowErrorMessage(serverResponce.msg);
                    break;
                case 'EXIST':
                    ShowWarningMessage(serverResponce.msg);
                    break;
                case 'SUCCESS':                        
                    $("#jqxNotification").jqxNotification("closeAll");
                    ShowSuccessMessage(serverResponce.msg, true);
                    localStorage.setItem("P1",  null);
                    $("#phase1Changed").hide();
                    //$("#phase1Changed").empty().removeClass("jqx-validator-danger-label");
                    localStorage.setItem("sync",  "changed");
                    syncChangedMsg();
                    $('#btnCalc').removeClass('disableForm');
                    break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
            }
    });  
}
function saveDispatch(chbDispatch) {
    $('#loadermain h4').text("Changing dispatch order" )
    $('#loadermain').show(); 
    var url='app/hSimulation/HourlyAnalysis.php';
    //let dispatchOrder = $("#sortable").jqxSortable("serialize");
    let year = $("#ddlYearsDisp").jqxDropDownList('getSelectedItem').value;

    //  let sort = $('#sortable').jqxSortable('toArray'); 
    $.ajax({
           type: "POST",
           url: url,
           dataType: 'json',
           data: $("#sortable").jqxSortable("serialize") + '&action=saveDispatch&year='+year+'&chbDispatch='+chbDispatch, 
           //data: {action:'saveDispatch', year: year, dispatchOrder: dispatchOrder },
           async:true,
           complete: function(e) {
                $('#loadermain h4').text('');
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
                        ShowInfoMessage(serverResponce["msg"]);
                        localStorage.setItem("dispatch",  "changed");
                        dispatchChangedMsg();
                        if(chbDispatch){
                            $('#WinDispatchOrder').modal('toggle');
                        }
                        
                        break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
                $('#loadermain').hide(); 
            }
    });
    return false;
}
function showDispatch(user, casename, daYears, currency) {
    $('#minLoader_dis').show();

    let MCoS;
    let IMPORT;
    $.ajax({
        method: 'GET',
        // url: 'xml/'+ user + '/'+casename+'/hSimulation/MCoS.json',
        url: 'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
        dataType: 'json',
        async:true,
        cache:false,
        complete: function(e) {
            data = e.responseJSON
            MCoS = data['MCoT']; 
            genData = genDataJSON(casename, user);
            IMPORT = genData['elImportMW'];
            renderDispatch(casename, user, year, MCoS, IMPORT);
            $('#minLoader_dis').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            ShowErrorMessage(errorThrown);
            $('#minLoader_dis').hide(); 
        }
    });

    $("#ddlYearsDisp").jqxDropDownList({ 
        source: daYears, 
        selectedIndex: 0, 
        theme: theme, 
        displayMember: 'year',  
        height: 16,
        width:75,
        autoDropDownHeight: true
    });
    year = $("#ddlYearsDisp").jqxDropDownList('getSelectedItem').value;

   // let MCoS = getMCoS();
    
    $("#ddlYearsDisp").on('change', function (event){  
        $('#minLoader_dis').show();  
        var item = event.args.item;
        year = item.value;
        renderDispatch(casename, user, year, MCoS, IMPORT);
    }); 

    function renderDispatch(casename, user, year, MCoS, IMPORT){
        fuels = getDispatch(casename, user, year); 
        // console.log(TECHS);
        // fuels = TECHS[year];
        // console.log(fuels);
        //postavi Storage na kraj
        fuels.push(fuels.splice(fuels.indexOf('Storage'), 1)[0]);
        importexport = IMPORT[year];
        // console.log('fuels1 ', fuels);
        // console.log('index ipot ', fuels.indexOf('ImportExport'))
        if (importexport < 0 ){
            fuels.unshift(fuels.splice(fuels.indexOf('ImportExport'), 1)[0]);
            //console.log('fuels ', fuels);
        }
        // else if(importexport == 0){
        //     fuels.push(fuels.splice(fuels.indexOf('ImportExport'), 1)[0]);
        // }
        // else if(importexport == 0){
        //     fuels.push(fuels.splice(fuels.indexOf('ImportExport'), 1)[0]);
        // }
        //console.log(fuels);
        var sortableList = '';
        for (var i = 0; i < fuels.length; i++) {

            if( MCoS[year][fuels[i]] == undefined && importexport < 0 && fuels[i] == 'ImportExport' ){
                name = 'Export';
                value = '-';
                tech = fuels[i];
                //addEl(tech, name, value);
                addEmpty(tech, name, value)
            }
            else if ( MCoS[year][fuels[i]] == undefined && importexport > 0 && fuels[i] == 'ImportExport' )
            {
                name = 'Import';
                value = '-';
                tech = fuels[i];
                addEl(tech, name, value)
            }
            else if ( MCoS[year][fuels[i]] == undefined && importexport == 0 && fuels[i] == 'ImportExport' )
            {
                name = '';
                value = '';
                tech = fuels[i];
                addEmpty(tech, name, value)
            }
            else if (fuels[i] == 'Storage' )
            {
                name = '';
                value = '';
                tech = fuels[i];
                addEmpty(tech, name, value)
            }
            else if ( MCoS[year][fuels[i]] == undefined  )
            {
                value = '-';
                name = fuels[i];
                tech = fuels[i];
                addEl(tech, name, value)
            }
            else            {
                //console.log(MCoS[year][fuels[i]])
                value = parseFloat( MCoS[year][fuels[i]]).toFixed(2);
                name = fuels[i];
                tech = fuels[i];
                addEl(tech, name, value)
            }
            //console.log(value)
            //console.log(MCoS[year][fuels[i]]);
            //let value = parseFloat(.replace(/,/g, '')).toFixed(2);
            function addEl(tech, name, value){
                var sortableElement =   
                `<div class="sortable-item" id=`+`sort_`+tech+`>
                    <i class="fa fa-sort orange fa-1.3x" aria-hidden="true"></i>` + name +`
                    <span class="pull-right"><i class="fa fa-info-circle blue " aria-hidden="true"></i>`+value+` [`+currency+`/MWh]</span>
                </div>`;
                sortableList = sortableList + sortableElement;
            }
            function addEmpty(tech, name, value){
                var sortableElementEmpty =   
                `<div class="sortable-item" id=`+`sort_`+tech+` style="display:none"></div>`;

                sortableList = sortableList + sortableElementEmpty;
            }

        }

        $("#sortable").html(sortableList);
        $("#sortable").jqxSortable(); 
        $('#minLoader_dis').hide(); 
    }
}
function Grid(casename,user){

    this.myURL2 = 'xml/'+ user + '/'+casename+'/hSimulation/OutputDetails.json',
    this.srcGrid = {
        datafields: [
            { name: 'year', type: 'int' },
            { name: 'ED', type: 'decimal' },
            { name: 'LF', type: 'decimal' },
            { name: 'PMAX', type: 'decimal' },
			{ name: 'UD', type: 'decimal' },
			{ name: 'maxUD', type: 'decimal' },
			{ name: 'countUD', type: 'int' },
			{ name: 'technologies', type: 'json' },
        ],
        datatype: 'json',
        //url: 'data.json',
		url: this.myURL2,
		async: false,
        cache: false,
    };
    //this.nestedGrid = null;
    this.GridSetting = function(daGrid,recordi){
        var initrowdetails = function (index, parentElement, gridElement, record) {
            var id = record.uid.toString();
            var grid = $($(parentElement).children()[0]);
			var nestedSource =
            {
                datafields: [
                    { name: 'tech', map: 'tech', type: 'string' },
                    { name: 'InstalledPower', map: 'InstalledPower', type: 'decimal' },
                    { name: 'TotalGeneration', map: 'TotalGeneration', type: 'decimal' },                          
                    { name: 'CF', map: 'CF', type: 'decimal' },
                    { name: 'CF2', map: 'CF2', type: 'decimal' },
                    { name: 'RCF', map: 'RCF', type: 'decimal' },
					{ name: 'CG', map: 'CG', type: 'decimal' },
					{ name: 'maxCHG', map: 'maxCHG', type: 'decimal' },
					{ name: 'countCHG', map: 'countCHG', type: 'int' },
                    { name: 'UnitSize', map: 'UnitSize', type: 'decimal' },
					{ name: 'UnitNumber', map: 'UnitNumber', type: 'int' },
					{ name: 'NotMaintenaned', map: 'NotMaintenaned', type: 'int' },
                    { name: 'PowerNotMaintenaned', map: 'PowerNotMaintenaned', type: 'decimal' }
                ],
                datatype: 'json',
                root: 'technology',
                localdata: recordi[index]
            };  
                
            var columnsrenderer = function (value) {
                return '<div style="text-align: center; margin-top: 12px; word-wrap:normal;white-space:normal;">' + value + '</div>';
            }
                
            var cellsrenderer1 = function (row, columnfield, value, defaulthtml, columnproperties) {

                var rowData = grid.jqxGrid('getrowdata', row);
                if (rowData.CF.toFixed(2) != rowData.CF2.toFixed(2)) {
                    value = $.jqx.dataFormat.formatnumber(value, 'd2');
                    return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';"><i class="fa fa-exclamation-triangle red" aria-hidden="true"></i>' + value + '</span>';
               }
            }
            
            var cellsrenderer2 = function (row, columnfield, value, defaulthtml, columnproperties) {
                var rowData = grid.jqxGrid('getrowdata', row);
                //color:#d9534f;
                if (parseFloat(rowData.CF2) < parseFloat(rowData.RCF)) {
                    value = $.jqx.dataFormat.formatnumber(value, 'd2');
                    return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';">' + value + '&nbsp;&nbsp; <i class="fa fa-exclamation-triangle red" aria-hidden="true"></i></span>';
                }
                if (parseFloat(rowData.CF2) > parseFloat(rowData.RCF)) {
                    //color:#4080bf;
                    value = $.jqx.dataFormat.formatnumber(value, 'd2');
                    return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';">' + value + '&nbsp;&nbsp; <i class="fa fa-exclamation-triangle esst" aria-hidden="true"></i></span>';
                }
            }
                
            var cellsrenderer3 = function (row, columnfield, value, defaulthtml, columnproperties) {
               // color:#e6ac00;
                if (value.toFixed(2)>0) {
                     value = $.jqx.dataFormat.formatnumber(value, 'd2');
                     return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + ';">' + value + '&nbsp;&nbsp; <i class="fa fa-exclamation-triangle green" aria-hidden="true"></i></span>';
                    //return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + ';">' + value + '</span><img style="margin:2px; margin-left: 10px"  src="css/images/danger16.png">';
                }
            }
            var cellsrenderer4 = function (row, columnfield, value, defaulthtml, columnproperties) {
                if (value.toFixed(2)>0) {
                     value = $.jqx.dataFormat.formatnumber(value, 'd2');
                     return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';">' + value + '  &nbsp;&nbsp; <i class="fa fa-exclamation-triangle red" aria-hidden="true"></i> </span>';
                    //return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + ';">' + value + '</span><img style="margin:2px; margin-left: 10px"  src="css/images/danger16.png">';
                }
            }
				
            var nestedAdapter = new $.jqx.dataAdapter(nestedSource);
            
            if (grid != null) {
                grid.jqxGrid({
                    source: nestedAdapter, 
                    theme: theme, 
                    width: '99%', 
                    height: 300, 
                    columnsheight: 75,
                    selectionmode: 'multiplecellsadvanced',
                    autoheight: true,
                    autorowheight: true,
                    columnsresize:true,
                    columns: [
                        { text: "Technology", datafield: "tech", width: 80,cellsformat: 'd2' , renderer: columnsrenderer, pinned: true},
                        { text: "Installed Power<br>[MW]", datafield: "InstalledPower", cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer},
                        { text: "Total Generation<br>[GWh]", datafield: "TotalGeneration", cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer},                            
                        { text: "Capacity Factor<br>[%]", datafield: "CF", cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer},
                        { text: "CF calculated from hourly patern[%]", datafield: "CF2", cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer,cellsrenderer: cellsrenderer1},
                        { text: "CF in relation to dispatch order<br>[%]", datafield: "RCF", cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer,cellsrenderer: cellsrenderer2},
						{ text: "Curtailment/<br>Underutilisation<br>[GWh]", datafield: "CG",cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer,cellsrenderer: cellsrenderer3},
						{ text: "Max Curtailment/<br>Underutilisation<br>[MW]", datafield: "maxCHG", cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer},
						{ text: "Count Curtailment/<br>Underutilisation<br>[hours]", datafield: "countCHG", cellsalign: 'right', renderer: columnsrenderer},
                        { text: "Unit size/<br>Maintenance<br>[MW]", datafield: "UnitSize",cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer,},
						{ text: "Number of units<br>Maintenance", datafield: "UnitNumber", cellsalign: 'right', renderer: columnsrenderer},
						{ text: "Number of units<br>not maintenaned", datafield: "NotMaintenaned", cellsalign: 'right', renderer: columnsrenderer},
                        { text: "Power<br>not maintenaned<br>[MW]", datafield: "PowerNotMaintenaned", cellsalign: 'right', renderer: columnsrenderer,cellsrenderer: cellsrenderer4}
                   ]
                });
            }
            //this.nestedGrid = grid;
        }

        // $("#xlsNested").click(function () {
        //     var data = new Array();
        //     nestedRows = this.nestedGrid.jqxGrid('getrows');
        //     $.each(nestedRows, function () {
        //         data.push(this);
        //     });
        //     console.log('nested ', this.nestedGrid )
        //     if (this.nestedGrid) {
        //         this.nestedGrid.jqxGrid('exportdata', 'csv', 'doccomparison', true, data);
        //     }         
        // });

        var columnsrenderer = function (value) {
            return '<div style="text-align: center; margin-top: 12px; word-wrap:normal;white-space:normal;">' + value + '</div>';
        }
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {
            if (value.toFixed(2) > 0) {
                value = $.jqx.dataFormat.formatnumber(value, 'd2');
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';">' + value + '&nbsp;&nbsp; <i class="fa fa-exclamation-triangle red" aria-hidden="true"></i></span>';
            }
        }

        var size = Object.keys(recordi[0].technologies.technology).length;
        let rowdetailsheight = 35 * size + 80;

        settings =  {
            width: '100%',
            columnsheight: 55,
            autoheight: true,
            autorowheight: true,
            source: this.srcGrid,
            theme: theme,
            rowdetails: true,
            // rowsheight: 25,
            columnsresize:true,
            initrowdetails: initrowdetails,
            altrows: true,
            selectionmode: 'multiplecellsadvanced',
            rowdetailstemplate: { 
                rowdetails: "<div id='gridNested' style='margin: 10px;'></div>", 
                rowdetailsheight: rowdetailsheight, 
                rowdetailshidden: true 
            },
            /*  ready: function () {
                $("#jqxGrid").jqxGrid('showrowdetails', 1);
            },*/
            columns: [
                  { text: 'Year', datafield: 'year', width: 100},
                  { text: 'Demand<sub>y</sub><br>[GWh]', datafield: 'ED',cellsformat: 'd2',cellsalign: 'right' , renderer: columnsrenderer},
                  { text: 'Load Factor<sub>y</sub><br>[%]', datafield: 'LF', cellsformat: 'd2',cellsalign: 'right', renderer: columnsrenderer },
                  { text: 'Peak Load<sub>y</sub><br>[MW]', datafield: 'PMAX',cellsformat: 'd2',cellsalign: 'right', renderer: columnsrenderer },
				  { text: 'Unserved<sub>y</sub><br>[GWh]', datafield: 'UD',cellsformat: 'd2',cellsalign: 'right', renderer: columnsrenderer,cellsrenderer: cellsrenderer },
				  { text: 'Max Unserved<sub>y</sub><br>[MWh]', datafield: 'maxUD',cellsformat: 'd2',cellsalign: 'right', renderer: columnsrenderer },
				  { text: 'Count Unserved<sub>y</sub><br>[hours]', datafield: 'countUD',cellsalign: 'right', renderer: columnsrenderer }
            ]
        }; 
        return settings;
    }
}
function Chart(chartSelection, year, casename, user) {
    this.tech = chartSelection;
    this.year = year;
    this.casename = casename;

    this.getseriesDispatchTech = function(year=this.year){  
        var disp = getDispatch(casename, user, year);
        seriesDispatchTech = [];
        disp.forEach(function(key) {
            serija ={
                dataField:key,
                lineWidth: 1, lineWidthSelected: 1, 
                opacity: 1, 
                displayText:key,
                color:color_obj[key]
            };
            seriesDispatchTech.push(serija);
        });
        return seriesDispatchTech
    }

    this.myURL2 = 'xml/'+ user + '/'+casename+'/hSimulation/'+chartSelection+'cp.json';
    this.srcChart = {
        url: this.myURL2,
        root: year.toString()+'>HourlyData',
        datatype: 'json',
        cache: false,
    }; 

    //console.log(this.srcChart.root);
    this.ChartSettingSingle = function(daChart,chartSelection, year,label){
        settings={
            title: label+', year '+year,
            description: "",
            padding: {left: 5,top: 5,right: 5,bottom: 5},
            titlePadding: {left: 900, top: 0,right: 10,bottom: 10},
            legendLayout: { left: 45, top: 5, width: '100%', height: 200, flow: 'horizontal' },
            source: daChart,
            enableAnimations: true,
            showLegend: true,
            enableAnimations: true,
            enableCrosshairs: true,
            crosshairsDashStyle: '2,2',
            crosshairsLineWidth: 1.5,
            crosshairsColor: '#2f6483',
            borderLineColor: 'transparent',
            categoryAxis: {
                dataField: 'hour',
                type: 'basic',
                minValue: 2190,
                maxValue: 2920,
                gridLinesInterval: 24, 
                flip: false,
                //valuesOnTicks: true,
                rangeSelector: {
                    serieType: 'line',
                    unitInterval: 730,
                    padding: { /*left: 0, right: 0,*/ top: 10, bottom: 0 },
                    // Uncomment the line below to render the selector in a separate container
                    //renderTo: $('#selectorContainer'),
                    //backgroundColor: "#E1E1E6",
                    size: 75,
                }
            },
        seriesGroups: [{
            type: 'spline',
            series: [{ dataField: 'value',displayText: 'Demand', lineWidth: 2, opacity: 1  , color: clDemand}]
            }]
        };
        return settings;
    };
    
    // this.ChartSettingMultiple = function(daChart, chartSelection, year, label,seriesDispatchTech){
    //     settings={
    //          title: label+", year "+year,
    //             description: "",
    //             enableAnimations: true,
    //             showLegend: true,
    //             animationDuration: 1500,
    //             enableCrosshairs: true,
    //             padding: { left: 5, top: 5, right: 20, bottom: 5 },
    //             source: daChart,
    //             xAxis:
    //                 {
	// 				dataField: 'hour',
    //                 type: 'basic',
    //                     minValue: 2190,
    //                     maxValue: 2920,
    //                     gridLinesInterval: 168, 
    //                     flip: true,
    //                     valuesOnTicks: true,

    //                     rangeSelector: {
    //                         serieType: 'line',
    //                         gridLinesInterval: 168, 
    //                         unitInterval: 730,
    //                         padding: { /*left: 0, right: 0,*/ top: 10, bottom: 0 },
    //                         // Uncomment the line below to render the selector in a separate container
    //                         //renderTo: $('#selectorContainer'),
    //                         backgroundColor: "#E1E1E6",
    //                         size: 90,
    //                         gridLines: {visible: true},
    //                     }
    //                 },
    //             seriesGroups:
    //                 [
    //                     {
    //                         type: 'area',
    //                        // toolTipFormatFunction: toolTipCustomFormatFn,
    //                         series: seriesDispatchTech
    //                     },
    //                     {
    //                         type: 'line',
    //                         series: [{ dataField: 'value',lineWidth: 2, displayText: 'Demand', opacity: 1 , color: clDemand}]
    //                     }
    //                 ]
    //     };
    //     return settings;
    // };
    
    this.Refresh = function(daChart, chartSelection, year, label, index, chartInstance){
            $('#jqxLoader').jqxLoader('open');
            $("#jqxLoader").jqxLoader({text: "Generating "+label+" chart..." });

            //this.srcChart.url = 'phase2/data/'+casename+'/'+tech+'cp.json';
            this.srcChart.url  = 'xml/'+ user + '/'+casename+'/hSimulation/'+chartSelection+'cp.json';
            this.srcChart.root = year.toString()+'>HourlyData';
            daChart.dataBind(this.srcChart, { 
                async: true, 
                autoBind: true, 
                loadComplete: function () { 
                    $('#jqxLoader').jqxLoader('close'); 
                },
                loadError: function (xhr, status, error) { 
                    alert('Error loading "' + source.url + '" : ' + error); 
                } 
            });
            seriesDispatchTech = this.getseriesDispatchTech(year);
            chartInstance.source = daChart;
            chartInstance.title= label+", year "+year;

            switch(true) {
                case (chartSelection == 'RHD'):
                    chartInstance.seriesGroups= [
                        {
                            type: 'spline',
                            series: [{ dataField: 'value',lineWidth: 2, displayText: 'Demand', opacity: 1 , color: clDemand}]
                        }
                    ];
                    chartInstance.update(); 
                break;
                case (chartSelection == 'UD'):
                    chartInstance.seriesGroups= [
                        {
                            type: 'spline',
                            series: [{ dataField: 'value',lineWidth: 2, displayText: 'Unseerved Demand', opacity: 1 , color: clDemand}]
                        }
                    ];
                    chartInstance.update();
                break;
                case (chartSelection == 'MCoSd'):
                    chartInstance.seriesGroups= [
                        {
                            type: 'spline',
                            series: [{ dataField: 'value',lineWidth: 2, displayText: 'Marginal cost duration', opacity: 1 , color: clMarginalCost}]
                        }
                    ];
                    chartInstance.update();
                break;
                case (chartSelection == 'STG'):
                    chartInstance.seriesGroups= [
                        {
                            type: 'stackedarea',
                            series: [{ dataField: 'value',lineWidth: 2, displayText: 'Storage operation', opacity: 1 , color: clStorage}]
                        }
                    ];
                    chartInstance.update();
                break;
                case (chartSelection == 'MCoS'):
                    chartInstance.seriesGroups= [
                        {
                            type: 'spline',
                            series: [{ dataField: 'MCoS_avg',lineWidth: 3, displayText: 'Annual Average Marginal Cost of System', opacity: 1 , color: clDemand},
                            { dataField: 'AUC',lineWidth: 3, displayText: 'Average unit cost', opacity: 1 , color: clStorage},
                            { dataField: 'MCoS',lineWidth: 1, displayText: 'Marginal Cost of System in each hour', opacity: 1 , color: clCoal}]
                        }
                    ];
                    chartInstance.update();
                break;
                case (chartSelection=='MHG' || chartSelection=='DHG' || chartSelection=='HM' || chartSelection=='TAIC'):
                    chartInstance.seriesGroups= [
                        {
                            type: 'stackedarea',
                            series: seriesDispatchTech
                        },
                        {
                            type: 'spline',
                            series: [{ dataField: 'value',lineWidth: 2, displayText: 'Demand', opacity: 1 , color: clDemand}]
                        }
                    ];
                    chartInstance.update(); 
                break;
                case (chartSelection=='CHG'):
                    chartInstance.seriesGroups= [
                        {
                            type: 'stackedarea',
                            series: seriesDispatchTech
                        },
                    ];
                    chartInstance.update();  
                break;
                default:
            }
            $("#jqxGrid").jqxGrid('showrowdetails', index);    
            //$('.loadermainLink').hide();

    };
}
//vrijednosti ya satna opterecenja  HData
function GridHourlyInfo(user, casename, techs, daYears){

    $("#ddlYearsHDout").jqxDropDownList({ 
        source: daYears, 
        selectedIndex: 0, 
        theme: theme, 
        displayMember: 'year',  
        height: 16,
        width:200,
        autoDropDownHeight: true
    });
    year = $("#ddlYearsHDout").jqxDropDownList('getSelectedItem').value;

    $('#HDtitle').html('Hourly data values for demand, wind, solar, hydro, ' + '<span>year: ' + year + '</span>');

    let cellclassname =  function(row, column, value, data) {    
        //console.log(column);
        if (value > 0 && column == "UD"){
            return 'unserved';
        }
        //else if (value > 0 && ( column == "WindCurtail" || column == "SolarCurtail" || column == "HydroCurtail" )){id.substr(id.length - 5)
        else if (value > 0 &&  column.slice(-7) == "Curtail" ){
            return 'curtailed';
        }      
    };

    URLWin  = 'xml/'+ user + '/'+casename+'/hSimulation/HDValues.json';
    source =
    {
        url: URLWin,
        root: year.toString(),
        datatype: 'json',
        async: true, 
        cache: false,
        datafields: [
			{ name: 'Hour',type: 'string'  },
            { name: 'Demand', type: 'number' },
            { name: 'UD', type: 'number' },
			{ name: 'Wind', type: 'number' },
			{ name: 'Solar', type: 'number' },
            { name: 'Hydro', type: 'number' },
            { name: 'Gas', type: 'number' },
			{ name: 'Oil', type: 'number' },
            { name: 'Coal', type: 'number' },
            { name: 'Biofuels', type: 'number' },
            { name: 'Peat', type: 'number' },
            { name: 'Waste', type: 'number' },
            { name: 'OilShale', type: 'number' },
            { name: 'Geothermal', type: 'number' },
            { name: 'Nuclear', type: 'number' },
            { name: 'Storage', type: 'number' },
            { name: 'WindCurtail', type: 'number' },
			{ name: 'SolarCurtail', type: 'number' },
            { name: 'HydroCurtail', type: 'number' },
            { name: 'GasCurtail', type: 'number' },
			{ name: 'OilCurtail', type: 'number' },
            { name: 'CoalCurtail', type: 'number' },
            { name: 'BiofuelsCurtail', type: 'number' },
			{ name: 'PeatCurtail', type: 'number' },
            { name: 'WasteCurtail', type: 'number' },
            { name: 'OilShaleCurtail', type: 'number' },
			{ name: 'GeothermalCurtail', type: 'number' },
            { name: 'NuclearCurtail', type: 'number' },
			{ name: 'StorageCurtail', type: 'number' }
        ],
        totalrecords: 8760
    };   

    var daGridHD = new $.jqx.dataAdapter(source); 

    //console.log(daGridHD);
    let brojKolona = techs.length;
    var widthLength = 100 / (2*(brojKolona-1)  + 3 );
    //console.log(brojKolona, widthLength);

    let columns = [];
    let h =  { text: "Hours", datafield: 'Hour', width: widthLength+'%', pinned: true };
    columns.push(h);
    let d = { text: 'Demand', datafield: 'Demand', width: widthLength+'%',cellsalign: 'right', cellsformat: 'd2'};
    columns.push(d);
    let ud = { text: 'Unserved demand', datafield: 'UD', width: widthLength+'%',cellsalign: 'right', cellsformat: 'd2', cellclassname: cellclassname };
    columns.push(ud);

    techs.forEach(function(tech) {
       //console.log(tech);
        if(tech != 'ImportExport'){
            let t = { text: tech, datafield: tech, width: widthLength+'%',cellsalign: 'right',  cellsformat: 'd2'};
            let tc = { text: tech + ' curtailment', datafield: tech+'Curtail', width: widthLength + '%',cellsalign: 'right',  cellsformat: 'd2', cellclassname: cellclassname };
            columns.push(t);
            columns.push(tc);
        }
    });
    let height = $(window).height() - 155;
    settings =
    {
        height:height,
        rowsheight: 25,
        width: '100%',
        theme: theme,
        filterable: true,   
        columnsresize: true,          
        source: daGridHD,
        selectionmode: 'multiplecellsadvanced',
        columns: columns
    }; 
    $('#jqxHourlyDataGrid').jqxGrid(settings);

    $("#xlsHD").click(function (e) {
        e.preventDefault();
        console.log('csv export');
        $("#jqxHourlyDataGrid").jqxGrid('exportdata', 'csv', 'Hourly data', true, null, true,'http://localhost/ESST/esst.ver.2.7.0/app/export.php'); 
       // $("#jqxGrijqxHourlyDataGridd").jqxGrid('exportdata', 'csv', 'jqxGrid', true, null, true, http://www.myserver.com/save-file.php);
    });

    $('#jqxHourlyDataGrid').jqxGrid('refreshfilterrow');
    $('#jqxHourlyDataGrid').jqxGrid('refresh');
    //$("#jqxHourlyDataGrid").jqxGrid('updatebounddata', 'cells');
    $("#ddlYearsHDout").on('change', function (event){    
        //$('.loadermainLink').show(); 
        var item = event.args.item;
        year = item.value;
        //console.log(year);
        source.root = year.toString();
        $('#HDtitle').html('Hourly data values for demand, wind, solar, hydro, ' + '<span>year: ' + year + '</span>');
        $("#jqxHourlyDataGrid").jqxGrid('updatebounddata', 'cells');
        //getHDataByYear(year);
        //$('#btnSaveHD').hide();
        //$("#msgBasic").hide();
    }); 

}
//vrijednosti ya satna opterecenja input preko grida umjesto xls file  hPattern
function GridHourlyInputBeta(casename,user, daYears){
    //$('#jqxHDataGrid').jqxGrid('showloadelement');
    //$('#jqxHDataGrid').jqxGrid('hideloadelement');
    $('#loaderHD').show();
    
    $("#ddlYearsHD").jqxDropDownList({ 
        source: daYears, 
        selectedIndex: 0, 
        theme: theme, 
        displayMember: 'year',  
        height: 16,
        width:200,
        autoDropDownHeight: true
    });

    var year = $("#ddlYearsHD").jqxDropDownList('getSelectedItem').value;
    //console.log(year);
    getHDataByYear(year);

    $("#ddlYearsHD").on('change', function (event){    
        $('#loaderHD').show(); 
        var item = event.args.item;
        year = item.value;
        getHDataByYear(year);
        $('#btnSaveHD').hide();
        $('#allYearsId').hide();
        $("#msgBasic").hide();
    }); 

    $("#jqxHDataGrid").on('cellvaluechanged', function (event)  {
        $('#btnSaveHD').show();
        $('#allYearsId').show();
    });

    function getHDataByYear(year){
        $.ajax({
            method: 'POST',
            url: 'app/hSimulation/HourlyAnalysis.php',
            data: { action:'getHPattern' },
            dataType: 'json',
            async:true,
            complete: function(e) {
                var serverResponce = e.responseJSON;
                source =
                {
                    localdata: serverResponce,
                    datatype: 'json',
                    root: year.toString(),
                    async: true, 
                    cache: false,
                    datafields: [
                            { name: 'Hour',type: 'string'  },
                            { name: 'Demand', type: 'number' },
                            { name: 'Wind', type: 'number' },
                            { name: 'Solar', type: 'number' },
                            { name: 'Hydro', type: 'number' }
                            ],
                };   
    
                let daGridHD = new $.jqx.dataAdapter(source);
                
                var cellclass = function (row, columnfield, value) {
                    if (value < 0) {
                        return 'negative';
                    }
                }

                let height = $(window).height() - 155;
    
                settings =
                {
                    height: height,
                    rowsheight: 25,
                    width: '100%',
                    theme: theme,
                    filterable: true,     
                    columnsresize: true,       
                    source: daGridHD,
                    editable: true,
                    selectionmode: 'multiplecellsadvanced',
                    columns: [
                      { text: "Hours", datafield: 'Hour', width: 80, pinned: true },
                      { text: 'Solar', datafield: 'Solar',cellsalign: 'right',cellsformat: 'd2', cellclassname: cellclass},
                      { text: 'Wind', datafield: 'Wind',cellsalign: 'right',  cellsformat: 'd2', cellclassname: cellclass},
                      { text: 'Hydro', datafield: 'Hydro', cellsalign: 'right',  cellsformat: 'd2', cellclassname: cellclass},
                      { text: 'Demand', datafield: 'Demand',cellsalign: 'right', cellsformat: 'd2', cellclassname: cellclass}
                    ]    
                }; 
    
                $('#jqxHDataGrid').jqxGrid(settings);
                $('#loaderHD').hide();
                //$('#jqxHDataGrid').jqxGrid('hideloadelement');
             },
             error: function(jqXHR, textStatus, errorThrown) {
                 ShowErrorMessage(errorThrown);
                 $('#loaderHD').hide(); 
             }
        });
    }

    $("#xlsHDp").click(function (e) {
        e.preventDefault();
        $("#jqxHDataGrid").jqxGrid('exportdata', 'csv', 'Hourly data', true, null, true,'http://localhost/ESST/esst.ver.2.7.0/app/export.php'); 
    });

}
function getLCOE(user, casename, techs, currency) {
    $('#minLoader_lcoe').show(); 
    $.ajax({
        method: 'GET',
        url: 'xml/'+ user + '/'+casename+'/hSimulation/LCOE.json',
        dataType: 'json',
        async:true,
        cache:false,

        complete: function(e) {
            var serverResponce = e.responseJSON;
            let columns = [];
            let  groups = [];;
            
            function getSource(root){
                return  {
                    localdata: serverResponce,
                    datatype: 'json',
                    //root: root,
                    async: true, 
                    cache: false,
                };  
            }
      
            function getDataAdpter(root) {
                let source = getSource(root);
                
                columns = [];  
                columns.push({ text: currency + '/MWh', datafield: 'Name', minwidth: 80,maxwidth:220, pinned: true});

                var dataAdapter = new $.jqx.dataAdapter(source, {
                    beforeLoadComplete: function (records) {
                        //console.log(records);
                        let dataSet = records[0];
                        let out = [];
                        let years = [];
                        
                        techs.forEach(function(tech) {
                            if(tech != 'ImportExport' && tech != 'Storage'){
                                //['joe', 'jane', 'mary'].includes('jane');
                              
                                let tmp = {};
                                tmp['Name'] = tech;

                                for (let year in dataSet) {
                                    if(Object.keys(dataSet[year]).includes(tech)){
                                        //console.log('year', year, 'tech', tech);
                                        // dataSet[year]['Year'] = year;
                                        // out.push(dataSet[year]);
                                        //if (years.indexOf(year) == -1 && year != 'uid') {
                                        if (year != 'uid') {
                                            
                                            if (years.indexOf(year) == -1){
                                                years.push(year);

                                                //console.log('year', year);
                                                let GROUP = { text: year, name: year, align: 'center' };
                                                groups.push(GROUP);
        
                                                //columns.push({ 'text': year, 'datafield': year, 'cellsalign':'right',  'cellsformat': 'd2', 'cellclassname': cellclass, 'aggregates': ['sum', 'avg'] });
                                                INV ={ text:'INV', datafield:'INV'+year, minwidth: 80,maxwidth:220,  cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: year, 'cellclassname': cellclass };
                                                columns.push(INV);
                                    
                                                FOM ={ text:'FOM', datafield:'FOM'+year, minwidth: 80,maxwidth:220, cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: year, 'cellclassname': cellclass };
                                                columns.push(FOM);
                                    
                                                VOM ={ text:'VOM', datafield:'VOM'+year, minwidth: 80,maxwidth:220, cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: year, 'cellclassname': cellclass };
                                                columns.push(VOM);
                                    
                                                FC ={ text:'FC ', datafield:'FC'+year, minwidth: 80,maxwidth:220,  cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: year, 'cellclassname': cellclass };
                                                columns.push(FC);
                                    
                                                CO2 ={ text:'CO2', datafield:'CO2'+year, minwidth: 80,maxwidth:220,  cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: year, 'cellclassname': cellclass };
                                                columns.push(CO2);
                                    
                                                Total ={ text:'Total', datafield:'Total'+year, minwidth: 80,maxwidth:220,   cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: year, 'cellclassname': cellclass};
                                                columns.push(Total);
                                            }

                                            // //dataadapter
                                            // $LCOEtmp_t['INV'.$year] = $LevalizedCost[$year][$tech]['INV'];
                                            // $LCOEtmp_t['FOM'.$year] = $LevalizedCost[$year][$tech]['FOM'];
                                            // $LCOEtmp_t['VOM'.$year] = $LevalizedCost[$year][$tech]['VOM'];
                                            // $LCOEtmp_t['FC'.$year] = $LevalizedCost[$year][$tech]['FC'];
                                            // $LCOEtmp_t['CO2'.$year] = $LevalizedCost[$year][$tech]['CO2'];
                                            // $LCOEtmp_t['Total'.$year] = $LevalizedCost[$year][$tech]['Total'];
    
                                            //tmp[year] = dataSet[year][tech];
    
                                            tmp['INV'+year] = dataSet[year][tech]['INV'];
                                            tmp['FOM'+year] = dataSet[year][tech]['FOM'];
                                            tmp['VOM'+year] = dataSet[year][tech]['VOM'];
                                            tmp['FC'+year] = dataSet[year][tech]['FC'];
                                            tmp['CO2'+year] = dataSet[year][tech]['CO2'];
                                            tmp['Total'+year] = dataSet[year][tech]['Total'];
                                        }
                                    }else{
                                        if (year != 'uid') {
                                            tmp['INV'+year] = 0;
                                            tmp['FOM'+year] = 0;
                                            tmp['VOM'+year] = 0;
                                            tmp['FC'+year] = 0;
                                            tmp['CO2'+year] = 0;
                                            tmp['Total'+year] = 0; 
                                        }
  
                                    }
                                    
                                }

                                out.push(tmp);
                            }
                        });
                        // console.log('coludataSetmns', dataSet);
                        // console.log('columns', columns);
                        //console.log(out);
                        return out;
                    },
                    loadError: function (jqXHR, status, error) {
                    },
                    loadComplete: function (records) {
                    }
                });
                return dataAdapter;
            }

            var cellclass = function (row, columnfield, value) {
                //console.log(row, columnfield)
                if (value < 0) {
                    return 'negative';
                }
                if(columnfield.includes("Total")){
                    return 'bold';
                }
            }

            function getSetting(root){
                dataAdapter = getDataAdpter(root);
                return {
                    autoheight: true,
                    width: '100%',
                    //columnsmenuwidth: 30,
                    theme: theme,
                   // filterable: true,     
                    columnsresize: true,  
                    showaggregates: false,  
                    showstatusbar: false,
                    statusbarheight: 50,   
                    source: dataAdapter,
                    editable: true,
                    selectionmode: 'multiplecellsadvanced',
                    columns: columns,
                    columngroups: groups
                };
            };
            $('#jqxLCOE_p2').jqxGrid(getSetting('Phase2'));
            //$('#jqxLCOE_p2').jqxGrid('updatebounddata', 'cells');
            
            let techSeries = getTechsSeries(techs, true);
            function getDataAdapterChart(root) {
                let source = getSource(root);
                var dataAdapter = new $.jqx.dataAdapter(source, {
                    beforeLoadComplete: function (records) {
                        let dataSet = records[0];
                        let out = [];
                        let i = 0;
                        Object.keys(dataSet).forEach(function(yr) {
                            if(yr != 'uid'){
                                let tmp = {};
                                tmp['Year'] = yr;
                                Object.keys(dataSet[yr]).forEach(function(tech) {         
                                    tmp[tech] = dataSet[yr][tech]['Total'];      
                                });
                                tmp['uid'] = i;
                                i++;
                                out.push(tmp);
                            }

                        });
                        return out;
                    }
                });
                //console.log('dataAdapter', dataAdapter);
                return dataAdapter;
            }

            let da = getDataAdapterChart('Phase2');
            let chartLCOE = getChartSettings(title='', description='', da, techSeries, currency + '/MWh', type='column', datafield='Year');
            $('#jqxChartLCOE_p2').jqxChart(chartLCOE);
            var chart7 = $('#jqxChartLCOE_p2').jqxChart('getInstance');
            //chart7.categoryAxis.dataField = 'Year';
            chart7.seriesGroups[0].labels.angle = 90;
            chart7.update();
            //$('#minLoader_lcoe').hide(); 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            ShowErrorMessage(errorThrown.msg);
            $('#minLoader_lcoe').hide(); 
        }
    });
}
function getAUC(user, casename, techs, currency) {
    $('#minLoader_lcoe').show();
    $.ajax({
        method: 'GET',
        url: 'xml/'+ user + '/'+casename+'/hSimulation/AUC.json',
        dataType: 'json',
        async:true,
        cache:false,
        complete: function(e) {
            var auc = e.responseJSON;
            initAUC(auc)

        },
        error: function(jqXHR, textStatus, errorThrown) {
            ShowErrorMessage(errorThrown.msg);
            $('#minLoader_lcoe').hide();
        }
    });

    function initAUC(auc){

        let dataSet = auc;
        let out = [];
        let years = [];
        let columns = [];  
        
        let lcoeVar = ['INV', 'FOM', 'VOM', 'FC', 'CO2'];
           
        var cellclass = function (row, columnfield, value) {
            if (value < 0) {
                return 'negative';
            }
        }

        columns.push({ text: currency + '/MWh', datafield: 'Name',minwidth: 80,maxwidth:220, pinned: true});
        for (let year in dataSet) {
            if(year != 'uid'){
                if (years.indexOf(year) == -1 && year != 'uid') {
                    years.push(year);
                    columns.push({ 'text': year, 'datafield': year, 'cellsalign':'right',  'cellsformat': 'd2', 'cellclassname': cellclass, aggregates: ['sum'] });
                }
            }  
        }

        for (let aucVar in lcoeVar) {
            let tmp = {};
            tmp['Name'] = lcoeVar[aucVar];
            for (let i in years) {
                tmp[years[i]] = dataSet[years[i]][lcoeVar[aucVar]];
            }
            out.push(tmp);
        }
        srcGrid =  {
            localdata: out,
            datatype: 'json',
            async: true, 
            cache: false,
        };  
        var daGrid = new $.jqx.dataAdapter(srcGrid);
        gridSetting = {
            autoheight: true,
            width: '100%',
            theme: theme,   
            columnsresize: true,  
            showaggregates: true,  
            showstatusbar: true,
            statusbarheight: 50,   
            source: daGrid,
            //editable: true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns
        };
        $('#jqxAUC_p2').jqxGrid(gridSetting);
        //$('#jqxAUC_p2').jqxGrid('updatebounddata', 'cells');

        //////////////////////////////////////////CHART
        let outChart = [];

        for (let i in years) {
            let tmpC = {};
            tmpC['year'] = years[i];
            for (let aucVar in lcoeVar) {
                tmpC[lcoeVar[aucVar]] = dataSet[years[i]][lcoeVar[aucVar]];
            }
            outChart.push(tmpC);
        };
        srcChart =  {
            localdata: outChart,
            datatype: 'json',
            async: true, 
            cache: false,
        }; 
        var daChart = new $.jqx.dataAdapter(srcChart);
        let series= [
            { dataField:'INV', displayText:'Investment' },
            { dataField:'FOM', displayText:'Fixed operating cost' },
            { dataField:'VOM', displayText:'Variable operating cost' },
            { dataField:'FC', displayText:'Fuel cost' },
            { dataField:'CO2', displayText:'CO2 cost' }
            // { dataField:'Total', displayText:'Total cost' }
        ]

        let chartAVGUC = getChartSettings(title='', description='', daChart, series, currency + '/MWh');
        $('#jqxChartAUC_p2').jqxChart(chartAVGUC);

        $('#minLoader_lcoe').hide();
    }
}
function GridElMixP2(user, casename){

    myURL2  = 'xml/'+ user + '/'+casename+'/hSimulation/ElMixP2.json';
    source =
    {
        datafields: [
            { name: 'year', type: 'int' },
			{ name: 'UD', type: 'decimal' },
			{ name: 'maxUD', type: 'decimal' },
			{ name: 'countUD', type: 'int' },
			{ name: 'elMix', type: 'json' },
        ],
        datatype: 'json',
		url: myURL2,
		async: true,
    };

    var dataAdapter = new $.jqx.dataAdapter(source, {
        loadComplete: function (records) {
            var records = dataAdapter.records;
            $('#jqxElMixGrid').jqxGrid(GridSetting( records ));
        },
        loadError: function (jqXHR, status, error) {
        },
        beforeLoadComplete: function (records) {
        }
    });

    dataAdapter.dataBind();
  
    function GridSetting( recordi){
        var initrowdetails = function (index, parentElement, gridElement, record) {
            var id = record.uid.toString();

                // var grid = $($(parentElement).children()[0]);
                // var chart = $($(parentElement).children()[1]); 
                // var chart2 = $($(parentElement).children()[2]); 

            var grid = $($(parentElement).find( "#grElMix" ));
            var chart = $($(parentElement).find( "#ch1ElMix" ));
            var chart2 = $($(parentElement).find( "#ch2ElMix" ));

			var nestedSource = {
                datafields: [
                    { name: 'tech', map: 'tech', type: 'string' },
                    { name: 'valueP1', map: 'valueP1', type: 'decimal' },
                    { name: 'valueP2', map: 'valueP2', type: 'decimal' }
                ],
                datatype: 'json',
                root: 'technology',
                localdata: recordi[index]
            };
				  
            var columnsrenderer = function (value) {
            	return '<div style="text-align: center; margin-top: 12px; word-wrap:normal;white-space:normal;">' + value + '</div>';
            }
			
            var nestedAdapter = new $.jqx.dataAdapter(nestedSource);
            
            if (grid != null) {
                grid.jqxGrid({
                    source: nestedAdapter, 
                    theme: theme, 
                    width: '100%', 
                    autoheight:true, 
                    columnsresize:true,
                    rowsheight: 27,
                    //columnsheight: 27,
                    altrows: true,
                    selectionmode: 'multiplecellsadvanced',
                    columns: [
						//{ text: '', width: '10%', cellsrenderer: photorenderer , renderer: columnsrenderer},
                        { text: "Technology", datafield: "tech", width: '33%',cellsformat: 'd2' , renderer: columnsrenderer},
                        { text: "el. mix P1[%]", datafield: "valueP1", width: '33%',cellsalign: 'right',cellsformat: 'd2', renderer: columnsrenderer},
                        { text: "el mix P2 [%]", datafield: "valueP2", width: '33%',cellsalign: 'right',cellsformat: 'd2' , renderer: columnsrenderer}
                   ]
                });
            }
                
            // .PieToolTip
                chart.jqxChart({
                    description: "",
                    title: "Share inputs phase 1",
                    enableAnimations: true,
                    showLegend: false,
                    showToolTips: false,
                    //showBorderLine: true,
                    //legendLayout: { left: 520, top: 170, width: 300, height: 200, flow: 'vertical' },
                    //padding: { left: 5, top: 5, right: 5, bottom: 5 },
                    titlePadding: { left: 0, top: 0, right: 0, bottom: 5 },
                    seriesGroups:
                        [
                            {
                                type: 'pie',
                                offsetX: 400,
                                source: nestedAdapter,
                                colorScheme: 'scheme01',
                                xAxis:
                                {
                                    formatSettings: { prefix: 'Phase1 ' }
                                },
                                series: [
                                            { 
                                                dataField: 'valueP1', 
                                                displayText: 'tech', 
                                                showLabels: true,
                                                labelRadius: 135, 
                                                initialAngle:0, 
                                                toolTipClass: 'PieToolTip',
                                                radius: 110,
                                                centerOffset: 5, 
                                                formatSettings: { sufix: '%', decimalPlaces: 1 },
                                                formatFunction: function (value, itemIndex) {
                                                    if(value<= -1 || value>1){
                                                        var techn = nestedAdapter.records[itemIndex].tech;
                                                        return techn + ": " + Number(value).toFixed(1)  + "%";
                                                    }
                                                }
                                            },
                                        ]
                            },
                        ]
            });

                chart2.jqxChart({
                    description: "",
                    title: "Hourly simulation",
                    enableAnimations: true,
                    showLegend: false,
                    showToolTips: false,

                    //showBorderLine: true,
                    //legendLayout: { left: 520, top: 170, width: 300, height: 200, flow: 'vertical' },
                    //padding: { left: 5, top: 5, right: 5, bottom: 5 },
                    titlePadding: { left: 0, top: 0, right: 0, bottom: 5 },
                    seriesGroups:
                        [
                            {
                                type: 'pie',
                                offsetX: 350,
                                source: nestedAdapter,
                                colorScheme: 'scheme01',
                                xAxis:
                                {
                                    formatSettings: { prefix: 'Phase 2 ' }
                                },
                                series: [
                                            { 
                                                dataField: 'valueP2', 
                                                displayText: 'tech', 
                                                showLabels: true,
                                                labelRadius: 135, 
                                                toolTipClass: 'PieToolTip',
                                                initialAngle: 0,
                                                radius:110,
                                                // radius: 70, 
                                                // innerRadius: 30,
                                                centerOffset: 5, 
                                                formatSettings: { sufix: '%', decimalPlaces: 1 },
                                                formatFunction: function (value, itemIndex) {
                                                if(value<= -1 || value>1){
                                                        var techn = nestedAdapter.records[itemIndex].tech;
                                                        return techn + ": " + Number(value).toFixed(1) + "%";
                                                    }
                                                }
                                            },
                                        ]
                            }
                        ]
            });
    
        }
        var columnsrenderer = function (value) {
        	return '<div style="text-align: center; margin-top: 12px; word-wrap:normal;white-space:normal;">' + value + '</div>';
        }
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {
            if (value > 0) {
                 value = $.jqx.dataFormat.formatnumber(value, 'd2');
                //return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + ';">' + value + '</span>&nbsp;&nbsp; <i class="fa fa-exclamation-triangle red" aria-hidden="true"></i>';
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';">' + value + '  &nbsp;&nbsp; <i class="fa fa-exclamation-triangle red" aria-hidden="true"></i> </span>';
            }
        }
    
        var size = Object.keys(recordi[0].elMix.technology).length;

        let rowdetailsheight = 30 * size + 40 + 320;  //35 za svaki red + 40 header + 280 za chart
        settings = {
                width: '100%',
                columnsheight: 50,
                autoheight: true,
                source: this.source,
                theme: theme,
                rowdetails: true,
                columnsresize:true,
                //rowsheight: 25,
                altrows: true,
                initrowdetails: initrowdetails,
                
                rowdetailstemplate: { 
                    //rowdetails: `<div id='grid' style='margin: 5px;width: 30%;float:left;'></div><div id='chart' style='margin: 5px;width: 30%; height: 350px; float: left;'></div><div id='chart2' style='margin: 5px;width: 30%; height: 350px; float: left;'></div>`, 
                    rowdetails: `<div class="row">
                                    <div class='col-md-12' style="margin-bottom:10px">
                                        <div id='grElMix'></div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div id='ch1ElMix' style="height: 320px;"></div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div id='ch2ElMix' style="height: 320px;"></div>
                                    </div>
                                 </div>`, 
                    
                    rowdetailsheight: rowdetailsheight, 
                    rowdetailshidden: true },
                ready: function () {
                    $("#jqxElMixGrid").jqxGrid('showrowdetails',0);
                },
                columns: [
                      { text: 'Year', datafield: 'year', minwidth: 100,  width: '25%'},
					  { text: 'Unserved<sub>y</sub><br>[GWh]', datafield: 'UD', width: '24%',cellsformat: 'd2',cellsalign: 'right', renderer: columnsrenderer,cellsrenderer: cellsrenderer },
					  { text: 'Max Unserved<sub>y</sub><br>[MWh]', datafield: 'maxUD', width: '24%', cellsformat: 'd2',cellsalign: 'right', renderer: columnsrenderer },
					  { text: 'Count Unserved<sub>y</sub><br>[hours]', datafield: 'countUD', width: '24%', cellsalign: 'right', renderer: columnsrenderer }
                ]
        }; //kraj inicijalizacije i settinga za trans grid 
        return settings;
    }
}
function getCO2(user, casename, techs) {
    //console.log(techs)
     $('#minLoader_co2').show(); 
    $.ajax({
        method: 'GET',
        url: 'xml/'+ user + '/'+casename+'/hSimulation/CO2.json',
        dataType: 'json',
        async:true,
        cache:false,

        complete: function(e) {
            var serverResponce = e.responseJSON;
            //console.log(serverResponce);
            let columns = [];
            
            function getSource(){
                return  {
                    localdata: serverResponce,
                    datatype: 'json',
                    //root: root,
                    async: true, 
                    cache: false,
                };  
            }
            let source = getSource();
      
            function getDataAdpter(type) {
                columns = [];  
                columns.push({ text: 'kTon', datafield: 'Tech', minwidth: 80, pinned: true});
                var dataAdapter = new $.jqx.dataAdapter(source, {
                    beforeLoadComplete: function (records) {
                        let dataSet = records[0];
                        //console.log(dataSet);
                        let out = [];
                        let years = [];
                        techs.forEach(function(tech) {
                            if(tech != 'ImportExport' && tech != 'Storage'){
                                let tmp = {};
                                tmp['Tech'] = tech;
                                for (let year in dataSet) {
                                    if(year != 'uid'){
                                        if (years.indexOf(year) == -1 && year != 'uid') {
                                            years.push(year);
                                            columns.push({ 'text': year, 'datafield': year, 'cellsalign':'right',  'cellsformat': 'd2', 'cellclassname': cellclass, 'aggregates': ['sum', 'avg'] });
                                        }
                                        //console.log(tech, year);
                                        tmp[year] = dataSet[year][tech][type];
                                    }  
                                }
                                out.push(tmp);
                            }

                        });
                        return out;
                    },
                    loadError: function (jqXHR, status, error) {
                    },
                    loadComplete: function (records) {
                    }
                });
                return dataAdapter;
            }

            var cellclass = function (row, columnfield, value) {
                if (value < 0) {
                    return 'negative';
                }
            }

            function getSetting(type){
                dataAdapter = getDataAdpter(type);
                return {
                    autoheight: true,
                    width: '100%',
                    theme: theme,
                   // filterable: true,     
                    columnsresize: true,  
                    showaggregates: true,  
                    showstatusbar: true,
                    statusbarheight: 50,   
                    source: dataAdapter,
                    editable: true,
                    selectionmode: 'multiplecellsadvanced',
                    columns: columns
                };
            }

            $('#jqxGridCO2').jqxGrid(getSetting('CO2'));
            $('#jqxGridSO2').jqxGrid(getSetting('SO2'));
            $('#jqxGridNOX').jqxGrid(getSetting('NOX'));
            $('#jqxGridPM').jqxGrid(getSetting('Other'));
            $('#jqxGridSYS').jqxGrid(getSetting('Sys'));

            // $('#jqxGridCO2').jqxGrid('updatebounddata', 'cells');
            // $('#jqxGridSO2').jqxGrid('updatebounddata', 'cells');
            // $('#jqxGridNOX').jqxGrid('updatebounddata', 'cells');
            // $('#jqxGridPM').jqxGrid('updatebounddata', 'cells');
            // $('#jqxGridSYS').jqxGrid('updatebounddata', 'cells');

            let techSeries = getTechsSeries(techs, false);

            function getDataAdapterChart(type) {
                var dataAdapter = new $.jqx.dataAdapter(source, {
                    beforeLoadComplete: function (records) {
                        let dataSet = records[0];
                        let out = [];
                        Object.keys(dataSet).forEach(function(yr) {
                            if(yr != 'uid'){ 
                                let tmp = {};
                                tmp['Year'] = yr;
                                techs.forEach(function(tech) {
                                    if(tech != 'ImportExport' && tech != 'Storage'){
                                        // console.log(yr,tech, type);
                                        // console.log(dataSet[yr][tech][type]);
                                        tmp[tech] = dataSet[yr][tech][type]; 
                                    }
                                });
                                out.push(tmp);
                            }
                        });
                        return out;
                    }
                });
                return dataAdapter;
            }

            function getSettingCahrt(type) {
                dataAdapter = getDataAdapterChart(type);
                let chartSetting = getChartSettings(title='', description='', dataAdapter, techSeries, 'Kton', type= 'stackedcolumn', dataField= 'Year');
                return chartSetting;

            }

            // setup the chart
            $('#jqxChartCO2').jqxChart( getSettingCahrt('CO2') );
            $('#jqxChartSO2').jqxChart( getSettingCahrt('SO2') );
            $('#jqxChartNOX').jqxChart( getSettingCahrt('NOX') );
            $('#jqxChartPM').jqxChart( getSettingCahrt('Other') );
            $('#jqxChartSYS').jqxChart( getSettingCahrt('Sys') );

            //chart events
            $("#xlsCo2").click(function() {
                $("#jqxGridCO2").jqxGrid('exportdata', 'xls', 'CO2 emissions');
            });
            $("#pngCo2").click(function() {
                $("#jqxChartCO2").jqxChart('saveAsPNG', 'CO2 emissions.png',  getExportServer());
            }); 

            $("#xlsSo2").click(function() {
                $("#jqxGridSO2").jqxGrid('exportdata', 'xls', 'SO2 emissions');
            });
            $("#pngSo2").click(function() {
                $("#jqxChartSO2").jqxChart('saveAsPNG', 'SO2 emissions.png',  getExportServer());
            }); 

            $("#xlsNox").click(function() {
                $("#jqxGridNOX").jqxGrid('exportdata', 'xls', 'NOx emissions');
            });
            $("#pngNox").click(function() {
                $("#jqxChartNOX").jqxChart('saveAsPNG', 'NOx emissions.png',  getExportServer());
            }); 

            $("#xlsPm").click(function() {
                $("#jqxGridPM").jqxGrid('exportdata', 'xls', 'PM emissions');
            });
            $("#pngPm").click(function() {
                $("#jqxChartPM").jqxChart('saveAsPNG', 'PM_emissions.png',  getExportServer());
            });  
            $("#xlsSys").click(function() {
                $("#jqxGridSYS").jqxGrid('exportdata', 'xls', 'System CO2 emissions');
            });
            $("#pngSys").click(function() {
                $("#jqxChartSYS").jqxChart('saveAsPNG', 'System_emissions.png',  getExportServer());
            });  
            
        var chart1 = $('#jqxChartSO2').jqxChart('getInstance');
        var chart2 = $('#jqxChartNOX').jqxChart('getInstance');
        var chart3 = $('#jqxChartCO2').jqxChart('getInstance');
        var chart4 = $('#jqxChartPM').jqxChart('getInstance');
        var chart5 = $('#jqxChartSYS').jqxChart('getInstance');
        //var chart5 = $('#jqxChart_systemCO2').jqxChart('getInstance');

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
        $('#minLoader_co2').hide(); 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            ShowErrorMessage(errorThrown.msg);
            $('#minLoader_co2').hide(); 
        }
    });
}
function getMCoS(user, casename, yearsArr, currency) {
    $('#minLoader_mcos').show();
    $.ajax({
        method: 'GET',
        url: 'xml/'+ user + '/'+casename+'/hSimulation/MCoScp.json',
        dataType: 'json',
        async:true,
        cache:false,
        complete: function(e) {
            var serverResponce = e.responseJSON;
            let data = [];
            for (let i = 0; i < 8760; i++) {
                let chunk = {};
                $.each(yearsArr, function (id, year) {
                    chunk['hour'] = serverResponce[year]['HourlyData'][i]['hour'];
                    chunk[year] = serverResponce[year]['HourlyData'][i]['MCoS'];
                });
                data.push(chunk);
            }         
            let srcMCoS = {
                localdata: data,
                datatype: 'json',
                async: true, 
                cache: false,
            };  
            var dataAdapter = new $.jqx.dataAdapter(srcMCoS);
            var cellclass = function (row, columnfield, value) {
                if (value < 0) {
                    return 'negative';
                }
            }
            let columns = [];
            let series = [];
            columns.push({ text: 'Hour', datafield: 'hour', pinned:true, editable: false, align: 'center',  minWidth: 75, maxWidth: 200 })
            $.each(yearsArr, function (id, year) {
                columns.push({ text: year, datafield: year,  cellsalign: 'right',  align: 'right' ,cellsformat: 'd2', cellclassname: cellclass});
                series.push({ dataField: year,lineWidth: 1, displayText: year, opacity: 1 });
            });

            let height = $(window).height() - 475;
            let gridSetting =  {
                    width: '100%',
                    //columnsmenuwidth: 30,
                    height: height,
                    rowsheight: 25,
                    width: '100%',
                    theme: theme,
                    filterable: true,     
                    columnsresize: true,       
                    source: dataAdapter,
                    editable: true,
                    selectionmode: 'multiplecellsadvanced',
                    columns: columns,
                };
            $('#jqxMCoS').jqxGrid(gridSetting);
            $('#jqxMCoS').jqxGrid('updatebounddata', 'cells');

            //series: [{ dataField: year,lineWidth: 2, displayText: year, opacity: 1 }]

            // let chartSetting = getChartSettings(title='', description='', dataAdapter, series, currency+'/MW', type= 'spline', dataField= 'hour');
            // $('#jqxMCoSChart').jqxChart( chartSetting);

            let chartSetting={
                title: '',
                description: "",
                padding: {left: 5,top: 5,right: 5,bottom: 5},
                titlePadding: {left: 150, top: 0,right: 10,bottom: 10},
                //legendLayout: { left: 45, top: 150, width: '100%', height: 200, flow: 'horizontal' },
                source: dataAdapter,
                enableAnimations: true,
                showLegend: true,
                enableAnimations: true,
                enableCrosshairs: true,
                crosshairsDashStyle: '2,2',
                crosshairsLineWidth: 1.5,
                crosshairsColor: '#2f6483',
                borderLineColor: 'transparent',
                categoryAxis: {
                    dataField: 'hour',
                    type: 'basic',
                    minValue: 2190,
                    maxValue: 2920,
                    gridLinesInterval: 24, 
                    flip: false,
                    //valuesOnTicks: true,
                    rangeSelector: {
                        serieType: 'line',
                        unitInterval: 730,
                        padding: { /*left: 0, right: 0,*/ top: 10, bottom: 0 },
                        // Uncomment the line below to render the selector in a separate container
                        //renderTo: $('#selectorContainer'),
                        backgroundColor: "#f9f9f9",
                        size: 55,
                    }
                },
            seriesGroups: [{
                type: 'spline',
                series: series,
                //[{ dataField: 'value',displayText: 'Demand', lineWidth: 2, opacity: 1  , color: clDemand}]
                }]
                
            };
            $('#jqxMCoSChart').jqxChart( chartSetting);
            $('#minLoader_mcos').hide();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            ShowErrorMessage(errorThrown.msg);
            $('#minLoader_mcos').hide();
        }
    });

}
function getSTG(casename,user, currency){

    $('#minLoader_stg').show();

    $.ajax({
        url:'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
        async: true,  
        cache: false,
        success:function(data){
            init(data['STG']);
        }
    });

    function init(DATA){
        let columns = [];
        let srcSTG = [];
        let params = {
            "VOL":{unit: 'GWh', desc: "Maximum storage volume [Energy]"},
            "INIT":{unit: '%',  desc: "Initial state of storage [%]"},
            "CAP":{unit: 'MW',  desc: "Charging/discharging capacity [Power]"},
            "LOS":{unit: '%/year', desc: "Storage losses"},
            "Eff":{unit: '%', desc: "Efficiency of charge/discharge"},
            "FCo":{unit: currency+'/GWh/year', desc: "Fixed Cost"},
        };
        let  years = Object.keys(DATA);

        columns.push({ text: 'ID', datafield: 'name', width: 55, pinned: true, align: 'center', cellsalign: 'center', hidden:true });
        columns.push({ text: 'Storage parameter', datafield: 'desc', width: 275, pinned: true, align: 'center', cellsalign: 'left' });
        columns.push({ text: 'Unit', datafield: 'unit', width: 75, pinned: true, align: 'center' , cellsalign: 'center'});

        $.each( params, function( idp, paramObj ) {
            let tmp = {};
            tmp['name'] = idp;
            tmp['unit'] = paramObj['unit'];
            tmp['desc'] = paramObj['desc'];
            $.each( years, function( idy, year ) {
                tmp[year] = DATA[year][idp];
            });
            srcSTG.push(tmp);
        });

        var cellsrenderer = function (row, column, value, defaulthtml, columnproperties) {
            if ( value == 0) {
                value = $.jqx.dataFormat.formatnumber(value, 'd2');
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i>' + value + '</span>';
           }
           else if ( value < 0) {
                value = $.jqx.dataFormat.formatnumber(value, 'd2');
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';"><i class="fa fa-exclamation-triangle danger" aria-hidden="true"></i>' + value + '</span>';
            }
            else if ( (row == 1 || row == 3 || row == 4) &&  ( value<0 || value>100 )) {
                value = $.jqx.dataFormat.formatnumber(value, 'd2');
                return '<span style="margin: 4px;  float: ' + columnproperties.cellsalign + ';"><i class="fa fa-exclamation-triangle danger" aria-hidden="true"></i>' + value + '</span>';
            }
        }

        $.each( years, function( idy, year ) {
            columns.push({ text: year, datafield: year, cellsalign: 'right',cellsformat: 'd2', align: 'right', columntype: 'numberinput',cellsrenderer:cellsrenderer });
        });

        var src ={
            datatype: 'json',
            localdata:srcSTG
        };  

        var dataAdapter = new $.jqx.dataAdapter(src);

        //dataAdapter.dataBind();
        $("#jqxSTGGrid").jqxGrid({
            autoheight: true,
            autorowheight: true,
            width: '100%',
            theme: theme,
            source: dataAdapter,
            editable: true,
            columnsresize:true,
            selectionmode: 'multiplecellsadvanced',
            columns: columns
        
        });
        $('#minLoader_stg').hide();
    }

}
function saveSTG(yearsArr) {
    $('#loadermain').show(); 

    var data  = $('#jqxSTGGrid').jqxGrid('getrows');

    let STGData = {};

    $.each( yearsArr, function( id, year ) {
        STGData[year] = {};
        $.each( data, function( idd, obj ) {
            STGData[year][obj['name']] = obj[year];
        });

    });

    var url='app/hSimulation/HourlyAnalysis.php';
    $.ajax({
           type: "POST",
           url: url,
           dataType: 'json',
           data: {data: JSON.stringify(STGData), action:'saveSTG' },
           async:true,
           complete: function(e) {
                $('#loadermain h4').text('');
                $('#loadermain').hide(); 
                var serverResponce = e.responseJSON;
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        break;
                    case 'SUCCESS': 
                        ShowInfoMessage(serverResponce["msg"]);
                        localStorage.setItem("stg",  "changed");
                        stgChangedMsg();
                        $('#STG').modal('toggle');
                        break;
                }  
            },
            error: function(jqXHR, textStatus, errorThrown) {
                ShowErrorMessage(errorThrown);
                $('#loadermain').hide(); 
            }
    });
    return false;
}

/////////////////////////////////////////////////////////OPSOLETE///////////////////////////////////////////////
// function eta(user, casename, daYears){
//     $("#ddlYearsEta").jqxDropDownList({ 
//         source: daYears, 
//         selectedIndex: 0, 
//         theme: theme, 
//         displayMember: 'year',  
//         height: 16,
//         width:200,
//         autoDropDownHeight: true
//     });

//     var year = $("#ddlYearsEta").jqxDropDownList('getSelectedItem').value;

//     var etaGrid = new gridEta(casename, year, user);   
//     var daGridEta = new $.jqx.dataAdapter(etaGrid.source);  
//     $('#jqxGridEta').jqxGrid(etaGrid.setting(daGridEta));

//     $("#ddlYearsEta").on('change', function (event){     
//         var item = args.item;
//         year = item.value;
//         index = item.index;
//         etaGrid.source.root = year.toString();
//         $('#jqxGridEta').jqxGrid({source:daGridEta});
//     });  

//     $("#jqxGridEta").on('cellvaluechanged', function (event){
//         // event arguments.
//         var args = event.args;
//         // column data field.
//         var datafield = event.args.datafield;
//         // row's bound index.
//         var rowBoundIndex = args.rowindex;
//         // new cell value.
//         var value = args.newvalue;
//         // old cell value.
//         var oldvalue = args.oldvalue;
//         var errors = EtaGridValidation(daGridEta.records);
//         //console.log(errors);
//     });

//     //vrijednosti ya satna opterecenja  
//     function gridEta(casename,year, user){
//         this.URLWin  = 'xml/'+ user + '/'+casename+'/hSimulation/ETA.json',
//         this.source = {
//             url: this.URLWin,
//             root: year.toString(),
//             datatype: 'json',
//             async: true, 
//             cache: false,
//             //autoBind: true,
//             datafields: [
//                 { name: 'tech',type: 'string'  },
//                 { name: 'ETA1', type: 'number' },
//                 { name: 'ETA2', type: 'number' },
//                 { name: 'Pmin', type: 'number' }
//             ],
//             //totalrecords: 8760
//         };   

//         var validationETA1 = function (cell, value) {
//             var eta1 = $('#jqxGridEta').jqxGrid('getcellvalue', cell.row, "ETA1");
//             if(value > eta1){
//                 return { result: false, message: "ETA<sub>min</sub> must be less than ETA<sub>max</sub>" };
//             }
//             if (value < 0 || value > 100) {
//                 return { result: false, message: "Quantity should be in the 0-100 interval" };
//             }
//             return true;
//         }
//         var validation = function (cell, value) {
//             if (value < 0 || value > 100) {
//                 return { result: false, message: "Quantity should be in the 0-100 interval" };
//             }
//             return true;
//         }
        
//         this.setting = function(daEta){
//             settings =  {
//                 autoheight: true,
//                 rowsheight: 25,
//                 width: '100%',
//                 theme: theme,
//                 //filterable: true,  
//                 editable: true,          
//                 source: daEta,
//                 selectionmode: 'multiplecellsadvanced',
//                 columns: [
//                 { text: "Technology", datafield: 'tech', width: 80, pinned: true, editable: false },
//                 { text: 'ETA<sub>max</sub>', datafield: 'ETA1',cellsalign: 'right', cellsformat: 'p2', editable: false, cellclassname: 'eta1' , validation: validation },
//                 { text: 'ETA<sub>min</sub>', datafield: 'ETA2',cellsalign: 'right',  cellsformat: 'p2', cellclassname: 'eta2', validation: validationETA1 },
//                 { text: 'P<sub>min</sub>', datafield: 'Pmin',cellsalign: 'right',cellsformat: 'p2', cellclassname: 'Pmin', validation: validation }
//                 ]    
//             }; 
//             return settings;
//         }
//     }

// }
// function EtaGridValidation(dataAdapter){
//     var errorType = [];
//     $("#jqxGridEta").jqxGrid('hidevalidationpopups');
//     var year = $("#ddlYearsEta").jqxDropDownList('getSelectedItem').value;
//     $.each( dataAdapter, function(i, element ) {
//         if(element['ETA2'] > element['ETA1']){
//             var error = new Object();
//             //console.log('ETA 2 =' + element['ETA2'] + ' ETA 1 =' + element['ETA1'] + " tech =" +element['tech']);
//             error['Year'] = year;
//             error['Value'] = element['ETA2'];
//             error['Tech'] = element['tech'];
//             error['Column'] = 'ETA2';
//             error['Type'] = 'ETA<sub>min</sub> > ETA<sub>max</sub>';
//             error['rowid'] = element['uid'];
//             errorType.push(error);
//         }
//         if(element['ETA2'] > 100 || element['ETA2'] < 0){
//             var error = new Object();
//             //console.log('ETA 2 =' + element['ETA2'] + " tech =" +element['tech']);
//             error['Year'] = year;
//             error['Value'] = element['ETA2'];
//             error['Tech'] = element['tech'];
//             error['Column'] = 'ETA2';
//             error['Type'] = 'ETA<sub>min</sub> out of range';
//             error['rowid'] = element['uid'];
//             errorType.push(error);
//         }
//         if(element['Pmin'] > 100 || element['Pmin'] < 0){
//             var error = new Object();
//             //console.log('Pmin =' + element['Pmin']  + " tech =" +element['tech']);
//             error['Year'] = year;
//             error['Value'] = element['Pmin'];
//             error['Tech'] = element['tech'];
//             error['Column'] = 'Pmin';
//             error['Type'] = 'P<sub>min</sub> out of range';
//             error['rowid'] = element['uid'];
//             errorType.push(error);
//         }
//     });

//     if(Object.keys(errorType).length !== 0){
//         for (var i = 0; i < errorType.length; i++) {
//             //index = daGridEta.records.findIndex(x => x.sector==errorType[i]['Sector']);
//             //console.log( errorType[i]['rowid']+ " " + errorType[i]['Column']);
//             $("#jqxGridEta").jqxGrid('showvalidationpopup', errorType[i]['rowid'], errorType[i]['Column'], errorType[i]['Type']);
//         }
//     }
//     return errorType
// }
// function saveEta(){
//     //$("#btnSaveEta").on('click', function(e){
//     //$("#btnSaveEta").unbind().click(function() {

//         var etaData = $('#jqxGridEta').jqxGrid('getrows');
//         var daEta = JSON.stringify(etaData, ['tech', 'ETA1', 'ETA2', 'Pmin' ]);
//         var errorType = EtaGridValidation(etaData);
//         var year = $("#ddlYearsEta").jqxDropDownList('getSelectedItem').value;
        
//         if(Object.keys(errorType).length === 0){
//             $.ajax({
//                 url: 'app/hSimulation/HourlyAnalysis.php',
//                 dataType: 'json',
//                 type: 'POST',
//                 data: {data: daEta, year:year, action:'saveEta' },
//                 async:true,
//                 complete: function(e) {
//                     var serverResponce = e.responseJSON;
//                     switch (serverResponce["type"]) {
//                         case 'ERROR':
//                             ShowErrorMessage(serverResponce["msg"]);
//                             break;
//                         case 'EXIST':
//                             ShowWarningMessage(serverResponce["msg"]);
//                             break;
//                         case 'SUCCESS':
//                             $('#jqxNotification').jqxNotification('closeLast'); 
//                             ShowInfoMessage(serverResponce["msg"]);
//                             localStorage.setItem("eta",  "changed");
//                             etaChangedMsg();
//                             $('#eta').modal('toggle');
//                             break;
//                     }  
//                 },
//                 error: function(jqXHR, textStatus, errorThrown) {
//                     ShowErrorMessage(errorThrown);
//                 }
//             }); 
//         }
//         // else{
//         //     for (var i = 0; i < errorType.length; i++) {
//         //         //index = daGridEta.records.findIndex(x => x.sector==errorType[i]['Sector']);
//         //         //console.log( errorType[i]['rowid']+ " " + errorType[i]['Column']);
//         //         $("#jqxGridEta").jqxGrid('showvalidationpopup', errorType[i]['rowid'], errorType[i]['Column'], errorType[i]['Type']);
//         //     }
//         // }
//     //});
// }

// function saveMaintenanceDuration() {
//     $('#loadermain').show(); 
//     var mDuration = new Object();
//     $.each(MD, function( k, v ) {
//         mDuration[k] = $('#level'+k).val();      
//     });
//     var url='app/hSimulation/HourlyAnalysis.php';
//     $.ajax({
//            type: "POST",
//            url: url,
//            dataType: 'json',
//            data: {duration: mDuration, action:'saveMaintenanceDuration' },
//            async:true,
//            complete: function(e) {
//                 $('#loadermain h4').text('');
//                 $('#loadermain').hide(); 
//                 var serverResponce = e.responseJSON;
//                 switch (serverResponce["type"]) {
//                     case 'ERROR':
//                         ShowErrorMessage(serverResponce["msg"]);
//                         break;
//                     case 'EXIST':
//                         ShowWarningMessage(serverResponce["msg"]);
//                         break;
//                     case 'SUCCESS': 
//                         ShowInfoMessage(serverResponce["msg"]);
//                         localStorage.setItem("md",  "changed");
//                         mdChangedMsg();
//                         $('#MDuration').modal('toggle');
//                         break;
//                 }  
//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 ShowErrorMessage(errorThrown);
//                 $('#loadermain').hide(); 
//             }
//     });
//     return false;
// }
// function saveMaintenanceUnitSize() {
//     $('#loadermain').show(); 

//     var data  = $('#jqxGridUnitSize').jqxGrid('getrows');

//     unitSize = {};
//     $.each( data, function( id, obj ) {
//         unitSize[obj['tech']] = obj['value'];
//     });

//     var url='app/hSimulation/HourlyAnalysis.php';
//     $.ajax({
//            type: "POST",
//            url: url,
//            dataType: 'json',
//            data: {unitSize: unitSize, action:'saveMaintenanceUnitSize' },
//            async:true,
//            complete: function(e) {
//                 $('#loadermain h4').text('');
//                 $('#loadermain').hide(); 
//                 var serverResponce = e.responseJSON;
//                 switch (serverResponce["type"]) {
//                     case 'ERROR':
//                         ShowErrorMessage(serverResponce["msg"]);
//                         break;
//                     case 'SUCCESS': 
//                         ShowInfoMessage(serverResponce["msg"]);
//                         localStorage.setItem("md",  "changed");
//                         mdChangedMsg();
//                         $('#MDuration').modal('toggle');
//                         break;
//                 }  
//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 ShowErrorMessage(errorThrown);
//                 $('#loadermain').hide(); 
//             }
//     });
//     return false;
// }

// function MaintenanceDuration(user, casename){
//     $('#mSlider').empty();
//     $.ajax({
//         //url: 'xml/'+ user + '/'+casename+'/hSimulation/MD.json',
//         url:'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
//         async: true,  
//         cache: false,
//         success:function(data){
//             MD = data['MD']; 
//             initBarGauge(MD);
//         }
//     });

//     function initBarGauge(MD){
//         var colors = new Array();
//         $.each(MD, function( k, v ) {
//             colors.push(color_obj[k]);  
//             $('#mSlider').append('<span style="font-style: italic; color:'+color_obj[k]+'">'+k+'</span><div id="level'+k+'" class="slider"></div>');  
//             $('#level'+k).jqxSlider({theme: theme, 
//                 mode: "fixed", 
//                 min: 2, max: 4, 
//                 ticksFrequency: 1, 
//                 value: v, 
//                 step: 2, 
//                 showMinorTicks: true, 
//                 tooltip: {
//                     visible: true, 
//                     formatFunction: function (value, item){
//                         var realVal = parseInt(value);
//                         return ('Technology: '+ item + 'duration ' + realVal + ' weeks.');
//                     },
//                 },
//                 minorTicksFrequency: 1, 
//                 showTickLabels: true,
//                 tickLabelFormatFunction: function (value){
//                     if (value == 2) return value;
//                     if (value == 4) return value;
//                     return "";
//                 } 
//             });          
//         });
    
//         $('#jqxbargauge').jqxBarGauge({
//             width: 570,
//             height: 570,
//             values: Object.values(MD),
//             max: 4,
//             relativeInnerRadius: 0.1,
//             startAngle: 180,
//             endAngle: 0,
//             colorScheme: 'rgb',
//             customColorScheme: {
//                 name: 'rgb',
//                 colors: colors,
//             },
//              tooltip: {
//                 visible: true, 
//                 formatFunction: function (value, item) {
//                     var realVal = parseInt(value);
//                     return ('Technology: '+ item + 'duration ' + realVal + ' weeks.');
//                 },
//              },
//             title: {
//                 text: '',
//                 font: {
//                     size: 26,
//                 },
//                 verticalAlignment: 'top',
//                 margin: 20,
//                 subtitle: {
//                     text: '',
//                     font: {
//                         size: 15
//                     }
//                 }
//             },
//                     labels: {
//                         formatFunction: function (value, item) {
//                             var realVal = parseInt(value);
//                             return realVal + ' weeks';
//                         },
//                         font: { size: 18},
//                         indent: 30
//                     },
//         });
    
//         var setColor = function(){
//             var gaugeValues = new Array();
//             $.each(MD, function( k, v ) {
//                 gaugeValues.push( $('#level'+k).val() );        
//             });
//             $('#jqxbargauge').val(gaugeValues);
//         }
    
//         $('.jqx-slider').on('change', function (event){
//             setColor();
//         });
//     };

//     $.ajax({
//         //url: 'xml/'+ user + '/'+casename+'/hSimulation/MUS.json',
//         url:'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
//         async: true,  
//         cache: false,
//         success:function(data){
//             MUS = data['MUS']; 
//             //console.log(MUS);
//             initMUS(MUS);
//         }
//     });

//     function initMUS(MUS){
//         var srcMUS =
//         {
//             datatype: 'json',
//             localdata:MUS
//         };  
//         //console.log(srcMUS);
//         var dataAdapter = new $.jqx.dataAdapter(srcMUS, {
            
//             beforeLoadComplete: function (records) {
//                 //console.log(records);              
//                 let srcInv = [];
//                 $.each( records[0], function( name, value ) {
//                     let tmp = {};
//                             //console.log(obj['technology'], obj[LabelID]);;
//                     tmp['tech'] = name;
//                     tmp['value'] = value;
//                     srcInv.push(tmp);
//                 });
//                 ///console.log(srcInv);
//                 return srcInv;
//             },
//             loadError: function (jqXHR, status, error) {}
//         });
//         //dataAdapter.dataBind();
//         $("#jqxGridUnitSize").jqxGrid({
//             autoheight: true,
//             autorowheight: true,
//             width: '100%',
//             theme: theme,
//             source: dataAdapter,
//             editable: true,
//             showstatusbar: true,
//             showaggregates: true,
//             columnsresize:true,
//             selectionmode: 'multiplecellsadvanced',
//             columns: [
//               { text: 'Technology', datafield: 'tech', width: 120, pinned: true, align: 'left', },
//               { text: 'Unit size [MW]', datafield: 'value', cellsalign: 'right',cellsformat: 'd2', align: 'right', columntype: 'numberinput',},
//             ] 
        
//     });
//     }
// }

/*
function showDispatch(user, casename) {
    //console.log(' case '+ casename + ' user ' + user);

    var esst = new esstCase(casename, user); 
    var fuels = esst.getDispatch();
    var sortableList = '';
    for (var i = 0; i < fuels.length; i++) {
        //var imgurl = 'css/images/' + fuels[i] + '32.png';
        //var img = '<img src="' + imgurl + '"/>';
        //var element = '<div class="sortable-item" id='+'sort_'+fuels[i]+'>' + img  + fuels[i] + '</div>';
        var element = '<div class="sortable-item" id='+'sort_'+fuels[i]+'><i class="fa fa-sort orange fa-1.3x" aria-hidden="true"></i>' + fuels[i] + '</div>';
        sortableList = sortableList + element;
    }
    $("#sortable").html(sortableList);
    $("#sortable").jqxSortable();              
} */
// function esstCase(casename,user, year){
//     //console.log(user);
//     this.user = user;
//     this.filepath = "xml/" + user + '/' +casename+"/"+casename+".xml";
//     this.loadXML = function(){
//         if (window.XMLHttpRequest){
//             xhttp=new XMLHttpRequest();
//           }
//         else{
//             xhttp=new ActiveXObject("Microsoft.XMLHTTP");
//           }
//         xhttp.open("GET",this.filepath,false);
//         xhttp.send();
//         return xhttp.responseXML;
//     }
    
//     this.getDispatch = function() {
//     var result="";
//     $.ajax({
//         //url:'xml/'+ this.user + '/'+casename+'/hSimulation/dispatch.json',
//         url:'xml/'+ this.user + '/'+casename+'/hSimulation/genData.json',
//         async: false,  
//         cache: false,
//         success:function(data) {
//            // console.log('data ', data['DISPATCH'])
//         result = data['DISPATCH'][year]; 
//         }
//     });
//     return result;
//     }
    
//     this.getElmixFuels = function(){
//         xmlDoc = this.loadXML();
//         HTMLcollectionElmixGoriva=xmlDoc.getElementsByTagName("ElMix_fuels");
//         elmix_goriva = HTMLcollectionElmixGoriva[0].childNodes;
//         var elmix_fuels=[];
//         for (i=0;i<elmix_goriva.length;i++){
//             if (HTMLcollectionElmixGoriva[0].childNodes[i].nodeType==1&&HTMLcollectionElmixGoriva[0].childNodes[i].tagName!='ImportExport'){
//                 elmix_fuels.push(HTMLcollectionElmixGoriva[0].childNodes[i].tagName);
//             }
//         }
//         return elmix_fuels;
//     }
// }
// function UploadStart(event){
//     $('#loadermain h4').text('Uploading data...');
//     $('#loadermain').show();
// }
// function UploadEnd(event){
//     var args = event.args;
//     var fileName = args.file;
//     var serverResponce = args.response;
//     //let casename = fileName;
//     //console.log(fileName, serverResponce)
//     $(".info").show();
//     $(".info").html(serverResponce);
//      $.ajax({
//           method: 'POST',
//           url: 'app/hSimulation/HourlyAnalysis.php',
//           async: true,
//           dataType: 'json',
//           data: {
//             'file': fileName,
//             'ajax': true,
//             //'case': casename, 
//             'action': 'HourlyData'
//           },
//           complete: function(e) {
//                 $('#loadermain h4').text('');
//                 $('#loadermain').hide();        
//                 var serverResponce = e.responseJSON;
//                     switch (serverResponce["type"]) {
//                         case 'ERROR':
//                             $('#jqxLoader').jqxLoader('close');
//                             ShowErrorMessage(serverResponce["msg"]);
//                             break;
//                         case 'EXIST':
//                             ShowWarningMessage(serverResponce["msg"]);
//                             break;
//                         case 'SUCCESS':
//                             $('#jqxNotification').jqxNotification('closeAll'); 
//                             localStorage.setItem("pattern",  "changed");
//                             $(".noPattern").removeClass("disableForm");
//                             patternChangedMsg();
//                             $("#initPattern").hide();
//                             // ShowSuccessMessage(serverResponce["msg"]);
//                             // ShowWarningMessage("You have uploaded new hourly patterns, please calculate.");
//                             //daStatus.dataBind();

//                             //daYears.dataBind();
//                             //daFuels.dataBind();
//                             break;
//                 }  
//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 ShowErrorMessage(errorThrown);
//             }
//         });
// }

// function satisfyENSfromFuel(fuel, user, casename){
//     $('#loadermain h4').text("Satisfying ENS from "+fuel )
//     $('#loadermain').show();
//     $.ajax({
//           //url: 'app/hSimulation/importData.php?action=ENSfromFuel&fuel='+fuel,
//           method: 'POST',
//           url: 'app/hSimulation/HourlyAnalysis.php',
//           data: {case:casename, action:'ENSfromFuel', fuel: fuel },
//           async: true,
//           cache: false,
//           dataType: 'json',
//           complete: function(e) {
//             $('#loadermain h4').text('');
//             $('#loadermain').hide();
//             var serverResponce = e.responseJSON;
//             switch (serverResponce.type) {
//                 case 'ERROR':
//                     ShowErrorMessage(serverResponce.msg);
//                     break;
//                 case 'EXIST':
//                     ShowWarningMessage(serverResponce.msg);
//                     break;
//                 case 'SUCCESS':
//                     getInfoData(user, casename);                      
//                     initDisplayData(user, casename);                        
//                     $("#jqxNotification").jqxNotification("closeAll");;
//                     ShowSuccessMessage(serverResponce.msg, false);
//                     break;
//                 }  
//             },
//             error: function(jqXHR, textStatus, errorThrown) {
//                 ShowErrorMessage(errorThrown);
//             }
//     });  
// }
// function Calculate(user, casename){
//     $('#loadermain h4').text('Calculation in progress, please wait');
//     $('#loadermain').show();
//      $.ajax({
//            method: 'POST',
//            url: 'app/hSimulation/HourlyAnalysis.php',
//            data: {case:casename, action:'Calculate' },
//            async: true,
//            cache: false,
//            dataType: 'json',
//            complete: function(e) {
//              $('#loadermain h4').text('');
//              $('#loadermain').hide();
//              var serverResponce = e.responseJSON;
//              switch (serverResponce.type) {
//                  case 'ERROR':
//                      ShowErrorMessage(serverResponce.msg);
//                      break;
//                  case 'EXIST':
//                      ShowWarningMessage(serverResponce.msg);
//                      break;
//                  case 'SUCCESS':
//                      localStorage.setItem("eta",  null);
//                      localStorage.setItem("md",  null);
//                      localStorage.setItem("dispatch",  null);                      
//                      localStorage.setItem("pattern",  null);
//                      getInfoData(user, casename);                 
//                      initDisplayData(user, casename);  
//                      $("#jqxNotification").jqxNotification("closeAll");
 
//                      ShowSuccessMessage(serverResponce.msg, true);
//                      break;
//                  }  
//              },
//              error: function(jqXHR, textStatus, errorThrown) {
//                  ShowErrorMessage(errorThrown);
//              }
//      });  
//  }

//function Maintenance(user, casename){
//    $('#loadermain h4').text('Calculation in progress, please wait');
//     $('#loadermain').show();
//     $.ajax({
//           method: 'POST',
//           url: 'app/hSimulation/HourlyAnalysis.php',
//           data: {case:casename, action:'Maintenance' },
//           async: true,
//           cache: false,
//           dataType: 'json',
//           complete: function(e) {
//             $('#loadermain h4').text('');
//             $('#loadermain').hide();
//             var serverResponce = e.responseJSON;
//             switch (serverResponce.type) {
//                 case 'ERROR':
//                     ShowErrorMessage(serverResponce.msg);
//                     break;
//                 case 'EXIST':
//                     ShowWarningMessage(serverResponce.msg);
//                     break;
//                 case 'SUCCESS':
//                     localStorage.setItem("eta",  null);
//                     localStorage.setItem("md",  null);
//                     localStorage.setItem("dispatch",  null);                      
//                     localStorage.setItem("pattern",  null);
//                     getInfoData(user, casename);                      
//                     initDisplayData(user, casename);                        
//                     $("#jqxNotification").jqxNotification("closeAll");;
//                     ShowSuccessMessage(serverResponce.msg, true);
//                     break;
//                 }  
//             },
//             error: function(xmlHttpRequest, textStatus, errorThrown) {
//                 if(xmlHttpRequest.readyState == 0 || xmlHttpRequest.status == 0) 
//                     return;  
//                 else{
//                     ShowErrorMessage(errorThrown);
//                 }
//             }

//     });  
// }