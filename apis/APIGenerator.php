<?php

class APIGenerator{
    
    var $fileName = null;
    var $fileContent = null;
    var $methods = [];
    
    function __construct($dataFile,$defaultContent='{}')
    {
        header("Content-type: application/json"); 
	    header("Access-Control-Allow-Origin: *");
	    
	    $this->fileName = $dataFile;
        $this->checkForFile($defaultContent);
    }
    
    function checkForFile($defaultContent){
        
        if(!file_exists($this->fileName)){
            $fh = fopen($this->fileName, 'a'); 
            if(is_string($defaultContent)) fwrite($fh,$defaultContent); 
            else fwrite($fh, json_encode($defaultContent)); 
            fclose($fh); 
            chmod($this->fileName, 0777); 
            
            $this->fileContent = json_decode($defaultContent);
        }
        else
        {
            $jsonContent = file_get_contents($this->fileName);
            $dataContent = json_decode($jsonContent);
            if($dataContent === null or $dataContent ===false){
                $this->throwError('Unable to get file content: '+json_last_error());
            } 
            else $this->fileContent = $dataContent;
            
        }
    }
    
    function method($methodName, $callback)
    {
        if(!$methodName or $methodName=='') throw new Exception('Please specify a method name');
        if(!$callback or !is_callable($callback)) throw new Exception('The callback need to be a callable function');
        $this->methods[$methodName] = $callback;
    }
    
    function run(){
        
        if(!isset($_GET['method'])) throw new Exception('No method defined');
        if(!isset($this->methods[$_GET['method']])) throw new Exception('Method '.$_GET['method'].' not recognized');

        try
        {
            $result = $this->methods[$_GET['method']]($this->fileContent);
            if($result === null) throw new Exception('Please return something on your API method callback for: '.$_GET['method']);
            
            $this->throwSuccess($result);
        }
        catch(Exception $e)
        {
            $this->throwError($e->getMessage());
        }        
    }
    
    function saveData($data){
        
        if($data === null) $this->throwError('Nothing sent to save');
        
        if(!is_array($data) && !is_object($data)) $this->throwError('The data sent to save must be an object or array');
        
        $result = file_put_contents($this->fileName,json_encode($data));
        if(!$result) $this->throwError('Error saving data');
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
	    $result['data'] = $data;
        $result['code'] = 200;
        echo json_encode($result);
        die();
	}
}