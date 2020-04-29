<?php 
	header("Content-type: application/json"); 
    header("Access-Control-Allow-Origin: *");
    $projects = json_decode(file_get_contents("./projects.json"));
    $index = rand(0,count($projects) - 1);
    echo json_encode($projects[$index]);
?>