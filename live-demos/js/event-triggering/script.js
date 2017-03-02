var runtimeTime = 0;


function myGenericListener(event)
{
	//milisecond timer
	setInterval(function(){ runtimeTime++; }, 1);

	printEventLog(event.type);
}

function printEventLog(eventType)
{
	var message = runtimeTime+"mil: ";
	switch(eventType)
	{
		default:
			message += eventType;
		break;
	}
	console.log(message);
}

$(document).ready(function(){
	printEventLog("ready");

	$.ajax({
		url: "https://4geeksacademy.github.io/exercise-assets/json/zips.json",
		success: function(){
			printEventLog("huge ajax success");
		},
		error: function(objError,errorMsg){
			console.log(errorMsg);
			printEventLog("huge ajax error: ");
		}
	});

	$.ajax({
		url: "https://4geeksacademy.github.io/exercise-assets/json/project1.json",
		success: function(){
			printEventLog("project1 ajax success");
		},
		error: function(){
			printEventLog("project1 ajax error");
		}
	});
});