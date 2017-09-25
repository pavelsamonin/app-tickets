$(document).ready(function () {
    var url = 'https://app-tickets.herokuapp.com/isoft/api/'+route;
    var redirect;

    switch (route){
        case 'addTransaction' :
            redirect = 'showTransactions';
            break;
        case 'addCustomer':
            redirect = 'showCustomers';
            break;
        default : '';
    }

    $('#contact-form').on('submit', function(event) {
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            method: 'post',
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
                document.location.replace('https://app-tickets.herokuapp.com/isoft/main/' + redirect);
            }
        });
    });

    var buttonsEdit = document.querySelectorAll('button[data-method="edit"]');
    var buttonsDelete = document.querySelectorAll('button[data-method="delete"]');
    buttonsEdit.forEach(function (t) {
        t.addEventListener('click', function () {
            listenFunc(this, this.getAttribute('data-attr'), this.getAttribute('data-method'));
        });
    });
    buttonsDelete.forEach(function (t) {
        t.addEventListener('click', function () {
            listenFunc(this, this.getAttribute('data-attr'), this.getAttribute('data-method'));
        });
    });

    function listenFunc(_this, id, method) {
        if (method == 'edit') {
            var parent = _this.parentNode.parentNode.parentNode;
            var amount = parent.getAttribute('data-amount');
            var data_id = parent.getAttribute('data-id');
            document.getElementById('id').value = data_id;
            document.getElementById('amount').value = amount;

            var old_element = document.getElementById("editSubmit");
            var new_element = old_element.cloneNode(true);
            old_element.parentNode.replaceChild(new_element, old_element);

            document.getElementById('editSubmit').addEventListener('click', function () {
                editRequest(id, document.getElementById('amount').value)
            }, false);
        }
        if (method == 'delete') {
            var projectId = _this.parentNode.getAttribute('data-projectid');

            var old_element = document.getElementById("deleteSubmit");
            var new_element = old_element.cloneNode(true);
            old_element.parentNode.replaceChild(new_element, old_element);

            document.getElementById('deleteSubmit').addEventListener('click', function () {
                deleteRequest(id)
            }, false);
        }
    }

    function editRequest(id,amount) {
        var url = 'https://app-tickets.herokuapp.com/isoft/api/updateTransaction';
        var redirect = 'showTransactions';
        var data = {
            'amount' : amount,
            'transactionId' : id
        };
        $.ajax({
            method: 'put',
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
            success: function(response) {
                // success handler
                document.location.replace('https://app-tickets.herokuapp.com/isoft/main/' + redirect);
            }
        });
    }
    function deleteRequest(id) {
        var url = 'https://app-tickets.herokuapp.com/isoft/api/deleteTransaction';
        var redirect = 'showTransactions';
        var data = {
            'transactionId' : id
        };
        $.ajax({
            method: 'delete',
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
            success: function(response) {
                // success handler
                document.location.replace('https://app-tickets.herokuapp.com/isoft/main/' + redirect);
            }
        });
    }
});

