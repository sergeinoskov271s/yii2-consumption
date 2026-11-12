"use strict";


alert('ok');

function inter_init() {

    $.ajax({
		url: 'http://192.168.1.1/yii2-consumption/outlay/ajaxhandler',
		type: 'POST',
	//	dataType: "json", 
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


	var objSelectD=document.getElementById('searchform-pole');
//    removeAllOptions(objSelectD);
    $('#searchform-pole').remove();
	var ar =[{value:1,text:'value 1'},{value:2, tect:'value2'}];
    for(var i=0;i<ar.length;i++){
		var optn=document.createElement('option');
		optn.text=ar[i];
		optn.value=i;
	//	objSelectD.options.add(optn);        
    }


}


window.onload=inter_init;
