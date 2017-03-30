"use strict";

$(document).ready(function(){
	listenToProperty('perspective','div1');
	listenToProperty('transformx','div2',transformFunction);
	listenToProperty('transformy','div2',transformFunction);
	listenToProperty('transformz','div2',transformFunction);
	listenToProperty('transform-style','div1');
});

function listenToProperty(propertyName,containerId,getFunction)
{
	var ruleValue = '';
	if(getFunction && typeof(getFunction) == 'function') ruleValue= getFunction(containerId,propertyName);
	else ruleValue = $("#"+containerId).css(propertyName);
	$("#"+propertyName+"_range").val(ruleValue);
	$("#"+propertyName+"_text").val(ruleValue);
	console.log($("#"+containerId).css('transform'));
	$("#"+propertyName+"_range").on("change", function() {
	     var value  = $(this).val();
	     $("#"+propertyName+"_text").val(value);
	     if(propertyName=="transformx" || propertyName=="transformy" || propertyName=="transformz")
	     {
	     	value  = $('#transformx_range').val();
	     	var value2  = $('#transformy_range').val();
	     	var value3  = $('#transformz_range').val();
	     	console.log(printRule('transform',value,value2,value3));
	     	$("#"+containerId).css('transform',printRule('transform',value,value2,value3));
	     }
	     else{
	     	$("#"+containerId).css(propertyName,printRule(propertyName,value));
	     }
	});
}

function printRule(propertyName,value,value2,value3)
{
	switch(propertyName){
		case "perspective":
			var ruleVal = value+"px";
			return ruleVal;
		break;
		case "transform":
			var ruleVal = "rotateX("+value+"deg)";
			if(value2) ruleVal += " rotateY("+value2+"deg)";
			if(value3) ruleVal += " rotateZ("+value3+"deg)";
			return ruleVal;
		break;
		default:
			var ruleVal = value;
			return ruleVal;
		break;
	}
}

var transformFunction = function(containerId,propertyName){
		if(propertyName=='transformx'){
			var b = $("#"+containerId).css('transform').split(',')[5]
			return Math.round(Math.asin(b) * (180/Math.PI));
		}
		if(propertyName=='transformy'){
			var b = $("#"+containerId).css('transform').split(',')[8]
			return Math.round(Math.asin(b) * (180/Math.PI));
		}
		if(propertyName=='transformz'){
			var b = $("#"+containerId).css('transform').split(',')[4]
			return Math.round(Math.asin(b) * (180/Math.PI));
		}
	}
