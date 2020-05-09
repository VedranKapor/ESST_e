$(document).ready(function () {
    access();
    render();

    //sakrij poruku kad se modal zatvori
    $('#addUserModal').on('hidden.bs.modal', function () {
        $('#msgUsername').hide();
        $('#msgPassword').hide();
        $('#username').val('');
        $('#password').val('');
    });
})



function adduser() {
    $('#jqxNotification').jqxNotification('closeAll');
    $('#msgUsernameAdd').hide();
    $('#msgPasswordAdd').hide();
    if ($('#username').val() == '') {
        //ShowErrorMessage('Username required');
        $('#msgUsernameAdd').text('Username is required field!');
        $('#msgUsernameAdd').addClass('jqx-validator-error-label');
        $('#msgUsernameAdd').show();
        return false;
    }

    if ($('#password').val() == '') {
        //ShowErrorMessage('Password required');
        $('#msgPasswordAdd').text('Password is required field!');
        $('#msgPasswordAdd').addClass('jqx-validator-error-label');
        $('#msgPasswordAdd').show();
        return false;
    }

    $.ajax({
        url: "auth/users/users.php",
        async: false,
        type: 'POST',
        //data: { action: 'addUser', username: $('#username').val(), password: $('#password').val(), usergroup: $("input[name='usergroup']:checked").val() },
        data: { action: 'addUser', username: $('#username').val(), password: $('#password').val(), usergroup: 'user' },
        success: function (data) {
            var serverResponce = JSON.parse(data);
            switch (serverResponce["type"]) {
                case 'ERRORCurrent':
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    //ShowErrorMessage(serverResponce["msg"]);
                    $('#msgUsernameAdd').text(serverResponce["msg"]);
                    $('#msgUsernameAdd').addClass('jqx-validator-error-label');
                    $('#msgUsernameAdd').show();
                break;
                case 'ERRORNew':
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    //ShowErrorMessage(serverResponce["msg"]);
                    $('#msgPasswordAdd').text(serverResponce["msg"]);
                    $('#msgPasswordAdd').addClass('jqx-validator-error-label');
                    $('#msgPasswordAdd').show();
                break;
                case 'SUCCESS':
                    $('#jqxNotification').jqxNotification('closeAll'); 
                    ShowInfoMessage(serverResponce["msg"]);
                    render();
                    $('#addUserModal').modal('hide');
                break;
            }






            // var serverResponce = JSON.parse(data);
            // switch (serverResponce["type"]) {
            //     case 'ERROR':
            //         $('#jqxNotification').jqxNotification('closeAll'); 
            //         //ShowErrorMessage(serverResponce["msg"]);
            //         $('#msgUsernameAdd').text(serverResponce["msg"]);
            //         $('#msgUsernameAdd').addClass('jqx-validator-error-label');
            //         $('#msgUsernameAdd').show();
            //         break;
            //     case 'SUCCESS':
            //         $('#jqxNotification').jqxNotification('closeAll'); 
            //         render();
            //         ShowInfoMessage(serverResponce["msg"]);
            //         $('#addUserModal').modal('hide');
            //         break;
            // }
        }
    });
}

//function for delete user
function DeleteUser(username) {
    bootbox.confirm('Are You sure that You want to DELETE user ' + username + ', with all case studies?',
    function (e) {
        if (e) {
            $.ajax({
                url: "auth/users/users.php",
                data: { action: 'deleteUser', username: username },
                type: 'POST',
                success: function (result) {
                    var serverResponce = JSON.parse(result);
                    //console.log(serverResponce);
                    switch (serverResponce["type"]) {
                        case 'ERROR':
                            ShowErrorMessage(serverResponce["msg"]);
                            break;
                        case 'SUCCESS':
                            render();
                            ShowInfoMessage(serverResponce["msg"]);
                            break;
                    }
                },
                error: function (xhr, status, error) {
                    ShowErrorMessage(error);
                }
            });
        }
    })
}

    //$(".editPS").click(function() {
$(document).delegate(".changePass","click",function(e){
    //e.stopImmediatePropagation();
    username=$(this).attr('data-ps');
    //console.log('klik na tableu sa userima, username =' + username);
    $('#usernameHid').val(username);
    $('#changepasswordUser').hide();
    $('#changepasswordAdmin').show();
});

// // logout
// function logout() {
//     $.ajax({
//         url: "models/logout.php",
//         async: false,
//         type: 'POST',
//         success: function (data) {
//             if ($.trim(data) === "1") {
//                 window.location = 'index.html';
//             }
//         }
//     });
// }

// check access
// function access() {
//     $.ajax({
//         url: "auth/users/users.php",
//         async: false,
//         data:{action:'getAccess'},
//         type: 'POST',
//         success: function (result) {
//             if ($.trim(result) === "-1") {
//                 window.location = 'index.html';
//             } 
//             // else {
//             //     arr = result.split('|');
//             //     //console.log(arr);
//             //     $('#user').html(arr[0]);
//             //     if (arr[1] == "admin") {
//             //         $('#manageusers').show();
//             //     }
//             // }
//         }
//     });
// }

function getUsers() {
    $.ajax({
        url: 'auth/us.json',
        async: false,
        type: 'POST',
        success: function (data) {
            result = data;
        }
    });
    return result;
}

function render() {
    ps = getUsers();
    //console.log(ps);
    var htmlarr = [];
    $("#esstUsers").empty();
    $.each(ps, function (index, value) {
       // console.log('index ' + index + ' username ' + value['username'] + ' group '+ value['gr']);
        if( value['usergroup'] != 'admin'){
            htmlstring = "";
            htmlstring += '<div class="panel panel-default">' +
                '<div class="panel-heading" style="padding-right: 0px !important;">' +
                    '<table style="width: 100%;">' +
                        '<tr>' +
                            '<td>' +
                                '<b><a data-toggle="collapse" class="pstitle" data-ps="'+ value['username'] +'" style="display:block; width:100%" ">' +
                                '<i class="ace-icon fa fa-user-circle-o bigger-130"></i>' + value['username'] + '</a></b>' +
                            '</td>' +
                            '<td style="width:40px; text-align:center">'+
                                '<span data-toggle="modal" data-target="#changePassModal">'+
                                '<span class="changePass" data-ps="'+ value['username'] +'" data-toggle="tooltip" data-placement="top" title="Change Password">'+
                                '<i class="ace-icon fa fa-pencil text-info icon-btn bigger-150">'+
                                '</span>'+
                            '</td>'+
                            '<td style="width:40px; text-align:center"> ' +
                                '<span>' +
                                '<span data-toggle="tooltip" data-placement="top" title="Delete user"' +
                                'onclick="DeleteUser(\'' + value['username'] + '\')">' +
                                '<i class="ace-icon fa fa-trash-o text-danger icon-btn bigger-150">'+   
                                '</span>' +
                                '</span>' +
                            '</td>' +
                        '</tr>' +
                    '</table>' +
                '</div>'+
            '</div>';
            htmlarr.push(htmlstring);
        }
    })
    $("#esstUsers").html(htmlarr.join(""));
}

