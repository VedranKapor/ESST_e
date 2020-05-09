$(document).ready(function () {
    //$.getScript("./phase2/HourlyAnalysisInit.js");
    access();
    let session = getSession();
    let casename = session.case;
    let user = session.us;

    //ukoliko je case odabran
    if (typeof casename != 'undefined') {
        //$('.ImportHA').css('visibility', 'visible');
        infoData = getInfoData(user, casename);
        initPageTitle(casename);

         //$('#jqxFileUpload').jqxFileUpload({uploadUrl: 'app/hSimulation/controler.php', fileInputName: 'fileToUpload', theme: theme });
         let xmlDoc = getXML(casename, user);
         var currency = getCurrency(xmlDoc);
         let years = getYears(xmlDoc);
         let yrs = getYearsIndexes(years);

        //  console.log(casename, user, yrs[0])
        let ElMix = getElMixFuels(xmlDoc);
        //console.log(ElMix)
        //  let techs = getDispatch(casename, user, yrs[0]);
        let techs = getTechs(ElMix);
        techs.push('Storage');
        // console.log('techs ',techs);

         let srcYears=[];
         let yearsArr = [];

         Object.keys(years).forEach(function(year) {
             if(years[year] == '1'){
                 let tmp = {};
                 tmp['year'] = year;
                 srcYears.push(tmp);
                 yearsArr.push(year);
             }
         });

         var daYears = new $.jqx.dataAdapter(srcYears);

         var source = {
             datatype: "json",
             datafields: [
                 { name: 'LabelName' },
                 { name: 'LabelID' }
             ],
             url: 'includes/labels.json',
             async: false,
         };
         var daTech = new $.jqx.dataAdapter(source);

        //dio za display podataka
        $("#ddlYears").jqxDropDownList({
            source: daYears,
            selectedIndex: 0,
            theme: theme,
            displayMember: 'year',
            height: 20,
            width:90,
            autoDropDownHeight: true
         });

        $("#ddlTech").jqxDropDownList({
            selectedIndex: 0,
            source: daTech,
            displayMember: "LabelName",
            valueMember: "LabelID",
            height: 20,
            width: 220,
            autoDropDownHeight: true,
            theme: theme
        });

        //upload hData
        // $('#jqxFileUpload').on('uploadStart', UploadStart);
        // $('#jqxFileUpload').on('uploadEnd', UploadEnd);

        //modal loading on demand
        $('#eta').on('shown.bs.modal', function(){ eta(user,casename, daYears) });

        $('#HData').on('shown.bs.modal', function(){ GridHourlyInputBeta(casename,user, daYears); });
        $('#HData').on('hidden.bs.modal', function () {  $('#btnSaveHD').hide(); $("#msgBasic").hide(); });

        //$('#MDuration').on('shown.bs.modal', function(){ MaintenanceDuration(user, casename); });

        $('#FORMOR').on('shown.bs.modal', function(){ FORMOR(user, casename); });
        $("#btnSaveFORMOR").on('click',saveFORMOR);

        $('#WinHourlyData').on('shown.bs.modal', function(){ GridHourlyInfo(user, casename, techs, daYears); });

        $('#ElMixP2').on('shown.bs.modal', function(){ GridElMixP2(user, casename); });

        $('#LCOE').on('shown.bs.modal', function(){ getLCOE(user, casename, techs, currency); getAUC(user, casename, techs, currency);});
        $('#LCOE').on('hidden.bs.modal', function () {        
            //$('#jqxLCOE_p2').empty();
            $('#jqxChartLCOE_p2').empty();  
            //$('#jqxAUC_p2').empty();
            $('#jqxChartAUC_p2').empty();
        });

        //$('#AUC').on('shown.bs.modal', function(){ getAUC(user, casename, techs, currency); });

        $('#MCoS').on('shown.bs.modal', function(){ getMCoS(user, casename, yearsArr); });
        $('#MCoS').on('hidden.bs.modal', function () {        
            $('#jqxMCoSChart').empty();
            //$('#jqxMCoS').empty();  
        });

        $('#CO2').on('shown.bs.modal', function(){ getCO2(user, casename, techs, currency); });

        $('#STG').on('shown.bs.modal', function(){ getSTG(casename, user, currency); });
        $("#btnSaveSTG").on('click', function() {
            saveSTG(yearsArr);
        });
        //actions
        $( "#btnCalc" ).on( "click", function() {
            let storage = $('#chbStorage').is(":checked");
            let maintenance = $('#chbMaintenance').is(":checked");
            Calculate(user, casename, maintenance, storage);
        });

        $("#btnDispatch").on('click',function() { showDispatch(user, casename, daYears, currency) });

        $( "#btnSaveDispatch" ).on( "click", function() {
            let chbDispatch = $('#chbDispatch').is(":checked");
            //console.log(chbDispatch);
            saveDispatch(chbDispatch);
        });

        //$("#btnSaveEta").on('click',saveEta);
        //$("#btnSaveHD").on('click',saveHData);
        $('#btnSaveHD').on('click', function() {
            let allYears = $('#chbAllYear').is(":checked");
            saveHData(allYears, yearsArr);
        });

        $('#btnadjustCF').on('click', function() {
            let storage = $('#chbStorage').is(":checked");
            let maintenance = $('#chbMaintenance').is(":checked");
            adjustCF(user, casename, storage, maintenance);
        });

        $("#btncheckInputPhase1").on('click',checkInputPhase1);

        $("#btnSync").on('click',sync);

        $("#showLog").click(function (e) {
            e.preventDefault();
            $('#log').html(`
                <div class='bs-callout bs-callout-primary' style="margin:10px 0px 10px 0px">
                    <h4>${DEF.SIM.title}</h4>
                    ${DEF.SIM.definition}
                </div>
            `);
            $('#log').toggle('slow');
        });

        // $("#btnSaveEta").unbind().click(function() {
        //     saveEta();
        // });

        //technology za ENS
        // var technology = $("#ddlTechnology").jqxDropDownList('getSelectedItem').value;
        // $("#ddlTechnology").on('change', function (event){
        //     var item = args.item;
        //     technology = item.value;
        // });

        //$('#btnENSfromFuel').on('click', function() {satisfyENSfromFuel(technology, user, casename)});

        //ako su  napunjani svi podaci
        if (infoData['HourlyData'] && infoData['ResultData']) { initDisplayData(user, casename); }
        // else {
        //     $('#jqxLoader').jqxLoader('close');
        // }
    } //ukoliko case nije odabran else dio
    else {
        //$("#selectCase").css("display", "block");
        ShowWarningMessage('Please Select case for hourly analysis!');
        // $("#btnCase").text('Confirm');
        // $("#btnCase").jqxButton({ width: '120px', height:23, theme: theme });
        // $('#btnCase').on('click', getCase);
        // $("#ddlCases").jqxDropDownList({ source: daCasess, selectedIndex: 0, theme: theme, displayMember: 'case', width: '250px' });
    }
  $('#loadermain').hide();
});