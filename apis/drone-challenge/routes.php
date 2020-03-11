<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Carbon\Carbon;

return function($api){

	//deprecated
	$api->get('/scripts/all', function (Request $request, Response $response, array $args) use ($api) {

        if(!is_dir("./scrips/")){
            mkdir("./scripts/");     // Create scripts if not exists
            if(!file_exists("./scripts/example.py")) file_put_contents("./scripts/example.py", "print('Hello I am a script')");
        }

        $scripts = scandir("./scripts/");
        $scripts = array_values(array_filter($scripts, function($fileName){
            return $fileName != "." and $fileName != "..";
        }));
	    return $response->withJson($scripts);
    });


	//deprecated
	$api->delete('/scripts/all', function (Request $request, Response $response, array $args) use ($api) {

        $scripts = scandir("./scripts/");
        $scripts = array_values(array_filter($scripts, function($fileName){
            return $fileName != "." and $fileName != "..";
        }));
        $total = 0;
        forEach($scripts as $s){
            if(is_writable("./scripts/".$s)){
                if(unlink("./scripts/".$s)) $total++;
            } 
            else{
                 throw new Exception('File not writable',400);
            }
        }
	    return $response->withJson([ "details" => $total." scripts deleted successfully"]);
    });

	$api->delete('/scripts/{script_name}', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['script_name'])) throw new Exception('Missing param script_name',400);

        $scripts = scandir("./scripts/");
        $deleted = false;
        forEach($scripts as $s){
            if($s == $args['script_name'])
            unlink($s);
            $deleted = true;
        }
	    if($deleted) return $response->withJson([ "details" => $total." scripts deleted successfully"]);
	    else throw new Exception('Script "'.$args['script_name'].'" not found',404);
    });

	$api->get('/scripts/{script_name}', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['script_name'])) throw new Exception('Missing param script_name',400);

        if(!is_file("./scripts/".$args['script_name']))  throw new Exception('Script '.$args['script_name'].' not found',400);

        $content = file_get_contents("./scripts/".$args['script_name']);
	    if($content) return $response->withBody($content);
	    else throw new Exception('Content not accessible for "'.$args['script_name'].'" not found',400);
    });
    

	//deprecated
	$api->post('/scripts/{script_name}', function (Request $request, Response $response, array $args) use ($api) {

        if(empty($args['script_name'])) throw new Exception('Missing param script_name',400);
        $scriptBody = $request->getBody();
        if(empty($scriptBody)) throw new Exception('Missing script content ',400);

        file_put_contents("./scripts/".$args['script_name'].".py", $scriptBody);     // Save our content to the file.

	    return $response->withJson([ "message" => "New script added succesfully: ".$args['script_name'].".py"]);
	});


	return $api;
};