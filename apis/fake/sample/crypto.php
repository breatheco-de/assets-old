<?php 
	header("Content-type: application/json"); 
	header("Access-Control-Allow-Origin: *");
    echo file_get_contents('https://api.coinmarketcap.com/v1/ticker/bitcoin/');