<?php 
	header("Content-type: application/json"); 
	if(strpos($_SERVER['HTTP_REFERER'], "breatheco") || strpos($_SERVER['HTTP_REFERER'], "c9") || strpos($_SERVER['HTTP_REFERER'], "replit")){
		header("Access-Control-Allow-Origin: *");
	}		
?>
{
	"content" : "hello world"
}