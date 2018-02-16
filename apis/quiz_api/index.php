<?php
	require_once('../APIGenerator.php');
    
	$api = new APIGenerator('data/','[]',false);

	$api->get('quizzes','Get all quizzes',function($request,$dataContent){
        return $dataContent;
	});

	$api->get('quiz', 'Get a particular quizz',function($request,$data) use ($api){
        
        return $api->getJsonByName($request['url_elements'][1]);
	});
	
	$api->run();
