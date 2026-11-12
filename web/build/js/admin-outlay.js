"use strict";


alert('ok');

function inter_init() {

    $.ajax({
    url: '/yii2-consumption/outlay/ajaxhandler',
    type: 'POST',
	dataType: "json", 
    data: {
        param1: 'value1',
        param2: 'value2',
        _csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2
    },
    success: function(response) {
        // Handle the successful response from the server
        console.log(response);
		alert(response);
    },
    error: function(xhr, status, error) {
        // Handle any errors during the AJAX request
        console.error(xhr.responseText);
    } 
});

}


window.onload=inter_init;
