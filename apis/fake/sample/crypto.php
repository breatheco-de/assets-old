<?php 
	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");
    echo file_get_contents('http://api.coindesk.com/v1/bpi/currentprice.json');