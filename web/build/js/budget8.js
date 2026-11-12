"use strict";
var path = [];
path = location.toString().split('\/');
//var pathAjax = path[0]+'//'+path[2]+'/'+path[3]+'/ajax/'
var pathAjax = path[3];

function cloneObject(source) {
	for (var i in source) {
		if (typeof source[i] == 'source') {
			this[i] = new cloneObject[i];
		} else {
			this[i] = source[i];
		}
	}
}

function inter_init() {

	var objSelectD=document.getElementById('budgetform-order');
	//var orderValue=objSelectD[objSelectD.selectedIndex].value;
	//var objDate=document.getElementById('budgetform-date');
	//var dateValue=objDate.value;
	//alert(dateValue);

	//var typeaheadSource = ['John', 'Alex', 'Terry'];

	//$('input.typeahead').typeahead({
	//	source: typeaheadSource
	//});
	$("input.typeahead").typeahead({
		onSelect: function (item) {
			console.log(item);
		},
		ajax: {
			url: "http://192.168.1.1/yii2-consumption/json/outlay/ajaxhandler",
			timeout: 500,
			displayField: "message",
			triggerLength: 1,
			method: "post",
			loadingClass: "loading-circle",
			preDispatch: function (query) {
				//showLoadingMask(true);
				return {
					search: query,
					_csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2
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
				return data.mylist;
			}
		}
	});


}



	
















function onChangeInput(val){
	var objSelectD=document.getElementById('searchform-order');
	var orderValue=objSelectD[objSelectD.selectedIndex].value;
	var objDate=document.getElementById('searchform-date');
	var dateValue=objDate.value;
	//alert(dateValue);
    $.ajax({
		url: 'http://192.168.1.1/yii2-consumption/json/outlay/ajaxhandler',
		//url:"/"+pathAjax+"/json/outlay/ajaxhandler",
		type: 'POST',
		data: {
			order: orderValue,
			param2: dateValue,
			_csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2
		},
		dataType: "json",
		success: function(response) {
			// Handle the successful response from the server
			//console.log(response);
			//alert(response);
			var result=new Array();
			//$.each(response, function(i,val){
			//	result[{value:i}] = {text:val};			
			//});
			for (const [key, value] of Object.entries(response)) {
			  //console.log(`${key}: ${value}`);
			  	//		  console.log(key: ${value}`);
			  
			  result.push({value:`${key}`,text:`${value}`});
			}
			//alert(result);
			//console.log(result);
			createPoleSelect(result) 
		},
		error: function(xhr, status, error) {
			// Handle any errors during the AJAX request
			console.error(xhr.responseText);
		} 
	});
	
	
	
}














function createPoleSelect(ar){
	var objSelectD=document.getElementById('searchform-pole');
//    removeAllOptions(objSelectD);
	   var i, L = objSelectD.options.length - 1;
		for(i = L; i >= 0; i--) {
		  objSelectD.remove(i);
	   }
	//var ar =[{value:1,text:'value 1'},{value:2, text:'value2'}];
	//objSelectD.options.add({text:'all',value:'all'});
	const newOption = document.createElement('option');
	newOption.textContent = '';
    newOption.value = '';
	newOption.selected = true;
	objSelectD.appendChild(newOption);
    for(var i=0;i<ar.length;i++){
		var optn=document.createElement('option');
		optn.text=ar[i].text;
		optn.value=ar[i].value;
		objSelectD.options.add(optn);        
    }
	
	
	
}



function changeOrder(){
	
	var objSelectD=document.getElementById('searchform-order');
	var orderValue=objSelectD[objSelectD.selectedIndex].value;
	getPole(orderValue);
	//alert("Selected value: " + orderValue);
	$.ajax({
		url: 'http://192.168.1.1/yii2-consumption/json/outlay/ajaxhandler',
//		url:"/"+pathAjax+"/json/outlay/ajaxhandler",
		type: 'POST',
		data: {
			order: orderValue,
			param2: 'value2',
			_csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2
		},
		dataType: "json",
		success: function(response) {
			// Handle the successful response from the server
			//console.log(response);
			//alert(response);
			var result=new Array();
			//$.each(response, function(i,val){
			//	result[{value:i}] = {text:val};			
			//});
			for (const [key, value] of Object.entries(response)) {
			  //console.log(`${key}: ${value}`);
			  	//		  console.log(key: ${value}`);
			  
			  result.push({value:`${key}`,text:`${value}`});
			}
			//alert(result);
			//console.log(result);
			createPoleSelect(result) 
		},
		error: function(xhr, status, error) {
			// Handle any errors during the AJAX request
			console.error(xhr.responseText);
		} 
	});
	
	
	
	
	
	
}




function getPole(orderValue){
	$.ajax({
		url: 'http://192.168.1.1/yii2-consumption/json/outlay/ajaxhandler',
		type: 'POST',
	//	dataType: "json", 
		data: {
			param1: orderValue,
			param2: 'value2',
			_csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2
		},
		success: function(response) {
			// Handle the successful response from the server
			console.log(response);
			//alert('get pole'.response);
		},
		error: function(xhr, status, error) {
			// Handle any errors during the AJAX request
			console.error(xhr.responseText);
		} 
	});
	
	
	
}













window.onload=inter_init;
