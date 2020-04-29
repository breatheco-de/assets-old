<?php 
	header("Access-Control-Allow-Origin: *");
    // header("HTTP/1.0 404 Not Found");
    $all = [
        [ "code" => 200, "msg" => "Ok" ],
        [ "code" => 500, "msg" => "Weird Server problem" ],
        [ "code" => 201, "msg" => "Created OK" ],
        [ "code" => 404, "msg" => "Not found, what did you asked for?" ],
        [ "code" => 403, "msg" => "You dont have access" ],
        [ "code" => 401, "msg" => "I don't know who you are, and this is private" ],
        [ "code" => 503, "msg" => "I'm not available, maybe later." ],
    ];
    $status = $all[rand(0,count($all) - 1)];
    http_response_code($status["code"]);
    echo json_encode($status["msg"]);
	die();
?>