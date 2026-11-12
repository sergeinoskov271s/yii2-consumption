let selectElements = document.querySelectorAll('input[name="selection_total[]"]');
//alert('ok');
//var key = selectElements[0].value;

//let sum = tables[0].rows[3].cells[1].innerText
//intsum = sum.replaceAll(' ','');
//parseInt(intsum)

let key = 4;
let tables = document.getElementsByTagName('table');
let td = tables[0].querySelectorAll("tr[data-key='"+ key +"'] > td")[1];
let selectElement = document.querySelector('input[name="selection_total[]"]');
let sumvalue = td.innerText;
let intsum =  parseInt(sumvalue.replaceAll(' ',''));
console.log(intsum + 1);
console.log(tables.length);
//tables[0].querySelectorAll("tr[data-key='1']")[0].querySelector('input').checked
let sum;

function sum_total(){
	let tables = document.getElementsByTagName('table');
	
	for(let i=0;i<tables[0].rows.length;i++){
		
		
	}
	
}