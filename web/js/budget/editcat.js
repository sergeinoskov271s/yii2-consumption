"use strict";

//personal
var ROOT;
var path = [];
path = location.toString().split('\/');
//var pathAjax = path[0]+'//'+path[2]+'/'+path[3]+'/ajax/'
var pathAjax = path[3];

var template = Handlebars.compile("Handlebars <b>{{doesWhat}}</b>");
// execute the compiled template and print the output to the console
console.log(template({ doesWhat: "rocks!" }));

var source = $("#name-table").html();
var template = Handlebars.compile(source);
var budgets = [{ cat: 'a' }, {cat:'b'}]
var context = { budgets: budgets };
var html = template(context);
//var html = template();
$('#result').html(html);	




function Budget() {
	this.towhom;
	this.id;
	this.forwhat;
	this.dt1;
	
}