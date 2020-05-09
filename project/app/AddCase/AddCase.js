$(document).ready(function (){

    var currency = ["AED","AFN","ALL","AMD","ANG","AOA","ARS","AUD","AWG","AZN","BAM","BBD","BDT","BGN","BHD","BIF","BMD","BND","BOB","BOV","BRL","BSD","BTN","BWP","BYR","BZD","CAD","CDF","CHE","CHF","CHW","CLF","CLP","CNY","COP","COU","CRC","CUP","CVE","CYP","CZK","DJF","DKK","DOP","DZD","EEK","EGP","ERN","ETB","EUR","FJD","FKP","GBP","GEL","GHS","GIP","GMD","GNF","GTQ","GYD","HKD","HNL","HRK","HTG","HUF","IDR","ILS","INR","IQD","IRR","ISK","JMD","JOD","JPY","KES","KGS","KHR","KMF","KPW","KRW","KWD","KYD","KZT","LAK","LBP","LKR","LRD","LSL","LTL","LVL","LYD","MAD","MDL","MGA","MKD","MMK","MNT","MOP","MRO","MTL","MUR","MVR","MWK","MXN","MXV","MYR","MZN","NAD","NGN","NIO","NOK","NPR","NZD","OMR","PAB","PEN","PGK","PHP","PKR","PLN","PYG","QAR","RON","RSD","RUB","RWF","SAR","SBD","SCR","SDG","SEK","SGD","SHP","SKK","SLL","SOS","SRD","STD","SYP","SZL","THB","TJS","TMM","TND","TOP","TRY","TTD","TWD","TZS","UAH","UGX","USD","USN","USS","UYU","UZS","VEB","VND","VUV","WST","XAF","XAG","XAU","XBA","XBB","XBC","XBD","XCD","XDR","XFO","XFU","XOF","XPD","XPF","XPT","XTS","XXX","YER","ZAR","ZMK","ZWD",];
    var units = ["PJ","ktoe","Mtoe","GWh"];
    var sector = ["Industry","Transport","Residential","Commercial","Agriculture","Fishing", "Non_energy_use", "Other"];
    var commodity = ["Coal","Oil","Gas","Biofuels","Waste", "Peat", "OilShale", "Electricity", "Heat"];
    var technology = ["Coal", "Oil", "Gas", "Biofuels", "Waste", "Peat", "OilShale", "Nuclear", "Geothermal", "Solar", "Wind", "Hydro"];
    var years =  ["1990","2000","2005","2010","2015","2020","2025","2030","2035","2040","2045","2050"];
    
    //getEsstCase();
    access();

    var action = getUrlVars()["action"];
    let session = getSession();
    let cs = session.case;

    //console.log(action, session);
    //console.log('edit')
    if(action == 'new'){
        renderAddCaseEmpty();
    }
    else if(action == 'edit' && typeof cs !== 'undefined'){
        getGenData(cs)
        .then(xml => {
            response = JSON.parse(xml);
            let data = response.data;
            //console.log(data);

            renderEditCase(data);
            ShowInfoMessage(response.msg);
        })
        .catch(error =>{ 
            console.log(error);
        });
    }

    //Use of getPreventDefault() is deprecated.  Use defaultPrevented instead.
    $('#Submit').on('click', function (event) {	
        event.preventDefault(); 
        buttonFlag = 'saveNew';
        $('#case').jqxValidator('validate');
    });
    
    $('#Edit').on('click', function (event) {	
        event.preventDefault(); 
        //getEsstCase();
        buttonFlag = 'saveEdit';
        $('#case').jqxValidator('validate');
    });
    
    $('#NewCS').on('click', function (event) {
        event.preventDefault();
        $('#Edit').hide();
        $('#NewCS').hide();
        $('#Submit').show();
        renderAddCaseEmpty();
    });
    
    var that = this;
    var render = function (message, input) {
       //console.log(Object.values(input));
       if (that._message) {
           that._message.remove();
       }
       that._message = $("<span class='jqx-validator-error-label' lang='"+ $.cookie("lang") +"'>" + message + "</span>")
       that._message.appendTo("#yearsselectmsg");
       return that._message;
    }
    
    $('#case').jqxValidator({
       hintType: 'label',
       animationDuration: 500,
    
       rules : [
           { input: '#casename', message: window.lang.translate("Case name is required field!"), action: 'keyup', rule: 'required' },
           { input: '#casename', message: window.lang.translate("Entered case name is not allowed!"), action: 'keyup', rule: function (input, commit) {
                       var casename = $( "#casename" ).val();
                       var result = (/^[a-zA-Z0-9-_ ]*$/.test(casename));
                       return result;
                  }
           },
            { input: '#yearsselect', message: window.lang.translate('Select at least one year'), action: 'change', hintRender: render, rule: function () {
                var elements = $('#yearsselect').find('input[type=checkbox]');
                var check = false;
                var result = $.grep(elements, function(element, index) {
                    if(element.checked==true)
                        check=true;
                    });
                return (check);
                }
            },
            { input: '#Sector', message: window.lang.translate('Select at least one sector'), action: 'change', rule: function () {
               var elements = $('#Sector').find('input[type=checkbox]');
               var check = false;
               var result = $.grep(elements, function(element, index) {
                   if(element.checked==true)
                       check=true;
                   });
               return (check);
               }
           },
           { input: '#Commodity', message: window.lang.translate('Select at least one commodity'), action: 'change', rule: function () {
               var elements = $('#Commodity').find('input[type=checkbox]');
               var check = false;
               var result = $.grep(elements, function(element, index) {
                   if(element.checked==true)
                       check=true;
                   });
               return (check);
               }
           },
           { input: '#Technology', message: window.lang.translate('Select at least one technology'), action: 'change', rule: function () {
               var elements = $('#Technology').find('input[type=checkbox]');
               var check = false;
               var result = $.grep(elements, function(element, index) {
                   if(element.checked==true)
                       check=true;
                   });
               return (check);
               }
           }
           ]
    });
    
    $('#case').on('validationSuccess', function (event) { 
        var url = "app/AddCase/AddCase.php";
        let cs = $("#casename").val();
        $.ajax({
                type: "POST",
                url: url,
                data: $("#case").serialize() + "&action="+buttonFlag, // serializes the form's elements.
                // data: $("#case").serialize() + "&Submit=send&ImportExport=true", // serializes the form's elements.
                //data: { data: $("#case").serialize(), action: 'saveNew' },
                async: false,
                success: function(e){
                     var serverResponce = JSON.parse(e);
                     //console.log(serverResponce);
                     switch (serverResponce["type"]) {
                         case 'ERROR':
                             ShowErrorMessage(serverResponce["msg"]);
                             break;
                         case 'EXIST':
                             ShowWarningMessage(serverResponce["msg"]);
                             break;
                         case 'SUCCESS':
                             ShowInfoMessage(serverResponce["msg"]);
                             $("#sidebar").load("includes/leftmenu.php");
                             setSession(cs);
                             pcas = JSON.parse(ps('getCases'));
                             renderCasePicker(pcas, cs);
                             if (buttonFlag == 'saveEdit'){
                                localStorage.setItem("P1",  "changed");
                             }
                             break;
                     } 
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    ShowErrorMessage(errorThrown);
                }
        });
         return false; // avoid to execute the actual submit of t
    });

    function  renderEditCase(genData){
        //console.log(genData);
        let yrs = [];
        $.each(  genData.Years, function( key, value ) {
            if(value == "1"){
                key=key.replace(/_/g, "");
                yrs.push(key);
            }
        });

        let scr = [];
        $.each(  genData.Sectors, function( key, value ) {
            if(value == "1"){
                scr.push(key);
            }
        });
        
        let techs = [];
        $.each(  genData.ElMix_fuels, function( key, value ) {
            if(value == "1"){
                techs.push(key);
            }
        });

        let fls = [];
        $.each(  genData.Fuels, function( key, value ) {
            if(value == "1"){
                fls.push(key);
            }
        });

        $('#Submit').hide();
        $('#NewCS').show();
        $('#Edit').show();

        $('#casename').val(genData.Case.name);
        $('#description').val(genData.Case.description);
    
         var container = $('<div />');
         $.each( sector, function( key, value ) {
            name=value.replace(/_/g, " ");
            if(scr.indexOf(value) != -1){
           //if( genData.Sectors.hasOwnProperty(value) ){
                    container.append('<div class="funkyradio"><div class="funkyradio-default"><input type="checkbox" name="Sector['+value+']" id="sector'+value+'" checked/><label for="sector'+value+'"  lang="en">'+name+'</label></div></div>');
              }
              else{
                    container.append('<div class="funkyradio"><div class="funkyradio-default"><input type="checkbox" name="Sector['+value+']" id="sector'+value+'"/><label for="sector'+value+'"  lang="en">'+name+'</label></div></div>');
              }
            });
         $('#Sector').html(container);
    
         var container = $('<div />');
         $.each( commodity, function( key, value ) {
            name=value.replace(/_/g, " ");
            if(fls.indexOf(value) != -1){
            //if( genData.Fuels.hasOwnProperty(value) ){
                    container.append('<div class="funkyradio" name="test"><div class="funkyradio-default"><input type="checkbox" name="Commodity['+value+']" id="commodity'+value+'" checked/><label for="commodity'+value+'"  lang="en">'+name+'</label></div></div>');
             }
             else{
                    container.append('<div class="funkyradio" name="test"><div class="funkyradio-default"><input type="checkbox" name="Commodity['+value+']" id="commodity'+value+'"/><label for="commodity'+value+'"  lang="en">'+name+'</label></div></div>');
             }
            });
         $('#Commodity').html(container);
    
         var container = $('<div />');
         $.each( technology, function( key, value ) {
            name=value.replace(/_/g, " ");
            if(techs.indexOf(value) != -1){
            //if( genData.ElMix_fuels.hasOwnProperty(value) ){
                    container.append('<div class="funkyradio" name="test"><div class="funkyradio-default"><input type="checkbox" name="Technology['+value+']" id="technology'+value+'" checked/><label for="technology'+value+'"  lang="en">'+name+'</label></div></div>');
             }
             else{
                    container.append('<div class="funkyradio" name="test"><div class="funkyradio-default"><input type="checkbox" name="Technology['+value+']" id="technology'+value+'"/><label for="technology'+value+'"  lang="en">'+name+'</label></div></div>');
             }
            });
         $('#Technology').html(container);
    
         var container = $('<select  class="form-control" id="currencies" name="Currency"/>');
         $.each( currency, function( key, value ) {
            if(value==genData.Case.currency)
                    container.append('<option value="'+value+'" selected name="currency'+value+'" id="'+value+'">'+value+'</option>');
            else
                    container.append('<option value="'+value+'" id="'+value+'">'+value+'</option>');
            });
         $('#Currency').html(container);
    
         var container = $('<select  class="form-control" id="units" name="Unit"/>');
         $.each( units, function( key, value ) {
             if (value== genData.Case.units){
                    container.append('<option value="'+value+'" selected id="'+value+'">'+value+'</option>');
             }
             else{
                  container.append('<option value="'+value+'" id="'+value+'">'+value+'</option>');
             }
    
        });
        $('#Unit').html(container);
    
        var container = $('<div />');
        $.each( years, function( key, value ) {
           //if( genData.Years.hasOwnProperty(name) ){
            if(yrs.indexOf(value) != -1){
                   container.append('<div class="funkyradio years" name="test"><div class="funkyradio-default"><input type="checkbox" name="Years['+value+']" id="technology'+value+'" checked/><label for="technology'+value+'"  lang="en">'+value+'</label></div></div>');
            }
            else{
                   container.append('<div class="funkyradio years" name="test"><div class="funkyradio-default"><input type="checkbox" name="Years['+value+']" id="technology'+value+'"/><label for="technology'+value+'"  lang="en">'+value+'</label></div></div>');
            }
           });

        $('#yearsselect').html(container);

        $("#datepicker").jqxDateTimeInput({width: '100%', height: 34 });

        //za promjenu unita i valute
        $( "#units" ).change(function() {
            if (action == 'edit'){
                ShowInfoMessage("Changing Unit will not recalulate results!");
            }
        });
        $( "#currencies" ).change(function() {
            console.log('curr cjanged');
            if (action == 'edit'){
                ShowInfoMessage("Changing Currency will not recalulate results!");
            }
        });
    }
    
    function  renderAddCaseEmpty(){
        $('#Submit').show();
        $('#casename').val('');
        //$('#scenarioname').val('');
        $('#description').val('');
        $('#yearsselect').empty();
        // $('#startYear').val('');
        // $('#endYear').val('');
    
       // $("#scenarioname").prop('disabled', false);
    
         var container = $('<div />');
         $.each( sector, function( key, value ) {
                name=value.replace(/_/g, " ");
                container.append('<div class="funkyradio"><div class="funkyradio-default"><input type="checkbox" name="Sector['+value+']" id="sector'+value+'" checked/><label for="sector'+value+'"  lang="en">'+name+'</label></div></div>');
            });
         $('#Sector').html(container);
    
         var container = $('<div />');
         $.each( commodity, function( key, value ) {
                //valueID=value.replace(/ /g,"_");
                name=value.replace(/_/g, " ");
                container.append('<div class="funkyradio"><div class="funkyradio-default"><input type="checkbox" name="Commodity['+value+']" id="commodity'+value+'" checked/><label for="commodity'+value+'"  lang="en">'+name+'</label></div></div>');
            });
         $('#Commodity').html(container);
    
         var container = $('<div />');
         $.each( technology, function( key, value ) {
                name=value.replace(/_/g, " ");
                container.append('<div class="funkyradio"><div class="funkyradio-default"><input type="checkbox" name="Technology['+value+']" id="technology'+value+'" checked/><label for="technology'+value+'"  lang="en">'+name+'</label></div></div>');
            });
         $('#Technology').html(container);
    
         var container = $('<select  class="form-control" name="Currency"/>');
         $.each( currency, function( key, value ) {
            if(value == "EUR")
                    container.append('<option value="'+value+'" selected name="currency'+value+'" id="'+value+'">'+value+'</option>');
            else
                    container.append('<option value="'+value+'" id="'+value+'">'+value+'</option>');
            });
         $('#Currency').html(container);
    
         var container = $('<select  class="form-control" name="Unit"/>');
         $.each( units, function( key, value ) {
                  container.append('<option value="'+value+'" id="'+value+'">'+value+'</option>');
        });
        $('#Unit').html(container);
    
        $("#datepicker").jqxDateTimeInput({width: '100%', height: '34px', });

        var container = $('<div />');
        $.each( years, function( key, value ) {
            container.append('<div class="funkyradio years"><div class="funkyradio-default"><input type="checkbox" name="Years['+value+']" id="'+value+'" checked/><label for="'+value+'">'+value+'</label></div></div>');   
        });
        $('#yearsselect').html(container);
    }

    $('#loadermain').hide("slow");
});
    