<?php 
	header("Content-type: application/json"); 
    header("Access-Control-Allow-Origin: *");
    echo file_get_contents("./projects.json");