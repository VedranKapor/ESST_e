$(document).ready(function () {
    Cookies.remove('titlecs');
    Cookies.remove('lm');

    $('#login').on('click', function (event) {
        var username = $('#username').val();
        var password = $('#password').val();
        $.ajax({
            url: "auth/login/login.php",
            async: true,
            data: { 'username': username, 'password': password },
            type: 'POST',
            
            success: function (result) {                  
                var serverResponce = JSON.parse(result);
                switch (serverResponce["type"]) {
                    case 'SUCCESS':
                    window.location.href = "esst.html";
                        break;
                    case 'ERROR':
                        ShowErrorMessage('Invalid username or password!');
                        break;
                }
            }
        }); 
    });
})
