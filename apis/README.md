# BreatheCode API's

This directory contains several apis needed to run the breathecode platform:

1. Quizes: Used by the breathecode platform quizes.
2. Kill The Bug Game: To save the participants.
3. Sounds: For games and tutorials.
4. TicTacToe: For the tictactoe project.
3. Replit video tutorials: Use by the breathecode platform for the video tutorials.

## Creating new API's

If you are building a very simple API there is no need for anything but the APIGenerator.php file.

### 1. This will create a GET request:
```
	$api = new APIGenerator('data/','[]',false);

    /*
    * Parameters
    * - The URL pattern
    * - Description of what the services does.
    * - Callback function that will handle the request (it receives the request info and the $content from the stored data)
    */
	$api->get('quizzes','Get all quizzes',function($request,$dataContent) use ($api){
	    //return whatever you want to respond back to the requester

        //If your data is sored in only one file	    
        return $dataContent;
        
        //or if you have several files storing your data
        $jsonContent = $api->getJsonByName($request['url_elements'][1]);
        return $dataContent;
	});
```
Note: url_elements contains all the URL parts
```
 https://exampledomain./com/<part1>/<part2>/<part3>/<partN>/
```

### 2. This will create a POST request:
```
	$api = new APIGenerator('data/','[]',false);

    /*
    * Parameters
    * - The URL pattern
    * - Description of what the services does.
    * - Callback function that will handle the request (it receives the request info and the $content from the stored data)
    */
	$api->post('game', 'Create new game', function($request, $dataContent) use ($api){
	    
        if(!isset($request['parameters']['player1']) || !isset($request['parameters']['player2']) || !isset($request['parameters']['winner'])) throw new Exception('Mising request body with parameters player1, player2 and winner');
        
        if(is_array($dataContent)) array_push($dataContent,$request['parameters']);
        else $dataContent = array_merge([],[$request['parameters']]);
        
        $api->saveData($dataContent);
        return $dataContent;
	});
```