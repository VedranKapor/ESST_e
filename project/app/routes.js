// Define the routes
crossroads.addRoute('/', function() {
    localStorage.setItem("activePage",  null);
    //$(".page-content").load('includes/home.html');
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  null);
    $('#loadermain').show();
    $(".page-content").load('app/ManageCases/ManageCases.html');
});

crossroads.addRoute('/Logout', function() {
    $.ajax({
        url:"app/app.php",
        data:{action:'resetSession'},
        type: 'POST',
        async: false,
        success: function (result) {
            var serverResponce = JSON.parse(result);
            switch (serverResponce["type"]) {
                case 'ERROR':
                    ShowErrorMessage(serverResponce["msg"]);
                    break;
                case 'SUCCESS':
                    break;
            }
        },
        error: function(xhr, status, error) {
            ShowErrorMessage(error);
        }
    });
    window.location.href = "index.html";
});

crossroads.addRoute('/changePassword', function() {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  null);
    $(".page-content").load('auth/views/changepassword.html');
});

crossroads.addRoute('/users', function() {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  null);
    $(".page-content").load('auth/users/users.html');
});
crossroads.addRoute('/Info', function() {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  null);
    $(".page-content").load('includes/info.html');
});
crossroads.addRoute('/ManageCases', function() {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  null);
    $('#loadermain').show();
    $(".page-content").load('app/ManageCases/ManageCases.html');
});
crossroads.addRoute('/editCase', function() {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  JSON.stringify('EditCase'));
    $('#loadermain').show();
    $(".page-content").load('app/EditCase/EditCase.html');
});
crossroads.addRoute('/addCase{?query}', function(query) {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  JSON.stringify('addCase'));
    $('#loadermain').show();
    $(".page-content").load('app/AddCase/AddCase.html' );
});

crossroads.addRoute('/TechData', function() {
    crossroads.ignoreState = false;
    localStorage.setItem("activePage",  JSON.stringify('TechData'));
    $('#loadermain').show();
    $(".page-content").load('app/TechData/TechData.html');
});

crossroads.addRoute('/FED_bysectors', function() {
    crossroads.ignoreState = false;
    $('#loadermain').show();
    localStorage.setItem("activePage",  JSON.stringify('FED_bysectors'));
    $(".page-content").load('views/FED_bysectors.php');
});

crossroads.addRoute('/FED_fuelshares.php{?query}', function(query) {
        crossroads.ignoreState = false;
        $.ajax({
            url:"app/app.php",
            data:{sector:query.sector,action:'setSession',session:'sector'},
            type: 'POST',
            async: false,
            success: function (result) {
                var serverResponce = JSON.parse(result);
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        break;
                    case 'SUCCESS':
                        $('#loadermain').show();
                        localStorage.setItem("activePage",  JSON.stringify('FED_fuelshares.php?sector=' + query.sector ));
                        $(".page-content").load('views/FED_fuelshares.php');
                        break;
                }
            },
            error: function(xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
});

crossroads.addRoute('/FED_fuelshares_values.php{?query}', function(query) {
    crossroads.ignoreState = false;
        $.ajax({
            url:"app/app.php",
            data:{sector:query.sector,action:'setSession',session:'sector'},
            type: 'POST',
            async: false,
            success: function (result) {
                var serverResponce = JSON.parse(result);
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        break;
                    case 'SUCCESS':
                        $('#loadermain').show();
                        localStorage.setItem("activePage",  JSON.stringify('FED_fuelshares_values.php?sector=' + query.sector));
                            $(".page-content").load('views/FED_fuelshares_values.php');
                        break;
                }
            },
            error: function(xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
});

crossroads.addRoute('/FED_fuelshares_total', function() {
    $('#loadermain').show();
    localStorage.setItem("activePage",  JSON.stringify('FED_fuelshares_total'));
   $(".page-content").load('views/FED_fuelshares_total.php');
});

crossroads.addRoute('/FED_losses', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('FED_losses'));
    $(".page-content").load('views/FED_losses.php');
});
crossroads.addRoute('/SecondaryEnergySupplies', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('SecondaryEnergySupplies'));
    $(".page-content").load('views/SecondaryEnergySupplies.php');
});
crossroads.addRoute('/SES_elmix', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('SES_elmix'));
    $(".page-content").load('views/SES_elmix.php');
});
crossroads.addRoute('/SES_elmix_values', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('SES_elmix_values'));
    $(".page-content").load('views/SES_elmix_values.php');
});
crossroads.addRoute('/PES_tpes', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('PES_tpes'));
    $(".page-content").load('views/PES_tpes.php');
});
crossroads.addRoute('/PES_domestic_production', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('PES_domestic_production'));
    $(".page-content").load('views/PES_domestic_production.php');
});

crossroads.addRoute('/TRA_technology', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('TRA_technology'));
    $(".page-content").load('views/TRA_technology.php');
});
crossroads.addRoute('/TRA_report', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('TRA_report'));
    $(".page-content").load('views/TRA_report.php');
});
crossroads.addRoute('/TRA_el_gen_emissions', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('TRA_el_gen_emissions'));
    $(".page-content").load('views/TRA_el_gen_emissions.php');
});
crossroads.addRoute('/TRA_environment_co2', function() {
     $('#loadermain').show();
     localStorage.setItem("activePage",  JSON.stringify('TRA_environment_co2'));
    $(".page-content").load('views/TRA_environment_co2.php');
});

crossroads.addRoute('/EnergyBalance.php{?query}', function(query) {
    crossroads.ignoreState = false;
        $.ajax({
            url:"app/app.php",
            data:{year:query.year,action:'setSession',session:'year'},
            type: 'POST',
            async: false,
            success: function (result) {
                var serverResponce = JSON.parse(result);
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        break;
                    case 'SUCCESS':
                        $('#loadermain').show();
                        localStorage.setItem("activePage",  JSON.stringify('EnergyBalance.php?year=' + query.year));
                            $(".page-content").load('views/EnergyBalance.php');
                        break;
                }
            },
            error: function(xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
});

crossroads.addRoute('/Sankey.php{?query}', function(query) {
    crossroads.ignoreState = false;
        $.ajax({
            url:"app/app.php",
            data:{year:query.year,action:'setSession',session:'year'},
            type: 'POST',
            async: false,
            success: function (result) {
                var serverResponce = JSON.parse(result);
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        break;
                    case 'SUCCESS':
                        $('#loadermain').show();
                        localStorage.setItem("activePage",  JSON.stringify('Sankey.php?year=' + query.year));
                            $(".page-content").load('views/Sankey.php');
                        break;
                }
            },
            error: function(xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
});

crossroads.addRoute('/DE_elmix', function() {
    $('#loadermain').show();
    localStorage.setItem("activePage",  JSON.stringify('DE_elmix'));
    $(".page-content").load('views/DE_elmix.php');
});

crossroads.addRoute('/HourlyAnalysis', function() {
    crossroads.ignoreState = false;
    $('#loadermain').show();
    localStorage.setItem("activePage",  JSON.stringify('HourlyAnalysis'));
    $(".page-content").load('app/hSimulation/HourlyAnalysis.html');
});

crossroads.addRoute('/ResultsRaw', function(userId) {
    $(".page-content").load('Classes/RowResults.php');
});

crossroads.addRoute('/Docs', function(userId) {
    localStorage.setItem("activePage",  JSON.stringify('Docs'));
    $(".page-content").load('includes/Docs.html');
});

crossroads.addRoute('/reportByYears', function(userId) {
    localStorage.setItem("activePage",  JSON.stringify('reportByYears'));
    $(".page-content").load('app/ReportByYears/reportByYears.html');
});
crossroads.addRoute('/Stats', function(userId) {
    localStorage.setItem("activePage",  JSON.stringify('Stats'));
    $(".page-content").load('app/Stats/Stats.html');
});
crossroads.bypassed.add(function(request) {
    console.error(request + ' seems to be a dead end...');
});

//Listen to hash changes
window.addEventListener("hashchange", function() {
    var route = '/';
    var hash = window.location.hash;
    if (hash.length > 0) {
        route = hash.split('#').pop();
    }
    crossroads.parse(route);
});

// trigger hashchange on first page load
window.dispatchEvent(new CustomEvent("hashchange"));
