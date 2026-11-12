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
	var autocomplete = new Autocomplete({
		elem: $('#budgetform-like'),
		provider: dataProvider
	});
	$(autocomplete).on('changeAutocomplete', function (e) {
		//if (showingTooltip) {
		//	document.body.removeChild(showingTooltip);
		//	showingTooltip = null;
		//}
		showCompletion(e.value);
	});
	function showCompletion(value) {
		console.log(value);
		//var enterpriseSelected = $('#navigatorEnterprise option:selected').html();
		//var navigatorSearch = $('#navigatorSearch option:selected').val();
		//if (navigatorSearch == 'room') {
		//	getRoom(value, enterpriseSelected);
		//} else if (navigatorSearch == 'first_name') {
		//	getName(value, enterpriseSelected);
		//}

		//if (navigatorSearch == 'enterprise') {
		//	//			getDepartment([enterpriseSelected, value]);
		//	$('#result td.enterprise input').val(value);
		//}
		//if (navigatorSearch == 'department') {
		//	//			getDepartment([enterpriseSelected, value]);			
		//	$('#result td.department input').val(value);
		//}
		//if (navigatorSearch == 'office') {
		//	//			getDepartment([enterpriseSelected, value]);
		//	$('#result td.office input').val(value);
		//}
		//if (navigatorSearch == 'group') {
		//	//			getDepartment([enterpriseSelected, value]);
		//	$('#result td.group input').val(value);
		//}
		//if (navigatorSearch == 'position') {
		//	//			getDepartment([enterpriseSelected, value]);
		//	$('#result td.position textarea').val(value);
		//}
		//if (navigatorSearch == 'name_name') {
		//	//			getDepartment([enterpriseSelected, value]);
		//	$('#result td.name_name input').val(value);
		//}
		//if (navigatorSearch == 'last_name') {
		//	//			getDepartment([enterpriseSelected, value]);
		//	$('#result td.last_name input').val(value);
		//}


		//	input.focus();
	}


	//selectEnterprise = new Choice({
	//	options: [{ text: 'Select', value: 'select' }],
	//	elem: $('#enterprise-select')
	//});


	//if (document.all && document.querySelector) {
	//	activeEnable = true;
	//	document.onmouseout = function (e) {
	//		if (showingTooltip) {
	//			document.body.removeChild(showingTooltip);
	//			showingTooltip = false;
	//		}
	//	};
	//	document.onmouseover = function (e) {
	//		var e = e || event;
	//		var target = e.target || e.srcElement;
	//		while (target != this) {
	//			var tooltip = target.getAttribute('data-tooltip');
	//			if (tooltip) break;
	//			target = target.parentNode;
	//		}
	//		if (!tooltip) return;
	//		showingTooltip = showTooltip(tooltip, target);
	//	};
	//} else if (!document.all) {
	//	activeEnable = true;
	//	document.onmouseout = function (e) {
	//		var e = e || event;
	//		var target = e.target || e.srcElement;
	//		while (target != this) {
	//			var tooltip = target.getAttribute('data-tooltip');
	//			if (tooltip) break;
	//			target = target.parentNode;
	//		}
	//		if (!tooltip) return;
	//		if (showingTooltip) {
	//			document.body.removeChild(showingTooltip);
	//			showingTooltip = false;
	//		}

	//	};
	//	document.onmouseover = function (e) {
	//		var e = e || event;
	//		var target = e.target || e.srcElement;
	//		while (target != this) {
	//			var tooltip = target.getAttribute('data-tooltip');
	//			if (tooltip) break;
	//			target = target.parentNode;
	//		}
	//		if (!tooltip) return;
	//		if (!showingTooltip) {
	//			showingTooltip = showTooltip(tooltip, target);
	//		}
	//	};
	//}
	$('.field-budgetform-like input').focus();
	
	
	
	


}

function Autocomplete(options) {
	var self = this;
	var elem = options.elem;
	var provider = options.provider;
	var input = $('input', elem);
	var list;
	input.on({
		focus: onInputFocus,
		blur: onInputBlur,
		keydown: onInputKeyDown
	});
	var inputCheckTimer;
	elem.on('click', '.nameLi', onLiClick);
	//    elem.on('click','.nextEnterpriseLi',onNextEnterpriseClick);
	//    elem.on('click','.depLi', onDepLiClick);
	var endSearch = false;

	function onNextEnterpriseClick(e) {
		nextEnterprise();
		changeEnterprise();
	}

	function onLiClick(e) {
		list.liSelect($(e.target));
		self.setValue(list.get() || input.val());
		list.clear();
		input.blur();

	}
	function onDepLiClick(e) {
		list.liSelect($(e.target));
		self.setValue(list.get() || input.val());
		list.clear();
		input.blur();

	}

	$('body').on('updateList', function (e) {
		provider.updateStrings(e.value);
		if (e.value) {
			updateList();
		} else {
			list.clear();
		}
	});
	$('body').on('updateListDep', function (e) {
		provider.updateStrings(e.value);
		updateListDep();
	});

	function onInputKeyDown(e) {
		var KEY_ARROW_UP = 38;
		var KEY_ARROW_RIGHT = 39;
		var KEY_ARROW_DOWN = 40;
		var KEY_ENTER = 13;
		var KEY_ESC = 27;
		var KEY_PAGEUP = 33;
		var KEY_PAGEDOWN = 34;

		switch (e.keyCode) {
			case KEY_ARROW_UP:
				list.up();
				return false;
				break;
			case KEY_ARROW_RIGHT:
				if (list.get()) {
					self.setValue(list.get(), true);
				}
				break;
			case KEY_ENTER:
				self.setValue(list.get() || input.val());
				list.clear();
				if (list.get()) {
					self.setValue(list.get(), true);
					//		    showCompletion(list.get());	
				}
				input.blur();
				break;
			case KEY_ESC:
				input.val('');
				inputValue = '';
				list.clear();
				break;
			case KEY_ARROW_DOWN:
				list.down();
				return false;
				break;
			case KEY_PAGEUP:
				prevEnterprise();
				changeEnterprise();
				return false;
				break;
			case KEY_PAGEDOWN:
				nextEnterprise();
				changeEnterprise();
				return false;
				break;
		}

	}

	function onInputFocus() {
		var inputValue = input.val();
		function checkInput() {
			if (inputValue != input.val()) {
				if (!list) {
					initList();
				}
				if (input.val() != '') {
					var enterpriseSelected = $('#navigatorEnterprise option:selected').html();
					var navigatorSearch = $('#navigatorSearch option:selected').val();
					switch (navigatorSearch) {
						//						case 'first_name': 
						//							getFirstNameLike(input.val(), enterpriseSelected);
						//						break;
						case 'name_name':
							getNameLike(input.val());
							break;
						case 'last_name':
							getLastNameLike(input.val());
							break;
						case 'room':
							getRoomLike(input.val());
							break;
						case 'enterprise':
							getEnterpriseLike(input.val());
							break;

						case 'department':
							getDepLike(input.val());
							break;
						case 'office':
							getOfficeLike(input.val());
							break;
						case 'group':
							getGroupLike(input.val());
							break;
						case 'position':
							getPositionLike(input.val());
							break;
					}
					list.clear();
					list.update(input.val());
					//			if(input.val().length > inputValue.length &&  list.getCount()==1){
					//			    self.setValue(list.get(), true);
					//			}
					inputValue = input.val();
					provider.updateStrings('');
				}
			}
		}
		inputCheckTimer = setInterval(checkInput, 10);
	}

	function initList() {
		list = new AutocompleteList(provider);
		list.render().appendTo(elem);
		$(list).on('update1', onListUpdate);
	}
	function updateList() {
		if (list) {
			list.update(input.val());
		}
	}
	function updateListDep() {
		if (list) {
			list.updateDep(input.val());
		}
	}

	function onListUpdate(e) {
		if (e.values.length) {
			elem.addClass('open');
		} else {
			elem.removeClass('open');
		}
	}
	var inputValue = '';

	function onInputBlur() {
		clearInterval(inputCheckTimer);
		//	if(list){
		//	    list.clear();
		//	}
	}
	this.setValue = function (value, quiet) {
		//	input.val(value);
		//	inputValue=value;
		inputValue = '';
		input.val('');
		if (!quiet) {
			$(self).triggerHandler({
				type: 'changeAutocomplete',
				value: value
			});
		}
	}
}

function AutocompleteList(provider) {
	var elem;
	var filteredResults;
	var currentIndex = 0;
	this.render = function () {
		elem = $('<ol/>');
		return elem;
	};
	var self = this;
	var count;

	this.update = function (value) {
		filteredResults = provider.filterByStart(value);
		count = filteredResults.length;
		if (filteredResults.length) {
			elem.html('<li class="nameLi">' + filteredResults.join('</li><li class="nameLi" >') + '</li>');
			//			var text="Продолжить поиск в следующем предприятии";
			//			elem.append('<li class="nextEnterpriseLi" >'+text+'</li>');
		} else {
			elem.empty();
		}
		currentIndex = 0;
		renderCurrent();
		$(this).triggerHandler({
			type: 'update1',
			values: filteredResults
		});
	};
	this.updateDep = function (value) {
		filteredResults = provider.getStrings(value);
		if (filteredResults.length) {
			elem.html('<li class="depLi">' + filteredResults.join('</li><li class="depLi">') + '</li>');
		} else {
			elem.empty();
		}
		currentIndex = 0;
		renderCurrent();
		$(this).triggerHandler({
			type: 'update1',
			values: filteredResults
		});
	};

	function renderCurrent() {
		elem.children().eq(currentIndex).addClass('selected');
	}
	function clearCurrent() {
		elem.children().eq(currentIndex).removeClass('selected');
	}
	this.get = function () {
		return filteredResults[currentIndex];
	};
	this.down = function () {
		if (currentIndex == filteredResults.length - 1) return;
		clearCurrent();
		currentIndex++;
		renderCurrent();
	};
	this.up = function () {
		if (currentIndex == 0) return;
		clearCurrent();
		currentIndex--;
		renderCurrent();
	};
	this.clear = function () {
		//	this.update('');
		elem.empty();
		$('.field-budgrtform-like').removeClass('open');
	};
	this.liSelect = function (li) {
		clearCurrent();
		li.addClass('selected');
		currentIndex = li.index();
	}
	this.getCount = (function () {
		return function () {
			return count;
		};
	})();
}

var dataProvider = new FilteringListProvider([
]);

function FilteringListProvider(strings) {
	var self = this;
	this.strings = strings;
	this.get = function (index) {
		return self.strings[index];
	};
	this.filterByStart = function (stringStart) {
		if (stringStart.length < 1) return [];
		if (self.strings.length < 1) return [];

		var stringStartLC = stringStart.toLowerCase();
		return self.strings.filter(function (str) {
			var strLC = str.toLowerCase();

			return strLC.slice(0, stringStartLC.length) == stringStartLC &&
				strLC != stringStartLC;
		});
	}
	this.getStrings = function () {
		return self.strings;
	}
	this.updateStrings = function (value) {
		self.strings = value;
	}
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
