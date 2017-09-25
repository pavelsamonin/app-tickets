$(document).ready(function () {
    var url = 'http://localhost:8888/isoft/auth/token_post';

    $('#login-form').on('submit', function(event) {
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: 'post',
            url: url,
            data : data,
            beforeSend : function(xhr) {
                // set header if JWT is set
                if (window.sessionStorage.token) {
                    xhr.setRequestHeader("Authorization", window.sessionStorage.token);
                }

            },
            error : function(err) {
                // error handler
                console.log(err.responseJSON)
            },
            success: function(data) {
                // success handler
                window.sessionStorage.token = data.token;
                document.location.replace('http://localhost:8888/isoft/main/showTransactions');
            }
        });
    });
});

