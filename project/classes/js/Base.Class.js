var fuel_obj = new Object();
 fuel_obj['electricity'] = 'Electricity';
 fuel_obj['coal'] = 'Coal';
 fuel_obj['hydro'] ='Hydro';
 fuel_obj['oil'] = 'Oil';
 fuel_obj['gas'] = 'Gas';
 fuel_obj['biofuels'] = 'Biofuels';
 fuel_obj['heat'] = 'Heat';
 fuel_obj['peat'] = 'Peat';
 fuel_obj['waste'] = 'Waste';
 fuel_obj['oilshale'] = 'Oil shale';
 fuel_obj['solar'] = 'Solar';
 fuel_obj['wind'] = 'Wind';
 fuel_obj['geothermal'] = 'Geothermal';
 fuel_obj['nuclear'] = 'Nuclear';
 fuel_obj['importexport'] = 'Import/Export';

 var sector_obj = {};
 sector_obj['Industry'] = "Industry";
 sector_obj['Transport'] = "Transport";
 sector_obj['Residential'] = "Residential";
 sector_obj['Commercial'] = "Commercial";
 sector_obj['Agriculture'] = "Agriculture";
 sector_obj['Fishing'] = "Fishing";
 sector_obj['Non_energy_use'] = "Non-energy use";
 sector_obj['Other'] = "Other";

 //colors
var clElectricity = '#ffcc66';
var clHydro = '#3a7db9';
var clCoal = '#4a251c';
var clOil = '#cf483c';
var clGas = '#9999ff';
var clBiofuels = '#79a342';
var clPeat = '#864433';
var clWaste = '#516d2c';
var clHeat = '#666666'
var clOil_Shale = '#cf483c';
var clSolar = '#9eea32';
var clWind = '#88c92b';
var clGeothermal = '#e3ee9f';
var clNuclear = '#f4a909';
var clImportExport = '#001f3f';
var clStorage = '#7575a3';
var clDemand = 'red';
var clMarginalCost = '#404040'

var color_obj = {};
 color_obj['Electricity'] = '#ffcc66';
 color_obj['Hydro'] = '#3a7db9';
 color_obj['Coal'] = '#4a251c';
 color_obj['Oil'] = '#cf483c';
 color_obj['Gas'] = '#9999ff';
 color_obj['Biofuels'] = '#79a342';
 color_obj['Peat'] = '#864433';
 color_obj['Waste'] = '#516d2c';
 color_obj['Heat'] = '#666666'
 color_obj['Oil_shale'] = '#cf483c';
 color_obj['Solar'] = '#9eea32';
 color_obj['Wind'] = '#88c92b';
 color_obj['Geothermal'] = '#e3ee9f';
 color_obj['Nuclear'] = '#f4a909';
 color_obj['ImportExport'] = '#001f3f';
 color_obj['Storage'] = '#7575a3';
 color_obj['Unserved demand'] = '#ffcc66';

 const CHART_TYPE = {
    stepline: "stepline",
    barChart: "column",
    lineChart: 'spline',
    areaChart: 'stackedsteparea',
    stackedChart: 'stackedcolumn',
    stackedChart100: 'stackedcolumn100',
    candleStick: 'candlestick'
}

const ENERGY_CONVERTER = {
    PJ_ktoe: 23.8845897,
    PJ_Mtoe:  0.0238845897,
    PJ_PJ: 1,
    PJ_GWh: 1000/3.6,

    ktoe_ktoe: 1,
    ktoe_Mtoe: 0.001,
    ktoe_GWh: 11.63,
    ktoe_PJ: 0.041868,

    Mtoe_ktoe: 1000,
    Mtoe_Mtoe: 1,
    Mtoe_GWh: 11630,
    Mtoe_PJ: 41.868,

    GWh_ktoe: 0.0859845228,
    GWh_Mtoe: 0.0000859845228,
    GWh_GWh: 1,
    GWh_PJ: 0.0036,

}

 // Read a page's GET URL variables and return them as an associative array.
function getUrlVars(){
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function loadXMLDoc(dname){
    let res;
    $.ajax({
        type: "GET" ,
        url: dname ,
        dataType: "xml" ,
        cache: false,
        async:false,
        success: function(xml) {
            //var xmlDoc = $.parseXML( xml );   <------------------this line
            //console.log(xml);
            res = xml;
        }       
    });
    return res;
}

function loadXMLDoc_(dname){
    if (window.XMLHttpRequest){
        xhttp=new XMLHttpRequest();
    }else{
        xhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
           xhttp.open("GET",dname,false);
           xhttp.send();

    return xhttp.responseXML;
} 

 function getXML(casename, user){
    var filepath = "xml/" + user+ '/'+casename+"/"+casename+".xml";
    xmlDoc = loadXMLDoc(filepath);
    return xmlDoc;
}
////////////////////**********15.11.2013.*****////////////////////////        
//funkcija za prvaljenje niza godina citajuci xml i popunjava niz   //
//sa indexom godine i vrijednoscu 1 ili 0 u zavisnosti              //
//da li je godina izabrana ili ne prilikom kreiranja scenarija      //
//////////////////////////////////////////////////////////////////////

function getYears(xmlDoc){
    // var filepath = "xml/"+casename+"/"+casename+".xml";
    //xmlDoc=loadXMLDoc(filepath);
    HTMLcollectionGodine=xmlDoc.getElementsByTagName("Years");
    godine = HTMLcollectionGodine[0].childNodes;
    var years=new Object();  
    
    for (i=0;i<godine.length;i++)    {
        if (HTMLcollectionGodine[0].childNodes[i].nodeType==1) {
           // if(HTMLcollectionGodine[0].childNodes[i].firstChild.nodeValue == "1")
            years[HTMLcollectionGodine[0].childNodes[i].tagName.substr(1)]= HTMLcollectionGodine[0].childNodes[i].firstChild.nodeValue;
        }
    }
    return years;
}

////////////////////**********15.11.2013.*****///////////////////////        
//funkcija za prvaljenje niza goriva koji cine udjele u            //
//proizvodnji elektricne energije citajuci xml i popunjava niz     //
//sa indexom imena goriva i vrijednoscu 1 ili 0 u zavisnosti       //
//da li je gorivo izabran ili ne prilikom kreiranja scenarija      //
//nizovi elmix_fuels se koriste dinamickih validatora i jqx inputa //
/////////////////////////////////////////////////////////////////////

function getElMixFuels(xmlDoc){
    //var filepath = "xml/"+casename+"/"+casename+".xml";
    //xmlDoc=loadXMLDoc(filepath);
    HTMLcollectionElmixGoriva=xmlDoc.getElementsByTagName("ElMix_fuels");
    elmix_goriva = HTMLcollectionElmixGoriva[0].childNodes;
    var elmix_fuels=new Object();
    for (i=0;i<elmix_goriva.length;i++)    {
        if (HTMLcollectionElmixGoriva[0].childNodes[i].nodeType==1)        {
            elmix_fuels[HTMLcollectionElmixGoriva[0].childNodes[i].tagName]= HTMLcollectionElmixGoriva[0].childNodes[i].firstChild.nodeValue;
        }
    }
    return elmix_fuels;
}

 ////////////////////**********15.11.2013.*****/////////////////////////        
//funkcija za prvaljenje niza sektora citajuci xml i popunjava niz   //
//sa indexom imena sektora i vrijednoscu 1 ili 0 u zavisnosti        //
//da li je sektor izabran ili ne prilikom kreiranja scenarija        //
//nizovi sectors se koriste dinamickih validatora i jqx inputa       //
///////////////////////////////////////////////////////////////////////
function getSectors(xmlDoc){
    //napuni nizive years, sectors, fuels radi dinamickih validatora i jqx inputa
    //var filepath = "xml/"+casename+"/"+casename+".xml";
    //xmlDoc=loadXMLDoc(filepath);
    HTMLcollectionSektori=xmlDoc.getElementsByTagName("Sectors");
    sektori = HTMLcollectionSektori[0].childNodes;

    var sectors=new Object();
    for (i=0;i<sektori.length;i++)    {
        if (HTMLcollectionSektori[0].childNodes[i].nodeType==1)        {
            sectors[HTMLcollectionSektori[0].childNodes[i].tagName]= HTMLcollectionSektori[0].childNodes[i].firstChild.nodeValue;
        }
    }
    return sectors;
}

////////////////////**********15.11.2013.*****/////////////////////////        
//funkcija za prvaljenje niza goriva citajuci xml i popunjava niz    //
//sa indexom imena goriva i vrijednoscu 1 ili 0 u zavisnosti         //
//da li je gorivo izabran ili ne prilikom kreiranja scenarija        //
//nizovi fuels se koriste dinamickih validatora i jqx inputa         //
///////////////////////////////////////////////////////////////////////

function getFuels(xmlDoc){
    //var filepath = "xml/"+casename+"/"+casename+".xml";
    //xmlDoc=loadXMLDoc(filepath);
    HTMLcollectionGoriva=xmlDoc.getElementsByTagName("Fuels");
    goriva = HTMLcollectionGoriva[0].childNodes;
    var fuels=new Object();
    for (i=0;i<goriva.length;i++)    {
        if (HTMLcollectionGoriva[0].childNodes[i].nodeType==1)        {
            fuels[HTMLcollectionGoriva[0].childNodes[i].tagName]= HTMLcollectionGoriva[0].childNodes[i].firstChild.nodeValue;
        }
    }
    return fuels;
}

function getCurrency(xmlDoc){
    currency=xmlDoc.getElementsByTagName("currency");
    return currency[0].firstChild.nodeValue;;
}

function getUnit(xmlDoc){
    unit=xmlDoc.getElementsByTagName("units");
    return unit[0].firstChild.nodeValue;
}

function getYearsIndexes(years){
    //years = getYears();
    var Indexes = [];
    for (key in years)    {
        if(years[key] == 1 )        {
            Indexes.push(key); 
        }
    }
    return  Indexes;
}

function getTechs(elmix){
    //years = getYears();
    var Indexes = [];
    for (key in elmix)    {
        if(elmix[key] == 1 )        {
            Indexes.push(key); 
        }
    }
    return  Indexes;
}

//years columns editable za share i decimal values
function getColYr_display_onlyDecimal( years, unit) {
    var colY_e = [];
    window.d = 2;
    window.decimal = 'd' + d;

    let aggregatesrenderer = function(aggregates, column, element, summaryData) { 
        //console.log('sum ', aggregates['sum'])
        // let agregat = parseFloat(aggregates['sum'].replace(/,/g, ''), 5);
        //console.log('agregat ', agregat)
        let result =  aggregates['sum'];
        var value = $.jqx.dataFormat.formatnumber(result, window.decimal);
        return result = '<span style="margin: 4px;">' +value + '</span>';
    }

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) { 
        var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
        return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    };

    var  kolone ={ text: unit, datafield:'name', minwidth:120, maxwidth:150, pinned: true, editable: false, align: 'right', };
    colY_e.push(kolone);

    for (var key in years){    
        if(years[key] == 1 ){
            kolone ={
                    text:key,
                    datafield:key,
                    minwidth: 100,
                    aggregates: ['sum'],
                    align: 'right',
                    cellsalign: 'right',
                    aggregatesrenderer: aggregatesrenderer,
                    cellsrenderer:cellsrenderer
            }
            colY_e.push(kolone);
        }
    }
    return colY_e;
}

//years columns editable za share i decimal values
function getColYr_e($div, years, type, unit) {
    var colY_e = [];

    window.factor = 1;
    window.d = 2;
    if(type=="d"){
        window.decimal = 'd' + d;
    }
    else if(type=='p'){
        window.decimal = 'p' + d;
    }

    let aggregatesrenderer = function(aggregates, column, element, summaryData) { 
        //console.log('sum ', aggregates['sum'])
        let agregat = parseFloat(aggregates['sum'].replace(/,/g, ''), 5);
        //console.log('agregat ', agregat)
        let result = agregat * window.factor;
        //let value = result;
        var value = $.jqx.dataFormat.formatnumber(result, window.decimal);
       //value = $.jqx.dataFormat.formatnumber(parseFloat(aggregates['sum'].replace(/,/g, '')), window.decimal);
        if( aggregates['sum'] > 100 && type=='p'){
            return result = '<span style="margin: 4px;"><i class="fa fa-exclamation-triangle danger" aria-hidden="true"></i>' +value + '</span>';
        }
        else if( aggregates['sum'] < 100 && type=='p'){
            return result = '<span style="margin: 4px;"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i>' +value + '</span>';
        }
        else if( aggregates['sum'] == 100 && type=='p'){
            return result = '<span style="margin: 4px;"><i class="fa fa-check-square-o success" aria-hidden="true"></i>' +value + '</span>';
        }
        else if (type == 'd'){
            return result = '<span style="margin: 4px;">' +value + '</span>';
        }
    }
    let validation = function (cell, value) {
        var ie = $div.jqxGrid('getcellvalue', cell.row, "name");
        if (value < 0 && ie != 'Import/Export'  && type== 'p') {
            return { result: false, message: 'Value must be positive!' };
        }
        else if( value > 100 && type== 'p'){
            return { result: false, message:"Share must be less than 100!" };
        }
        else if (value < 0 && type== 'd') {
            return { result: false, message: 'Value must be positive!' };
        }else{
            return true;
        }
    }

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
        value = value * window.factor;
        // if(columnfield == '2045'){
        //     console.log(value, window.factor, value*window.factor)
        // }   
        var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
        return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    };
    let initeditor = function (row, cellvalue, editor) {
        editor.jqxNumberInput({ decimalDigits: window.d });
    }

    var  kolone ={ text: unit, datafield:'name', minwidth:120, maxwidth:150, pinned: true, editable: false, align: 'right', };
    colY_e.push(kolone);

    for (var key in years){    
        if(years[key] == 1 ){
            kolone ={
                    text:key,
                    datafield:key,
                    minwidth: 120,
                    aggregates: ['sum'],
                    align: 'right',
                    cellsalign: 'right',
                    cellsformat: 'd2',
                    columntype: 'numberinput',
                    aggregatesrenderer: aggregatesrenderer,
                    initeditor: initeditor,
                    validation: validation,
                    cellsrenderer:cellsrenderer
            }
            colY_e.push(kolone);
        }
    }
    return colY_e;
}

function getColUD( years, unit) {
    var colY_e = [];

    window.factor = 1;
    window.d = 2;
    window.decimal = 'd' + d;

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
        value = value * window.factor;
        var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
        return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    };

    var  kolone ={ text: unit, datafield:'name', minwidth:120, maxwidth:150, pinned: true, editable: false, align: 'right', };
    colY_e.push(kolone);

    for (var key in years){    
        if(years[key] == 1 ){
            kolone ={
                    text:key,
                    datafield:key,
                    minwidth: 100,
                    align: 'right',
                    cellsalign: 'right',
                    cellsformat: 'd2',
                    cellsrenderer:cellsrenderer
            }
            colY_e.push(kolone);
        }
    }
    return colY_e;
}

function getColStats( periods, unit, type) {
    var colStats = [];

    window.d = 2;
    window.decimal = 'd' + d;

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
        var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
        return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    };

    let aggregatesrenderer = function(aggregates, column, element, summaryData) { 
        let agregat = parseFloat(aggregates['sum'].replace(/,/g, ''), window.d);
        let result = agregat;
        var value = $.jqx.dataFormat.formatnumber(result, window.decimal);
        return result = '<span style="margin: 4px;">' +value + '</span>';

    }

    var  kolone ={ text: unit, datafield:'name', minwidth:120, maxwidth:150, pinned: true, editable: false, align: 'right', };
    colStats.push(kolone);

    for(let i=1; i <= periods; i++){
        kolone ={
            text: type + ' ' +i,
            datafield:i,
            minwidth: 100,
            aggregates: ['sum'],
            align: 'right',
            cellsalign: 'right',
            cellsformat: 'd2',
            cellsrenderer:cellsrenderer,
            aggregatesrenderer: aggregatesrenderer,
        }
        colStats.push(kolone);
    }
    return colStats;
}
//years columns editable za share i decimal values
function getColYr_e_simple( years, unit) {
    var colY_e = [];

    let validation = function (cell, value) {
        if (value < 0) {
            return { result: false, message: 'Value must be positive!' };
        }else{
            return true;
        }
    }

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
        var formattedValue = $.jqx.dataFormat.formatnumber(value, 'd2');
        return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    };

    let initeditor = function (row, cellvalue, editor) {
        editor.jqxNumberInput({ decimalDigits: 4 });
    }

    var  kolone ={ text: unit, datafield:'name', minwidth:120, pinned: true, editable: false, align: 'right', };
    colY_e.push(kolone);

    for (var key in years){    
        if(years[key] == 1 ){
            kolone ={
                    text:key,
                    datafield:key,
                    minwidth: 100,
                    aggregates: ['sum'],
                    align: 'right',
                    cellsalign: 'right',
                    cellsformat: 'd2',
                    columntype: 'numberinput',
                    initeditor: initeditor,
                    validation: validation,
                    cellsrenderer:cellsrenderer
            }
            colY_e.push(kolone);
        }
    }
    return colY_e;
}

function getYearsEditableColumns(years, unit) {
    var columns_years_editable1 = [];
    flag = false;
    window.d = 2;
    window.decimal = 'd' + d;

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
            var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
            return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    }; 
    let initeditor = function (row, cellvalue, editor) {
        editor.jqxNumberInput({ decimalDigits: window.d });
    }
    //var widthLength = 100 / (brojKolona + 1);
    for (var key in years){    
        if(years[key] == 1 )    {
            if (!flag)        {
                var  kolone ={
                    text:unit,
                    datafield:'name',
                    //width: widthLength+"%",
                    minwidth: 120,
                    pinned: true,
                    editable: false,
                    align: 'right',
                };
                flag = true;
                columns_years_editable1.push(kolone);
            }
        
                kolone ={
                        text:key,
                        datafield:key,
                        minwidth: 100,
                        // width:120,
                        //width: widthLength+"%",
                        aggregates: ['sum'],
                        align: 'right',
                        cellsalign: 'right',
                        cellsformat: 'd2',
                        columntype: 'numberinput',
                        aggregatesrenderer: function (aggregates, column, element, summaryData) {                                  
                                    return '<span style="margin: 4px;">' + aggregates['sum'] + '</span>';
                                },
                        initeditor: initeditor,
                        validation: function (cell, value) {
                                        if (value < 0) {
                                            return { result: false, message: value_should_be_positive };
                                        }
                                        return true;
                                        },
                        cellsrenderer:cellsrenderer
                        }
                                        
                columns_years_editable1.push(kolone);
        }
    }
    return columns_years_editable1;
}

//years columns for shares
function getYearsEditableColumns2(years) {
    var columns_years_editable = [];
    flag = false;

    window.d = 2;
    window.decimal = 'd' + d;
    window.percentage = 'p' + d;

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
        var formattedValue = $.jqx.dataFormat.formatnumber(value, window.percentage);
        return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    };
    let initeditor = function (row, cellvalue, editor) {
        editor.jqxNumberInput({ decimalDigits: window.d });
    }
    let aggregatesrenderer = function(aggregates, column, element, summaryData) {         
        value = $.jqx.dataFormat.formatnumber(aggregates['sum'], window.percentage);
        if( value > 100){
            return result = '<span style="margin: 4px;"><i class="fa fa-exclamation-triangle warning" aria-hidden="true"></i>' +value + '</span>';
        }else{
            return result = '<span style="margin: 4px;"><i class="fa fa-check-square-o success" aria-hidden="true"></i>' +value + '</span>';
        }
    }

    //var column = Object.keys(years).length;
    //var widthLength = 100 / (brojKolona + 1);
    for (var key in years){    
        if(years[key] == 1 )    {
            if (!flag)        {
                var  kolone ={
                    //text:unit,
                    text: '[%]',
                   // width: widthLength+"%",
                    datafield:'name',
                    minwidth:120,
                    //maxWidth:220,
                    pinned: true,
                    editable: false,
                    align: 'right',
                };
                flag = true;
                columns_years_editable.push(kolone);
            }
        
                kolone ={
                        text:key,
                        datafield:key,
                        minwidth: 100,
                        // width:120,
                        //width: widthLength+"%",
                        aggregates: ['sum'],
                        align: 'right',
                        cellsalign: 'right',
                        cellsformat: 'd2',
                        columntype: 'numberinput',
                        aggregatesrenderer: aggregatesrenderer,
                        initeditor: initeditor,
                        validation: function (cell, value) {
                                var ie = $('#jqxgrid_trans').jqxGrid('getcellvalue', cell.row, "name");
                                //console.log('negative')
                                if (value < 0 && ie != 'Import/Export') {
                                    return { result: false, message: value_should_be_positive };
                                }
                                else if((value >100) ){
                                    return { result: false, message:"Share must be less than 100!" };
                                }
                                return true;
                        },
                        cellsrenderer:cellsrenderer
                    }
            
            columns_years_editable.push(kolone);
        }
    }
    return columns_years_editable;
}

function getSectorSeries(sectors){
    // var sectors = getSectors();
    // napraviti serije za grafikone po sektoriam
    series_sectors = [];
    for (var key in sectors){    
        if(sectors[key] == 1 )    {
               // console.log(sector_obj[key], window.lang.translate(sector_obj[key]))
            var  serija ={
                    dataField:key,
                    displayText: window.lang.translate(sector_obj[key])
            };
            series_sectors.push(serija);
        }
    }
    return series_sectors;
}

function getYearsSeries(years){
    // var years = getYears();
    // napraviti serije za grafikone po godinama
    var series_years = [];
    for (var key in years) {    
        if(years[key] == 1 ) {
            var  serija ={
                    dataField:key,
                    displayText:key
            };
            series_years.push(serija);
        }
    }
    return series_years;
}

function getFuelSeries(fuels){
    // napraviti serije za grafikone po gorivima finalnim
    series_fuels = [];
    for (var key in fuels)
    {    
        if(fuels[key] == 1 )
        {
            var key1 = key.toLowerCase();  
            //console.log(key1,fuel_obj[key1]);      
            var  serija ={
                    dataField:key,
                    displayText:fuel_obj[key1],
                    //color:color_obj[key]
                    fillColor:color_obj[key],
                    lineColor:color_obj[key],
                    lineColorSelected: darkerColor(String(color_obj[key]),.2),
                    fillColorSelected:  darkerColor(String(color_obj[key]),.2)
            };
            series_fuels.push(serija);
        }
    }
    return series_fuels;
}

function getFuelSeriesDomProduction(fuels){
    // napraviti serije za grafikone po gorivima finalnim
    series_fuels = [];
    for (var key in fuels){    
        if(fuels[key] == 1 && key != "Electricity" && key != "Heat") {
            var key1 = key.toLowerCase();  
            //console.log(key1,fuel_obj[key1]);      
            var  serija ={
                    dataField:key,
                    displayText:fuel_obj[key1],
                    //color:color_obj[key]
                    fillColor:color_obj[key],
                    lineColor:color_obj[key],
                    lineColorSelected: darkerColor(String(color_obj[key]),.2),
                    fillColorSelected:  darkerColor(String(color_obj[key]),.2)
            };
            series_fuels.push(serija);
        }
    }
    return series_fuels;
}

function getElMixFuelSeries(elmix_fuels){
    //console.log('elmix_fuels', elmix_fuels);
    // napraviti serije za grafikone po gorivima finalnim
    series_elmix_fuels = [];
    for (var key in elmix_fuels) {
        if(elmix_fuels[key] == 1 ) {
            var key1 = key.toLowerCase();
            var  serija ={
                    dataField:key,
                    displayText:fuel_obj[key1],
                    fillColor:color_obj[key],
                    lineColor:color_obj[key],
                    lineColorSelected: darkerColor(String(color_obj[key]),.2),
                    fillColorSelected:  darkerColor(String(color_obj[key]),.2)
            };
            series_elmix_fuels.push(serija);
    
        }
    }
    //console.log('series_elmix_fuels', series_elmix_fuels);
    return series_elmix_fuels;
}

function getTechsSeries(techs, labels){
    //console.log('elmix_fuels', elmix_fuels);
    // napraviti serije za grafikone po gorivima finalnim
    series_elmix_fuels = [];

    techs.forEach(function(tech) {
       // console.log(tech);
        if(tech != 'ImportExport'){
            var  serija ={
                dataField:tech,
                displayText:tech,
                fillColor:color_obj[tech],
                lineColor:color_obj[tech],
                lineColorSelected: darkerColor(String(color_obj[tech]),.2),
                fillColorSelected:  darkerColor(String(color_obj[tech]),.2),
                // labels: {
                //     visible: true,
                //     verticalAlignment: 'center',
                //     offset: { x: 0, y: 0 },
                //     angle: 90
                // },
                // formatFunction: function (value, index, series) {
                //     //console.log(value.toFixed(2));
                //     //console.log('series', series, 'index', index)
                //     if(!isNaN(value) && value != 0){
                //         return series.displayText + ' ' + value.toFixed(2);
                //         //return value.toFixed(2);
                //     }
                // }

            };
            if(labels){
                serija['labels'] = {
                    visible: true,
                    verticalAlignment: 'center',
                    offset: { x: 0, y: 0 },
                    angle: 90
                },
                serija['formatFunction'] = function (value, index, series) {
                    //console.log(value.toFixed(2));
                    //console.log('series', series, 'index', index)
                    if(!isNaN(value) && value != 0){
                        return series.displayText + ' ' + value.toFixed(2);
                        //return value.toFixed(2);
                    }
                }
            }
            series_elmix_fuels.push(serija);
        }

    });
    return series_elmix_fuels;
}

function getElMixFuelSeriesWOImportExport(elmix_fuels){
    
    series_elmix_fuels = [];
    for (var key in elmix_fuels) {    
        if(elmix_fuels[key] == 1 && key!='ImportExport' ) {
            var key1 = key.toLowerCase();        
            var  serija ={
                    dataField:key,
                    displayText:fuel_obj[key1],
                    //color:color_obj[key]
                    fillColor:color_obj[key],
                    lineColor:color_obj[key],
                    lineColorSelected: darkerColor(String(color_obj[key]),.2),
                    fillColorSelected:  darkerColor(String(color_obj[key]),.2)
            };
            series_elmix_fuels.push(serija);
        }
    }
    return series_elmix_fuels;
}

function getYearsCoulmns(years, unit, brojKolona){
    window.d = 2;
    window.decimal = 'd' + d;
    window.factor = 1;

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
            value = value * window.factor;
           //console.log(value, window.factor);
            var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
            return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    }; 

    agr = function (aggregates, column, element, summaryData) {    
        let agregat = parseFloat(aggregates['sum'].replace(",", ""),window.d);
        let result = agregat * window.factor;
        //console.log( result, window.factor); 
        var formattedValue = $.jqx.dataFormat.formatnumber(result, window.decimal);
        return '<span style="margin: 4px;" class="esst">' + formattedValue + '</span>';
    }

    //var column = Object.keys(years).length;
    var widthLength = 100 / (brojKolona + 1);
    // napraviti kolone za gridove po godinama
    var columns_years = [];
    flag = false;
    if (!flag){
        var kolone ={
            text:unit,
            //text: "",
            datafield:'name',
            minwidth:120,
            width: widthLength+"%",
            pinned: true
        };
        columns_years.push(kolone);
        flag = true;
    }

    for (var key in years){
        if(years[key] == 1 )    {
            kolone ={
                    text:key,
                    datafield:key,
                    width: widthLength+"%",
                    aggregates: ['sum'],
                    cellsalign: 'right',
                    cellsformat: 'f2',
                    align: 'right', 
                    //aggregatesrenderer: agr,
                    cellsrenderer:cellsrenderer
            };
            columns_years.push(kolone);
        }   
    }
    return columns_years;
}

function getYearsColumnsRes(years, unit){

    agr = function (aggregates, column, element, summaryData) {                                  
        return '<span style="margin: 4px;" class="esst">' + aggregates['sum'] + '</span>';
    }
    // napraviti kolone za gridove po godinama
    var columns_years = [];
    flag = false;
    if (!flag){
        var kolone ={
            text:unit,
            datafield:'name',
            width:225,
            pinned: true
        };
        columns_years.push(kolone);
        flag = true;
    }

    for (var key in years){
        if(years[key] == 1 )    {
            kolone ={
                    text:key,
                    datafield:key,
                    // width:80,
                    aggregates: ['sum'],
                    cellsalign: 'right',
                    cellsformat: 'f2',
                    cellclassname: 'esst',
                    align: 'right', 
                    aggregatesrenderer: agr
            };
            columns_years.push(kolone);
        }   
    }
    return columns_years;
}

function getGroupedLCOECoulmns(years, unit, brojKolona){
    window.d = 2;
    window.decimal = 'd' + d;
    window.factor = 1;

    let cellsrenderer = function(row, columnfield, value, defaulthtml, columnproperties) {
            value = value * window.factor;
           //console.log(value, window.factor);
            var formattedValue = $.jqx.dataFormat.formatnumber(value, window.decimal);
            return '<span style="margin: 4px; float:right; ">' + formattedValue + '</span>';
    }; 

    agr = function (aggregates, column, element, summaryData) {    
        let agregat = parseFloat(aggregates['sum'].replace(",", ""),window.d);
        let result = agregat * window.factor;
        //console.log( result, window.factor); 
        var formattedValue = $.jqx.dataFormat.formatnumber(result, window.decimal);
        return '<span style="margin: 4px;" class="esst">' + formattedValue + '</span>';
    }

    //var column = Object.keys(years).length;
    //var widthLength = 100 / (5*brojKolona + 1);
    // napraviti kolone za gridove po godinama
    var columns_years = [];

    var kolone ={ text:unit, datafield:'name',minwidth: 80,maxwidth:220, pinned: true };
    columns_years.push(kolone);

    for (var key in years){
        if(years[key] == 1 )    {
            INV ={ text:'INV', datafield:'INV'+key, minwidth: 80,maxwidth:220,aggregates: ['sum'], cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: key, aggregatesrenderer: agr, cellsrenderer:cellsrenderer };
            columns_years.push(INV);

            FOM ={ text:'FOM', datafield:'FOM'+key, minwidth: 80,maxwidth:220, aggregates: ['sum'], cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: key, aggregatesrenderer: agr, cellsrenderer:cellsrenderer };
            columns_years.push(FOM);

            VOM ={ text:'VOM', datafield:'VOM'+key,minwidth: 80,maxwidth:220, aggregates: ['sum'], cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: key, aggregatesrenderer: agr, cellsrenderer:cellsrenderer };
            columns_years.push(VOM);

            FC ={ text:'FC ', datafield:'FC'+key, minwidth: 80,maxwidth:220, aggregates: ['sum'], cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: key, aggregatesrenderer: agr, cellsrenderer:cellsrenderer };
            columns_years.push(FC);

            CO2 ={ text:'CO2', datafield:'CO2'+key, minwidth: 80,maxwidth:220, aggregates: ['sum'], cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: key, aggregatesrenderer: agr, cellsrenderer:cellsrenderer };
            columns_years.push(CO2);

            Total ={ text:'Total', datafield:'Total'+key, minwidth: 80,maxwidth:220, aggregates: ['sum'], cellsalign: 'right', cellsformat: 'f2', align: 'right', columngroup: key, aggregatesrenderer: agr, cellsrenderer:cellsrenderer };
            columns_years.push(Total);
        }   
    }
    return columns_years;
}

function getLCOEGroups(years){

    // napraviti kolone za gridove po godinama
    var groups = [];

    for (var key in years){
        if(years[key] == 1 )    {
            let INV = { text: key, name: key, align: 'center' };
            groups.push(INV);
            // // let INV = { text: key, name: 'INV'+key, align: 'center' };
            // // groups.push(INV);
            // // let FOM = { text: key, name: 'FOM'+key, align: 'center' };
            // // groups.push(FOM);
            // // let VOM = { text: key, name: 'VOM'+key, align: 'center' };
            // // groups.push(VOM);
            // // let FC = { text: key, name: 'FC'+key, align: 'center' };
            // // groups.push(FC);
            // // let CO2 = { text: key, name: 'CO2'+key, align: 'center' };
            // // groups.push(CO2);

        }   
    }
    return groups;
}

function getGroupLCOESeries(years,elmix_fuels){
    let groups = [];
   
    for (var key in years){
        if(years[key] == 1 ){
           
            for (var key in elmix_fuels) {    
                if(elmix_fuels[key] == 1 && key!='ImportExport' ) {
                    var key1 = key.toLowerCase();    
                    let series = [];

                    var INV ={ dataField:'INV'+key, displayText:fuel_obj[key1] + ' INV' };
                    series.push(INV);

                    var FOM ={ dataField:'FOM'+key, displayText:fuel_obj[key1] + ' FOM'};
                    series.push(FOM);

                    var VOM ={ dataField:'VOM'+key, displayText:fuel_obj[key1] + ' VOM'};
                    series.push(VOM);

                    var FC ={ dataField:'FC'+key, displayText:fuel_obj[key1] + ' FC'};
                    series.push(FC);

                    var CO2 = { dataField:'CO2'+key, displayText:fuel_obj[key1] + ' CO2'};
                    series.push(CO2);

                    let seriesgroup = { type: 'stackedcolumn', columnsGapPercent: 5, seriesGapPercent: 5, series: series };
                    groups.push(seriesgroup);
                }
            }

        }
    }

    return groups;
}
