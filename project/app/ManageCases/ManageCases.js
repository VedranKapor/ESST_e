$(document).ready(function () {
    access();
    renderNavPills("visible", "visible", "hidden", "Case studies", 'Manage cases', "");
    pcas = JSON.parse(ps('getCases'));
    render(pcas);

    $('#CaseSearch').empty();
    //Search cases
    $('#CaseSearch').keyup(function () {
        var query = $.trim($('#CaseSearch').val()).toLowerCase();
        $('.pstitle').each(function () {
            var $this = $(this);
            if ($this.text().toLowerCase().indexOf(query) === -1)
                $this.closest('.panel').fadeOut();
            else $this.closest('.panel').fadeIn();
        });
    })

    $(document).delegate(".pstitle","click",function(e){
        e.stopImmediatePropagation();
        titleps=$(this).attr('data-ps');
        $.ajax({
            url:"app/app.php",
            data:{case:titleps,action:'setSession',session:'case'},
            type: 'POST',
            async: false,
            success: function (result) {
                var serverResponce = JSON.parse(result);
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        break;
                    case 'SUCCESS':
                        crossroads.ignoreState;
                        hasher.setHash('');
                        hasher.setHash('#FED_bysectors');
                        $("#sidebar").empty();
                        $("#sidebar").load("includes/leftmenu.php");
                        break;
                }
            },
            error: function(xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
    });

    //restore modal
    $("#restoreCS").click(function () {
        $.ajax({
            url: "app/ManageCases/ManageCases.php",
            data: { action: 'cleanCases' },
            type: 'POST',
            success: function (result) {
            },
            error: function (xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
    });

    $('#jqxFileUpload').on('uploadStart', function (event) {
        var fileName = event.args.file;
        //console.log(fileName);
    }); 

    $('#jqxFileUpload').jqxFileUpload({ width: 300, uploadUrl: "app/ManageCases/ManageCases.php", fileInputName: 'fileToUpload' });

    $('#jqxFileUpload').on('uploadEnd', function (event) {
        var args = event.args;
        var fileName = args.file;
        var serverResponce = JSON.parse(args.response);
        switch (serverResponce["type"]) {
            case 'ERROR':
                $('#messageup').text(serverResponce["msg"]);
                $('#messageup').addClass('jqx-validator-error-label');
                $('#messageup').show();
                break;
            case 'SUCCESS':
                render(JSON.parse(ps('getCases')));
                $('#modalrestore').modal('toggle');
                ShowInfoMessage(serverResponce["msg"]);
                break;
        }
    });

    //sakrij poruku kad se modal zatvori
    $('#modalrestore').on('hidden.bs.modal', function () {
        $('#messageup').hide();
    })
	
    $(document).delegate(".editPS","click",function(e){
        e.stopImmediatePropagation();
        titleps=$(this).attr('data-ps');
        setSession(titleps);
        hasher.setHash('');
        //hasher.setHash('#editCase');
        hasher.setHash("#addCase?action=edit");
        $("#sidebar").load("includes/leftmenu.php");
    });

    //description case
    //$(".descriptionPS").click(function () {
    $(document).delegate(".descriptionPS","click",function(e){
        e.stopImmediatePropagation();
        $('#mdescriptionps').val('');
        var titleps = $(this).attr('data-ps');
		$('#mtitleps_desc').html(titleps);
        $.ajax({
            url: "app/ManageCases/ManageCases.php",
            data: { case: titleps, action: 'getDescription' },
            type: 'POST',
            success: function (result) {
                $('#mdescriptionps').html(result);
            },
            error: function (xhr, status, error) {
                ShowErrorMessage(error);
            }
        });

    });

    $(document).delegate(".backupCS","click",function(e){
        e.stopImmediatePropagation();
        //console.log($('#loadermain').length)
        $('#loadermain').show();
        var titleps = $(this).attr('data-ps');
        $.ajax({
            url: "app/ManageCases/ManageCases.php",
            data: { case: titleps,  action: 'backupCase' },
            type: 'POST',
            async: true,
            success: function (result) {
                var serverResponce = JSON.parse(result);
                if(serverResponce.zip) {
                    
                    location.href = "app/download.php?case="+titleps;
                    $('#loadermain').hide();
                    ShowInfoMessage("Case "+ titleps + " backedup!");
                }
            },
            error: function (xhr, status, error) {
                ShowErrorMessage(error);
                $('#loadermain').hide();
            }
        });
    });

    //copy end backup case study
    //$(".copyCS").click(function () {
    $(document).delegate(".copyCS","click",function(e){
        e.stopImmediatePropagation();
        $('#loadermain').show();
        var titleps = $(this).attr('data-ps');
        $.ajax({
            url: "app/ManageCases/ManageCases.php",
            data: { case: titleps,  action: 'copyCase' },
            type: 'POST',
            async: false,
            success: function (result) {
                var serverResponce = JSON.parse(result);
                //console.log(serverResponce);
                switch (serverResponce["type"]) {
                    case 'ERROR':
                        ShowErrorMessage(serverResponce["msg"]);
                        $('#loadermain').hide();
                        break;
                    case 'SUCCESS':
                        //render(JSON.parse(ps('getCases')));
                        let pcas = JSON.parse(ps('getCases'))
                        render(pcas);
                        renderCasePicker(pcas, titleps)
                        $('#loadermain').hide();
                        ShowInfoMessage(serverResponce["msg"]);
                        break;
                }
            },
            error: function (xhr, status, error) {
                ShowErrorMessage(error);
            }
        });
    });
    $('#loadermain').hide();
});

function render(ps) {
    var htmlarr = [];
    $("#cases").empty();
    $.each(ps, function (index, value) {
        htmlstring = "";
        htmlstring += '<div class="panel panel-default">' +
            '<div class="panel-heading" style="padding-right: 0px !important;">' +
                '<table style="width: 100%;">' +
                    '<tr>' +
                        '<td>' +
                            '<b><a data-toggle="collapse" class="pstitle" data-ps="'+ value['title'] +'" style="display:block; width:100%" data-parent="#cases" id="psid_' + value['title'] + '" href="#collapse_' + value['title'].replace(/[^A-Z0-9]/ig, "") + '">' +
                            '<span class="glyphicon glyphicon-folder-close text-default"></span>   ' + value['title'] + '</a></b>' +
                        '</td>' +
                        '<td style="width:550px; text-align:left"  class="hidden-md hidden-sm hidden-xs">' +
                            '<i data-toggle="collapse" class="text-muted" style="display:block; width:100%" data-parent="#cases">' + value['desc'] + '</i>' +
                        '</td>' +
                        '<td style="width:40px; text-align:center">' +
                            '<span data-toggle="modal" data-target="#modaldescriptionps">' +
                            '<span class="descriptionPS" data-ps="' + value['title'] + '" data-toggle="tooltip" data-placement="top" title="Description">' +
                            '<span class="glyphicon glyphicon-info-sign text-info icon-btn"></span>' +
                            '</span>' +
                            '</span>' +
                        '</td>' +
						'<td style="width:40px; text-align:center">'+
							//'<span data-toggle="modal" data-target="#modaleditps">'+
							'<span class="editPS" data-ps="'+ value['title'] +'" data-toggle="tooltip" data-placement="top" title="Edit">'+
							'<span class="glyphicon glyphicon-edit text-info icon-btn"></span>'+
							//'</span>'+
							'</span>'+
						'</td>'+
                        '<td style="width:40px; text-align:center">' +
                            // '<span data-toggle="modal" data-target="#modalbackup">' +
                            '<span class="backupCS" data-ps="' + value['title'] + '"'+
                            'data-toggle="tooltip" data-placement="top" title="Backup case study" >' +
                            '<span class="glyphicon glyphicon-download-alt text-info icon-btn"></span>' +
                            '</span>' +
                            '</span> ' +
                            '</td>' +
                        '<td style="width:40px; text-align:center">' +
                            '<span data-toggle="modal" data-target="#modalcopy">' +
                            '<span class="copyCS" data-ps="' + value['title'] + '"' + 'id="copy_' + value['title'] + '"  data-toggle="tooltip" data-placement="top" title="Copy case study" >' +
                            '<span class="glyphicon glyphicon-duplicate text-info icon-btn"></span>' +
                            '</span>' +
                            '</span>' +
                        '</td>' +
                        '<td style="width:40px; text-align:center"> ' +
                            '<span>' +
                            '<span data-toggle="tooltip" data-placement="top" title="Delete planning study"' +
                            'onclick="DeletePS(\'' + value['title'] + '\')">' +
                            '<span  class="glyphicon glyphicon-trash text-danger icon-btn"></span>' +
                            '</span>' +
                            '</span>' +
                        '</td>' +
                    '</tr>' +
                '</table>' +
            '</div>' +
            '<div id="collapse_' + value['title'].replace(/[^A-Z0-9]/ig, "") + '" class="panel-collapse collapse">';
        htmlstring += 
            '</div>' +
            '</div>';
        htmlarr.push(htmlstring);
    })
    $("#cases").html(htmlarr.join(""));
}

//function for delete planninng study
function DeletePS(titleps) {
    //e.stopImmediatePropagation();
    bootbox.confirm({
        title: "<div class='jqx-validator-danger-label-big white'></i>Delete case study?</div>",
        message: "<div >Are You sure that You want to DELETE Planning Study <b class='danger'>" + titleps + "</b>?</div>",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel deletion',
                className: 'btn-success btn-sm'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm',
                className: 'btn-warning btn-sm'
            }
        },
        callback: function (result) {
            if(result){
                $.ajax({
                    url: "app/ManageCases/ManageCases.php",
                    data: { case: titleps, action: 'deleteCase'},
                    type: 'POST',
                    success: function (result) {
                        var serverResponce = JSON.parse(result);
                        //console.log(serverResponce);
                        switch (serverResponce["type"]) {
                            case 'ERROR':
                                ShowErrorMessage(serverResponce["msg"]);
                                break;
                            case 'SUCCESS':
                                let pcas = JSON.parse(ps('getCases'))
                                render(pcas);
                                renderCasePicker(pcas, titleps)
                                $("#sidebar").empty();
                                $("#sidebar").load("includes/leftmenu.php");
                                ShowInfoMessage(serverResponce["msg"]);
                                break;
                        }
                    },
                    error: function (xhr, status, error) {
                        ShowErrorMessage(error);
                    }
                });
            }
        }
    }); 

    // bootbox.confirm('Are You sure that You want to DELETE Planning Study ' + titleps + '?',
    // function (e) {
    //     if (e) {

    //     }
    // })
}
