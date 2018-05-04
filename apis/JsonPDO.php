<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
    
class JsonPDO{
    
    //Onyl used if the data is stored in just one json file
    var $fileName = null;
    
    //Only use when the data is stored in different files
    var $dataFiles = null;
    
    var $logger = null;
    
    //The real content already parse, if several datafiles where provided it will be an array of objects
    var $dataContent = null;
    
    var $debug = false;
    
    function __construct($dataPath = null,$defaultContent='{}',$debug = false){
        
	    $this->debug = $debug;
	    
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
    		    $newPath = $path.$value;
        		if($this->debug) echo "Recorriendo: $newPath \n";
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
            			    if($this->debug) echo "Incluyendo $newPath ...\n";
            			    array_push($this->dataFiles, $newPath);
            			    $this->dataContent[$newPath] = $this->parseFile($newPath, $defaultContent);
        			    }
        			} 
        		}
        	}
        	return $this->dataFiles;
        }
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
            if(empty($dataContent)) $this->throwError('Unable to get file "'.$fileName.'" contents or it was empty: ');
            
            return $dataContent;
        }
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
        if(!$this->fileName) throw new Exception('You need to specify the JSON file name');
        $result = file_put_contents($this->fileName,json_encode($data));
        if(!$result) $this->throwError('Error saving data into '.$this->fileName);
    }
    
    function toFile($fileName){
        $path = $this->getPathByName($fileName);
        return new FileInterface($path);
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
    
    function getPathByName($fileName){
        
        if(empty($fileName)) throw new Exception("The name of the json you are requesting is empty");
        
        if(!is_array($this->dataContent)) throw new Exception("There is only one json file as data model");
        
        foreach ($this->dataContent as $key => $jsonObject) {
            if(strpos($key,$fileName)) return $key;
        }
        
        throw new Exception("There json file ".$fileName." was not found");
    }
    
    function getAllContent(){
        return $this->dataContent;   
    }
    
    function throwError($msg){
	    throw new Exception($msg);
	}
}

class FileInterface{
    private $fileName = null;
    function __construct($fileName=null){
        if(!$fileName) throw new Exception('Missing file name');
        if(!file_exists($fileName)) throw new Exception('JSON file '.$fileName.' does not exists');
        $this->fileName = $fileName;
    }
    function save($data){
        if($data === null) $this->throwError('Nothing sent to save');
        
        if(!is_array($data) && !is_object($data)) $this->throwError('The data sent to save must be an object or array');
        if(!$this->fileName) throw new Exception('You need to specify the JSON file name');
        $result = file_put_contents($this->fileName,json_encode($data));
        if(!$result) $this->throwError('Error saving data into '.$this->fileName);
        
        return $data;
    }
}