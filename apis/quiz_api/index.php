<?php
	require_once('../APIGenerator.php');
    
	$api = new APIGenerator('data/','[]',false);

	$api->get('quizzes','Get all quizzes',function($request,$dataContent){
        return $dataContent;
	});

	$api->get('quiz', 'Get a particular quizz',function($request,$data) use ($api){
        
        $quizObj = $api->getJsonByName($request['url_elements'][1]);
        print_r($quizObj); die();
	});
	
	$api->run();
