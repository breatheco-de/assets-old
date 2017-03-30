"use strict";

var translatex = "0";
var translatey = "0";
var translatez = "0";
var scale = "0";
$(document).ready(function(){
	//listenToProperty('perspective','parent-div');
	listenToProperty('translatex','div1');
	listenToProperty('translatey','div1');
	listenToProperty('translatez','div1');
	listenToProperty('scale','div1');
	listenToProperty('transform-style','div1');
});

function listenToProperty(propertyName,containerId)
{
	var translatex = $("#translatex_range").val();
	$("#translatex_text").val(translatex);
	var translatey = $("#translatey_range").val();
	$("#translatey_text").val(translatey);
	var translatez = $("#translatez_range").val();
	$("#translatez_text").val(translatez);
	var scale = $("#scale_range").val();
	$("#scale_text").val(scale);

	console.log($("#"+containerId).css('transform'));
	$("#"+propertyName+"_range").on("change", function() {
	    $("#"+containerId).css('transform',printRule());
	});
}

function printRule()
{
	return "translateX("+translatex+"px) translateY("+translatey+"px) translateZ("+translatez+"px)";
}


var transformFunction = function(containerId,propertyName){
		return 0;
	}
