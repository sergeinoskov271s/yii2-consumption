"use strict";

//personal
var ROOT;
var path = [];
path = location.toString().split('\/');
//var pathAjax = path[0]+'//'+path[2]+'/'+path[3]+'/ajax/'
var pathAjax = path[3];

var template = Handlebars.compile("Handlebars <b>{{doesWhat}}</b>");
// execute the compiled template and print the output to the console
console.log(template({ doesWhat: "rocks77!" }));

var source = $("#name-table").html();
var template = Handlebars.compile(source);
var budgets = [{ cat: 'a' }, {cat:'b'}]
var context = { budgets: budgets };
var html = template(context);
//var html = template();
$('#result').html(html);	

function inter_init() {

	//alert(dateValue);
	
	$.ajax({
		url: 'http://192.168.1.1/yii2-consumption/json/template/getcats',
		type: 'POST',
		data: {
			/*order: 'cat',
			param2: "пит",
			_csrf: yii.getCsrfToken() // Important for CSRF validation in Yii2*/
		},
		dataType: "json",
		success: function (response) {
			// Handle the successful response from the server
			//console.log(response);
			//alert(response);
			var result = new Array();
			//$.each(response, function(i,val){
			//	result[{value:i}] = {text:val};			
			//});
			for (const [key, value] of Object.entries(response)) {
				//console.log(`${key}: ${value}`);
				//		  console.log(key: ${value}`);

				result.push({ id: `${value}`, title: `${value}` });
			}
			//alert(result);
			console.log(result);
			//id="budget-budgets-0-cat"
			//createPoleSelect(result)
			let ar = [{'title':'test','id':'test'}];
			createCatOption(result);
		},
		error: function (xhr, status, error) {
			// Handle any errors during the AJAX request
			console.error(xhr.responseText);
		}
	});
	
	





}


function removeAllOptions(selectbox){
    for(var i=selectbox.options.length-1;i>=0;i--){
        selectbox.remove(i);
    }
} 

function readCookie(name){
    let nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i=0;i<ca.length;i++) {
        let c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function createCookie(name,value){
    const date = new Date();
    date.setTime(date.getTime()+(20*24*60*60*1000));
    let expires = "; expires=" + date.toGMTString();
    document.cookie = name + "=" + value + expires + "; path=/";
} 

window.onload=inter_init;




function createCatOption(ar){
    let objSelectD = document.getElementById('budget-budgets-0-cat');
//    removeAllOptions(objSelectD);
    //$('#budget-budgets-0-cat').remove();
    removeAllOptions(objSelectD); 
    
    if(ar.length === 0) return;
    for(let i=0;i<ar.length;i++){
        let opt = document.createElement('option');
        opt.text = ar[i]['title'];
        opt.value = ar[i]['id'];
        objSelectD.options.add(opt);
    }
    /* const depSel = readCookie('departmentValue');
     if(depSel){
         for(let i=0;i<objSelectD.options.length;i++){
             if(objSelectD.options[i].value === depSel){
                 objSelectD.options[i].selected = true;
             }
         }
     }*/
} 











function Budget() {
	this.towhom;
	this.id;
	this.forwhat;
	this.dt1;
	
}