<?php
    require('../../vendor/autoload.php');
    require_once('../APIGenerator.php');
    
    use Google\Cloud\Datastore\DatastoreClient;
    $datastore = new DatastoreClient([ 
        'projectId' => 'breathecode-197918',
        'keyFilePath' => '../../breathecode-efde1976e6d3.json'
    ]);
	$api = new APIGenerator();
	$api->get('answers', 'Get all survays', function($request) use ($datastore){
        $query = $datastore->query()->kind('NPS_Answer')->order('created_at');
        $items = $datastore->runQuery($query);
        
        $results = [];
        foreach($items as $ans) {
            $results[] = [
                "user_id" => $ans->user_id,
                "answer" => $ans->answer,
                "cohort" => $ans->cohort,
                "created_at" => $ans->created_at,
                "comments" => $ans->comments,
            ];
        }
        return $results;
	});

	$api->get('student_answers', 'Get all survays', function($request) use ($datastore){
	    
	    if(!isset($request['url_elements'][1])) throw new Exception('Mising student_id');
	    $userId = $request['url_elements'][1];
        
        $query = $datastore->query()->kind('NPS_Answer')->filter('user_id', '=', intval($userId));
        $items = $datastore->runQuery($query);
        
        $results = [];
        $latestAnswer = null;
        foreach($items as $ans) {
            $newAnswer = [
                "user_id" => $ans->user_id,
                "answer" => $ans->answer,
                "cohort" => $ans->cohort,
                "created_at" => $ans->created_at,
                "comments" => $ans->comments,
            ];
            $results[] = $newAnswer;
            //get the latest answer
            if(!$latestAnswer or ($latestAnswer['created_at'] < $newAnswer['created_at'])) $latestAnswer = $newAnswer;
        }
        return $results;
	});

	$api->put('answer', 'Save new survay', function($request) use ($datastore){
	    
        if(!isset($request['parameters']['user_id'])) throw new Exception('Mising request body user_id');
        if(!isset($request['parameters']['answer'])) throw new Exception('Mising request body answer');
        if(!isset($request['parameters']['cohort'])) throw new Exception('Mising request body cohort');
        if(!isset($request['parameters']['comments'])) throw new Exception('Mising request comments');
        
        $userId = $request['parameters']['user_id'];
        $query = $datastore->query()->kind('NPS_Answer')->filter('user_id', '=', intval($userId));
        $items = $datastore->runQuery($query);
        
        $results = [];
        $latestAnswer = null;
        foreach($items as $ans) {
            $newAnswer = [
                "user_id" => $ans->user_id,
                "answer" => $ans->answer,
                "cohort" => $ans->cohort,
                "created_at" => $ans->created_at,
                "comments" => $ans->comments,
            ];
            $results[] = $newAnswer;
            //get the latest answer
            if(!$latestAnswer or ($latestAnswer['created_at'] < $newAnswer['created_at'])) $latestAnswer = $newAnswer;
        }
        
        if($latestAnswer)
        {
            $today = new DateTime();
            $days = $today->diff($latestAnswer['created_at'])->format("%d");
            if(intval($days) < 25) throw new Exception('You need to wait at least 25 days to vote again');
        }
        
        $npsAnswer = $datastore->entity('NPS_Answer', [
            'created_at' => new DateTime(),
            'user_id' => $request['parameters']['user_id'],
            'answer' => $request['parameters']['answer'],
            'cohort' => $request['parameters']['cohort'],
            'comments' => $request['parameters']['comments']
        ]);
        
        try
        {
            $datastore->insert($npsAnswer);
        }
        catch(Exception $e){
            $exception = json_decode($e->getMessage());
            throw new Exception($exception->error->message);
        }
        
        return [
            "user_id" => $npsAnswer->user_id,
            "answer" => $npsAnswer->answer,
            "cohort" => $npsAnswer->cohort,
            "created_at" => $npsAnswer->created_at,
            "comments" => $npsAnswer->comments
        ];
	});
	
	$api->post('clean', 'Clean responses',function($request) use ($api){
	    
	    $api->saveData([]);
        return [];
	});
	
	$api->run();