"use strict";
var path = [];
path = location.toString().split('\/');
//var pathAjax = path[0]+'//'+path[2]+'/'+path[3]+'/ajax/'
var pathAjax = path[3];
//console.log(pathAjax);


function inter_init() {
	var objSelectD=document.getElementById('searchform-order');
 	var orderValue=objSelectD[objSelectD.selectedIndex].value;
 	//console.error(xhr.responseText);
    // $.ajax({
	// 	url: 'http://192.168.1.1/yii2-consumption/json/outlayadmin/ajaxhandler',
	// 	//url:"/"+pathAjax+"/json/outlay/ajaxhandler",
	// 	type: 'POST',
	// 	data: {
	// 		order: orderValue,
	// 		param2: '',
	// 		_csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2
	// 	},
	// 	dataType: "json",
	// 	success: function(response) {
	// 		// Handle the successful response from the server
	// 		var result=new Array();
	// 		for (const [key, value] of Object.entries(response)) {
			  
	// 		  result.push({value:`${key}`,text:`${value}`});
	// 		}
	// 		createPoleSelect(result) 
	// 	},
	// 	error: function(xhr, status, error) {
	// 		// Handle any errors during the AJAX request
	// 		console.error(xhr.responseText);
	// 	} 
	// });
	

$("#cat.typeahead").typeahead({
		onSelect: function (item) {
			console.log(item);
		},
		ajax: {
			url: "http://192.168.1.1/yii2-consumption/json/outlayadmin/ajaxhandler",
			timeout: 500,
			displayField: "message",
			triggerLength: 1,
			method: "post",
			loadingClass: "loading-circle",
			preDispatch: function (query) {
				return {
					search: query,
					_csrf: yii.getCsrfToken(),
					order: 'cat',
					//param2: 'cat',
				}
			},
			preProcess: function (data) {
				console.log(data);				
				//showLoadingMask(false);
				if (data.success === false) {
					// Hide the list, there was some error
					return false;
				}
				// We good!
				var result = new Array();
				for (const [key, value] of Object.entries(data)) {
					result.push({ message: `${value}` });
				}
				console.log(result);
				return result;
			}
		}
	}); 

$("#forwhat.typeahead").typeahead({
		onSelect: function (item) {
			console.log(item);
		},
		ajax: {
			url: "http://192.168.1.1/yii2-consumption/json/outlayadmin/ajaxhandler",
			timeout: 500,
			displayField: "message",
			triggerLength: 1,
			method: "post",
			loadingClass: "loading-circle",
			preDispatch: function (query) {
				return {
					search: query,
					_csrf: yii.getCsrfToken(),
					order: 'forwhat',
					//param2: 'cat',
				}
			},
			preProcess: function (data) {
				console.log(data);				
				//showLoadingMask(false);
				if (data.success === false) {
					// Hide the list, there was some error
					return false;
				}
				// We good!
				var result = new Array();
				for (const [key, value] of Object.entries(data)) {
					result.push({ message: `${value}` });
				}
				console.log(result);
				return result;
			}
		}
	}); 


$("#towhom.typeahead").typeahead({
		onSelect: function (item) {
			console.log(item);
		},
		ajax: {
			url: "http://192.168.1.1/yii2-consumption/json/outlayadmin/ajaxhandler",
			timeout: 500,
			displayField: "message",
			triggerLength: 1,
			method: "post",
			loadingClass: "loading-circle",
			preDispatch: function (query) {
				return {
					search: query,
					_csrf: yii.getCsrfToken(),
					order: 'towhom',
					//param2: 'cat',
				}
			},
			preProcess: function (data) {
				console.log(data);				
				//showLoadingMask(false);
				if (data.success === false) {
					// Hide the list, there was some error
					return false;
				}
				// We good!
				var result = new Array();
				for (const [key, value] of Object.entries(data)) {
					result.push({ message: `${value}` });
				}
				console.log(result);
				return result;
			}
		}
	}); 



}




function changeOrder(){
	/*var objSelectD=document.getElementById('searchform-order');
	var orderValue=objSelectD[objSelectD.selectedIndex].value;
	$.ajax({
		url: 'http://192.168.1.1/yii2-consumption/json/outlay/ajaxhandler',
		type: 'POST',
		data: {
			order: orderValue,
			param2: '',
			_csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2
		},
		dataType: "json",
		success: function(response) {
			// Handle the successful response from the server
			var result=new Array();
			for (const [key, value] of Object.entries(response)) {
			  result.push({value:`${key}`,text:`${value}`});
			}
			createPoleSelect(result) 
		},
		error: function(xhr, status, error) {
			// Handle any errors during the AJAX request
			console.error(xhr.responseText);
		} 
	});*/
}



window.onload=inter_init;
