<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
    
class APIGenerator{
    
    //Onyl used if the data is stored in just one json file
    var $fileName = null;
    
    //Only use when the data is stored in different files
    var $dataFiles = null;
    
    var $logger = null;
    
    //The real content already parse, if several datafiles where provided it will be an array of objects
    var $dataContent = null;
    
    //All the API available methods
    var $methods = [];
    
    var $debug = false;
    
    var $request;
    var $host = null;
        
    function __construct($dataPath = null,$defaultContent='{}',$debug = false){
        
	    $this->debug = $debug;
	    $this->host = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	    
	    $this->request = [];
	    $this->request['type'] = $_SERVER['REQUEST_METHOD'];
	    
	    $url = '';
	    if(isset($_GET['url'])) $url = $_GET['url'];
	    
	    $this->request['url'] = $url;
	    $this->request['url_elements'] = explode('/', $url);
	    
	    //if the request has one empty element means that no resource was specified on the request URL
	    if(count($this->request['url_elements'])==1 && empty($this->request['url_elements'][0]))
	        array_shift($this->request['url_elements']);
	    
	    $this->parseIncomingParams();
	    if($dataPath) $this->getDataStructure($dataPath, $defaultContent);
    }
    
    function logRequests($fileURL){

        $this->logger = new Logger('requests');
        $this->logger->pushHandler(new StreamHandler($fileURL, Logger::INFO));
    }
    
    function getDataStructure($path, $defaultContent){
        
        $pathParts = pathinfo($path);
        if(!empty($pathParts['extension'])){
            $this->fileName = $path;
            $this->dataContent = $this->parseFile($this->fileName,$defaultContent);
        } 
        else
        {
            if($this->dataContent == null) $this->dataContent = [];
            if($this->dataFiles == null) $this->dataFiles = [];
            
        	$directories = scandir($path);
        	$urlparts = explode("/", $path);
        	$level = 0;
        	foreach ($directories as $value){
        		if($this->debug) echo "Recorriendo: $newPath \n";
    		    $newPath = $path.$value;
        		if($value!='.' and $value!='..' and is_dir($path)) 
        		{
        			$laspath = basename($path);
        			if(is_dir($newPath)) 
        			{
        			    if($this->debug) echo "Entro en: $newPath \n";
        			    $this->getDataStructure($newPath.'/', $defaultContent);
        			}
        			else{
        			    $newPathParts = pathinfo($newPath);
        			    if($newPathParts['extension'] == 'json')
        			    {
            			    if($this->debug) echo "Guardando $newPath ...\n";
            			    array_push($this->dataFiles, $newPath);
            			    $this->dataContent[$newPath] = $this->parseFile($newPath, $defaultContent);
        			    }
        			} 
        		}
        	}
        	return $this->dataFiles;
        }
    }
    
    public function parseIncomingParams() {
        $parameters = array();

        // first of all, pull the GET vars
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
        }

        // now how about PUT/POST bodies? These override what we got from GET
        $body = file_get_contents("php://input");
        $content_type = false;
        if(isset($_SERVER['CONTENT_TYPE'])) {
            $content_type = $_SERVER['CONTENT_TYPE'];
        }
        switch($content_type) {
            case "application/json":
                $body_params = json_decode($body);
                if($body_params) {
                    foreach($body_params as $param_name => $param_value) {
                        $parameters[$param_name] = $param_value;
                    }
                }
                $this->format = "json";
                break;
            case "application/x-www-form-urlencoded":
                parse_str($body, $postvars);
                foreach($postvars as $field => $value) {
                    $parameters[$field] = $value;

                }
                $this->format = "html";
                break;
            default:
                // we could parse other supported formats here
                break;
        }
        $this->request['parameters'] = $parameters;
        
        $this->request['parameters'] = array_merge($this->request['parameters'], $_POST);
    }
    function parseFile($fileName, $defaultContent){
        
        if(!file_exists($fileName)){
            $fh = fopen($fileName, 'a'); 
            if(is_string($defaultContent)) fwrite($fh,$defaultContent); 
            else fwrite($fh, json_encode($defaultContent)); 
            fclose($fh); 
            chmod($fileName, 0777); 
            
            $dataContent = (array) json_decode($defaultContent);
            if($dataContent === null or $dataContent ===false) $this->throwError('Unable to get file content: '+json_last_error());
            
            return $dataContent;
        }
        else
        {
            $jsonContent = file_get_contents($fileName);
            $dataContent = (array) json_decode($jsonContent);
            if($dataContent === null or $dataContent ===false) $this->throwError('Unable to get file content: '+json_last_error());
            
            return $dataContent;
        }
    }
    
    function addMethod($methodType='GET', $methodName, $description, $callback){
        if(!$methodName or $methodName=='') throw new Exception('Please specify a method name');
        if(!$callback or !is_callable($callback)) throw new Exception('The callback need to be a callable function');
        
        if(!isset($this->methods[$methodType])) $this->methods[$methodType] = [];
        if(!isset($this->methods[$methodType][$methodName])) $this->methods[$methodType][$methodName] = [];

        $this->methods[$methodType][$methodName]['callback'] = $callback;
        $this->methods[$methodType][$methodName]['description'] = $description;
    }
    function get($methodName, $description, $callback){ $this->addMethod('GET', $methodName, $description, $callback); }
    function post($methodName, $description, $callback){ $this->addMethod('POST',$methodName, $description, $callback); }
    function put($methodName, $description, $callback){ $this->addMethod('PUT',$methodName, $description, $callback); }
    function delete($methodName, $description, $callback){ $this->addMethod('DELETE',$methodName, $description, $callback); }
    
    function run(){
        if(count($this->request['url_elements'])==0){
            header("Content-type: text/html"); 
            if(file_exists('README.md')) $this->generateDocs('readme');
            else $this->generateDocs(); 
            die();
        }
        else
        {
            header("Content-type: application/json"); 
	        header("Access-Control-Allow-Origin: *");
	        header('Access-Control-Allow-Methods: GET, POST, PUT');
	        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        }

        $methodType = $this->request['type'];
        $methodName = $this->request['url_elements'][0];

        if($this->logger) $this->logger->info("$methodType: ".$this->request['url']." PARAMS: ".json_encode($this->request['parameters']));

        if($methodType!='OPTIONS'){
            if(!isset($this->methods[$methodType][$methodName])) $this->throwError('Method '.$methodType.': /'.$methodName.' not recognized. Maybe with PUT, POST or DELETE?');;

            try{
                
                $result = $this->methods[$methodType][$methodName]['callback']($this->request, $this->dataContent);
                if($result === null) throw new Exception('Please return something on your API method callback for: '.$methodName);

                $this->throwSuccess($result);
            }
            catch(Exception $e){
                
                $this->throwError($e->getMessage());
            } 
        }
        else $this->throwSuccess('ok');
    }
    
    function createDirectory($path,$qzes){
        
    	$directories = scandir($path);
    	$urlparts = explode("/", $path);
    	$level = 0;
    	foreach ($directories as $value) {
    		$newPath = $path.$value.'/';
    		//echo "Recorriendo: $newPath \n";
    		if($value!='.' and $value!='..' and is_dir($path)) 
    		{
    			$laspath = basename($path);
    			//echo "entro...$laspath... \n";
    			//if(isset($urlparts[5])) echo $urlparts[5]."\n";
    			if(is_dir($newPath)) $qzes = createDirectory($newPath,$qzes);
    			else if(isValidQuiz($newPath)){
    			    $auxQuiz = json_decode(file_get_contents($path.$value),true);
    			    $auxQuiz['info']['slug'] = substr($value,0,strlen($value)-5);
    			    if(!$auxQuiz['info']['badges']) throw new Exception('There is a Quiz without badges');
    			    if($auxQuiz = filterQuiz($auxQuiz))
    			    {
        			    $auxQuiz['info']['category'] = basename($path);
        			    //print_r($auxQuiz); die();
        			    if(!isset($auxQuiz['info']['status']) || $auxQuiz['info']['status']=='published')
        			    {
            				if($auxQuiz) array_push($qzes, $auxQuiz);
        			    }
    			    }
    			}
    		}
    		//else echo "No es directorio".$newPath."\n";
    	}
    	return $qzes;
    }
    
    function saveData($data){
        
        if($data === null) $this->throwError('Nothing sent to save');
        
        if(!is_array($data) && !is_object($data)) $this->throwError('The data sent to save must be an object or array');
        
        $result = file_put_contents($this->fileName,json_encode($data));
        if(!$result) $this->throwError('Error saving data into '.$this->fileName);
    }
    
    function getJsonByName($fileName){
        
        if(empty($fileName)) throw new Exception("The name of the json you are requesting is empty");
        
        if(!is_array($this->dataContent)) throw new Exception("There is only one json file as data model");
        
        foreach ($this->dataContent as $key => $jsonObject) {
            $file = pathinfo($key);
            if($file['filename'] == $fileName) return $jsonObject;
        }
        
        throw new Exception("There json file ".$fileName." was not found");
    }
    
    function generateDocs($mode='default'){

        $content = '<!DOCTYPE html><html><head></head><body style="margin:0px;padding:0px;overflow:hidden">';
        switch($mode)
        {
            case "readme":
                $content .= '<iframe src="https://assets.breatheco.de/live-demos/markdown-parser/?skin=api&path='.$this->host.'/README.md" frameborder="0" style="overflow:hidden;height:100vh;width:100%" height="100%" width="100%"></iframe>';
            break;
            default:
                $content .= '<h1>Available API Methods</h1>';
                $content .= '<hp>Host: '.$this->host.'</p>';
                $content .= '<ul>';
                if(isset($this->methods['GET'])) foreach($this->methods['GET'] as $name => $value) $content .= '<li>GET: '.$name.' -> '.json_encode($value).'</li>';
                if(isset($this->methods['POST'])) foreach($this->methods['POST'] as $name => $value) $content .= '<li>POST: '.$name.' -> '.json_encode($value).'</li>';
                if(isset($this->methods['PUT'])) foreach($this->methods['PUT'] as $name => $value) $content .= '<li>PUT: '.$name.' -> '.json_encode($value).'</li>';
                if(isset($this->methods['DELETE'])) foreach($this->methods['DELETE'] as $name => $value) $content .= '<li>DELETE: '.$name.' -> '.json_encode($value).'</li>';
                $content .= '</ul>';
            break;
        }
        $content .= '</body></html>';
        echo $content;
    }
    
    function throwError($msg){
	    $result = [];
	    $result['message'] = $msg;
        $result['code'] = 500;
        echo json_encode($result);
        die();
	}
	
	function throwSuccess($data){
	    $result = [];
	    if(!is_object($data) && !is_array($data))
	    {
    	    $result['message'] = $data;
            $result['code'] = 200;
	    }
	    else{
	        $result = $data;
	    }
        echo json_encode($result);
        die();
	}
}