var runtimeTime = 0;


function myGenericListener(event)
{
	//milisecond timer
	setInterval(function(){ runtimeTime++; }, 1);

	printEventLog(event.type);
}

function printEventLog(eventType)
{
	switch(eventType)
	{
		default:
			console.log(runtimeTime+": "+eventType+" triggered");
		break;
	}
}

$(document).ready(function(){
	printEventLog("ready");

	$.ajax({
		url: "https://4geeksacademy.github.io/exercise-assets/json/weird_portfolio.json",
		success: function(){
			printEventLog("ajax success");
		},
		error: function(){
			printEventLog("ajax error");
		}
	});
});