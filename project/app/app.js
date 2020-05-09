//provjerava da li postoji results json
function FileExists(file, user, casename) {
    var  check = 0;
    $.ajax({
        url:'xml/'+ user + '/'+ casename+'/hSimulation/'+file,
        type:'HEAD',
        async: false,
        error: function() {
        check = 0;
        },
        success: function() {
        check = 1;
        }
    });
    return check;
}

function getSession() {
    $.ajax({
        data:{action:'getSession'},
        url:"app/app.php",
        async: false,  
        type: 'POST',
        success:function(data) {
            result = $.parseJSON(data); 
        }
    });
    return result;
}

function setSession(cs) {
    $.ajax({
        data:{action:'setSession', session:'case', case: cs },
        url:"app/app.php",
        async: false,  
        type: 'POST',
        success:function(data) {
            result = $.parseJSON(data); 
        }
    });
    return result;
}

function getGenData(cs) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url:"app/app.php",
            async: true,  
            type: 'POST',
            data:{action:'getGenData', case: cs },
            success:function(data) {
                resolve(data);
            },
            error: function(xhr, status, error) {
                reject(error);
            }
        });
    });
}

// function genData(casename, user) {
//     var result="";
//     $.ajax({
//         url:'xml/'+ user + '/'+casename+'/hSimulation/genData.json',
//         async: false,  
//         cache: false,
//         success:function(data) {
//             result =  data; 
//         }
//     });
//     return result;
// } 

function ps(action) {
    var result = "";
    $.ajax({
        //url: "app/ManageCases/ManageCases.php",
        url:"app/app.php",
        async: false,
        type: 'POST',
        data: { action: action },
        success: function (data) {
            result = data;
        }
    });
    return result;
}

function access() {
    $.ajax({
        url: "auth/users/users.php",
        async: false,
        data:{action:'getAccess'},
        type: 'POST',
        success: function (result) {
            var serverResponce = JSON.parse(result);
            switch (serverResponce["type"]) {
                case 'ADMINACCESS':
                    //console.log(serverResponce["msg"]);
                break;
                case 'USERACCESS':
                    //console.log(serverResponce["msg"]);
                break;
                case 'ERROR':
                    //console.log(serverResponce["msg"]);
                    window.location = 'index.html';
                break;
            }
            // console.log(result);
            // if ($.trim(result) === "-1") {
            //     window.location = 'index.html';
            // } 
            // else {
            //     arr = result.split('|');
            //     //console.log(arr);
            //     $('#user').html(arr[0]);
            //     if (arr[1] == "admin") {
            //         $('#manageusers').show();
            //     }
            // }
        }
    });
}

function renderCasePicker(pcs, cs) {
    tmp = $(document).height();
    win = $(window).height();
    h = win - 117;  //footer 23 i navbar 45 px breadcrumb 41

    $('#casePicker').attr('style', 'min-height:' + h + 'px !important;');
    
    var htmlarr = [];
    htmlarr.push("<div class='label-title' style='margin-bottom:15px'><span class='glyphicon glyphicon-folder-open esst'></span><b>  Select case study<b></div>");
    $.each(pcs, function (index, value) {
        htmlstring = "";
            if (value['title'] == cs) {
                htmlstring += '<div class="funkyradio funkyradio-cst">' +
                    '<input type="radio" class="casepicker_onClick" name="cs" data-cs="' + value['title'] + '" id="' + value['title'] +  '" checked/>' +
                    '<label for="' + value['title'] +'">' + value['title'] + '</label>' +
                    '</div>';
            }
            else {
                (value['title'] !=  cs)
                htmlstring += '<div class="funkyradio funkyradio-cst">' +
                    '<input type="radio" class="casepicker_onClick" name="cs" data-cs="' + value['title'] + '"  id="' + value['title'] +  '"/>' +
                    '<label for="' + value['title'] + '">' + value['title'] + '</label>' +
                    '</div>';
            }
  
        htmlstring += '</div>';
        htmlarr.push(htmlstring);
    })
    $("#casePicker").html(htmlarr.join(""));
}

function clearLocalStorageMsgP2(){
    localStorage.setItem("eta",  null);
    localStorage.setItem("md",  null);
    localStorage.setItem("stg",  null);
    localStorage.setItem("dispatch",  null);  
    localStorage.setItem("sync",  null);                    
    localStorage.setItem("pattern",  null);
}

function clearLocalStorageMsgP1(){                   
    localStorage.setItem("P1",  null);
}

//promjena case study
$(document).delegate(".casepicker_onClick", "click", function (e) {

    let pattern = localStorage.getItem("pattern");
    let eta = localStorage.getItem("eta");
    let md = localStorage.getItem("md");
    let stg = localStorage.getItem("stg");
    let dispatch = localStorage.getItem("dispatch");
    let sync = localStorage.getItem("sync");

    $("#jqxNotification").jqxNotification("closeAll");

    var cs = $(this).attr('data-cs');

    if(pattern=="changed" || eta =="changed" || md =="changed"|| stg =="changed" || dispatch =="changed" || sync == 'changed') {
        bootbox.confirm({
            title: "<div class='jqx-validator-warning-label-big white'></i>Simualtion setting changes!</div>",
            message: "<div >Some of the input values have benn changed. Plese run simulation to keep data and results consistent befroe changing case study.</div>",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel and run simulation',
                    className: 'btn-success btn-sm'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Change case anyway',
                    className: 'btn-warning btn-sm'
                }
            },
            callback: function (result) {
               // console.log(result);
               // console.log('This was logged in the callback: ' + result);
               if(result){
                renderCaseData();
               }
            }
        });  
    }else{
        renderCaseData();
    }

    function renderCaseData(){
        let active = localStorage.getItem("activePage");
        clearLocalStorageMsgP2();
    
        setSession(cs);
        $("#sidebar").load("includes/leftmenu.php");
        if( active != 'null' ){
            $('#loadermain').show();
            if( JSON.parse(active) != 'addCase'){
                crossroads.ignoreState  = true;
                hasher.setHash('');
                hasher.setHash('#'+JSON.parse(active));  
            }else{
                crossroads.ignoreState  = true;
                hasher.setHash('');
                hasher.setHash('#'+JSON.parse(active)+"?action=edit");             
            }
            $('#loadermain').hide("slow");
        } 
    }

});

function changepassword() {
    $('#jqxNotification').jqxNotification('closeAll');
    $('#msgPassword').hide();
    $('#msgPasswordNew').hide();

    if ($('#currentpassword').val() == '') {
       // console.log('praznooo');
        //ShowErrorMessage('Username required');
        $('#msgPassword').text('Current password is required field!');
        $('#msgPassword').addClass('jqx-validator-error-label');
        $('#msgPassword').show();
        return false;
    }

    if ($('#newpassword').val() == '') {
        //ShowErrorMessage('Password required');
        $('#msgPasswordNew').text('New username is required field!');
        $('#msgPasswordNew').addClass('jqx-validator-error-label');
        $('#msgPasswordNew').show();
        return false;
    }
    $.ajax({
        url: "auth/users/users.php",
        async: false,
        type: 'POST',
        data: { action: 'changePassword', userID: 'null', currentpassword: $('#currentpassword').val(), newpassword: $('#newpassword').val() },
        success: function (data) {
            var serverResponce = JSON.parse(data);
            switch (serverResponce["type"]) {
                case 'ERRORCurrent':
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    //ShowErrorMessage(serverResponce["msg"]);
                    $('#msgPassword').text(serverResponce["msg"]);
                    $('#msgPassword').addClass('jqx-validator-error-label');
                    $('#msgPassword').show();
                break;
                case 'ERRORNew':
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    //ShowErrorMessage(serverResponce["msg"]);
                    $('#msgPasswordNew').text(serverResponce["msg"]);
                    $('#msgPasswordNew').addClass('jqx-validator-error-label');
                    $('#msgPasswordNew').show();
                break;
                case 'SUCCESS':
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    ShowInfoMessage(serverResponce["msg"]);
                    $('#changePassModal').modal('hide');
                break;
            }
        }
    });
}

function changepasswordAdmin() {
    var username =$('#usernameHid').val();
    $('#jqxNotification').jqxNotification('closeAll');
    $('#msgPassword').hide();
    $('#msgPasswordNew').hide();

    if ($('#currentpassword').val() == '') {
        //console.log('postojii '+ $('#msgPassword').length);
        //ShowErrorMessage('Username required');
        $('#msgPassword').text('Current password is required field!');
        $('#msgPassword').addClass('jqx-validator-error-label');
        $('#msgPassword').show();
        return false;
    }

    if ($('#newpassword').val() == '') {
        //ShowErrorMessage('Password required');
        $('#msgPasswordNew').text('New username is required field!');
        $('#msgPasswordNew').addClass('jqx-validator-error-label');
        $('#msgPasswordNew').show();
        return false;
    }
    $.ajax({
        url: "auth/users/users.php",
        async: false,
        type: 'POST',
        data: { action: 'changePassword', userID: username, currentpassword: $('#currentpassword').val(), newpassword: $('#newpassword').val() },
        success: function (data) {
            var serverResponce = JSON.parse(data);
            switch (serverResponce["type"]) {
                case 'ERRORCurrent':
               // console.log(serverResponce);
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    //ShowErrorMessage(serverResponce["msg"]);
                    $('#msgPassword').text(serverResponce["msg"]);
                    $('#msgPassword').addClass('jqx-validator-error-label');
                    $('#msgPassword').show();
                break;
                case 'ERRORNew':
               // console.log(serverResponce);
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    //ShowErrorMessage(serverResponce["msg"]);
                    $('#msgPasswordNew').text(serverResponce["msg"]);
                    $('#msgPasswordNew').addClass('jqx-validator-error-label');
                    $('#msgPasswordNew').show();
                break;
                case 'SUCCESS':
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    ShowInfoMessage(serverResponce["msg"]);
                    $('#changePassModal').modal('hide');
                break;
            }
        }
    });
}

function renderNavPills(l1Status, l2Status, l3Status, l1Label, l2Label,l3Label){

    $("#l1").css("visibility", l1Status);
    $("#l1").html('<i class="ace-icon fa fa-folder-open-o home-icon"></i><span lang="en">'+ l1Label +'</span>');
    $("#l1").addClass('active');

    $("#l2").css("visibility", l2Status);
    $("#l2").html(l2Label);
    $("#l2").addClass('active');

    $("#l3").css("visibility", l3Status);
    $("#l3").html(l3Label);
    $("#l3").addClass('active');
    return true;
}

$(document).ready(function () {
    session = getSession();
    //console.log(session);
    if(session.gr == 'admin'){
        $('#userManage').show();
    }

    let cs = session.case;

    //console.log(session);
    pcas = JSON.parse(ps('getCases'));
    //console.log(pcas);

    renderCasePicker(pcas, cs)

    //sakrij poruku kad se modal zatvori
    $('#changePassModal').on('hidden.bs.modal', function () {
        //console.log('hide msgs app.js')
        $('#changepasswordUser').show();
        $('#changepasswordAdmin').hide();
        $('#msgPassword').hide();
        $('#msgPasswordNew').hide();
        $('#currentpassword').val('')
        $('#newpassword').val('')
    });
});

$(document).delegate("#sidebar-collapse", "click",function(e) {
        setTimeout(function () {
        $(window).trigger('resize');
    }, 500);
}); 
    
$("#sidebar").on("click",function(e) {
        setTimeout(function () {
        $(window).trigger('resize');
    }, 500);
});

$(document).delegate(".orange2","click",function(e){
        setTimeout(function () {
        $(window).trigger('resize');
    }, 300);
});
   
var theme = 'bootstrap';
function ShowErrorMessage(message) {
    $("#jqxNotification").jqxNotification({ browserBoundsOffset: 50, height: 120, opacity: 1, position: "top-right", autoClose: false, autoCloseDelay: 4000, showCloseButton: true, template: "error", width: '18%', theme:theme });
    $("#jqxNotificationContent").text(message);
    $("#jqxNotification").jqxNotification("open");
}
function ShowInfoMessage(message) {
    $("#jqxNotification").jqxNotification({ browserBoundsOffset: 50, height: 120, opacity: 1, position: "top-right", autoClose: true, autoCloseDelay: 4000, showCloseButton: true, template: "info", width: '18%', theme:theme });
    $("#jqxNotificationContent").text(message);
    $("#jqxNotification").jqxNotification("open");
}
function ShowWarningMessage(message) {
    $("#jqxNotification").jqxNotification({ browserBoundsOffset: 50, height: 120,  opacity: 1,position: "top-right", autoClose: false, autoCloseDelay: 4000, showCloseButton: true, template: "warning", width: '18%', theme:theme });
    $("#jqxNotificationContent").html(message);
    $("#jqxNotification").jqxNotification("open");
}
function ShowSuccessMessage(message, autoClose) {
    $("#jqxNotification").jqxNotification({ browserBoundsOffset: 50, height: 120, opacity: 1, position: "top-right", autoClose: autoClose, autoCloseDelay: 4000, showCloseButton: true, template: "success", width: '18%', theme:theme });
    $("#jqxNotificationContent").text(message);
    $("#jqxNotification").jqxNotification("open");
}
function ShowDefaultMessage(message, autoClose) {
    $("#jqxNotification").jqxNotification({ browserBoundsOffset: 50, height: 120, opacity: 1, position: "top-right", autoClose: false, autoCloseDelay: 4000, showCloseButton: true, template: "null", width: '18%', theme:theme });
    $("#jqxNotificationContent").html(message);
    $("#jqxNotification").jqxNotification("open");
}
