<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$quizes = array();
const BASE_PATH = './q/';
$quizes = createDirectory(BASE_PATH, $quizes);

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
			    if($auxQuiz = filterQuiz($auxQuiz))
			    {
    			    $auxQuiz['info']['category'] = basename($path);
        			if($auxQuiz) array_push($qzes, $auxQuiz);
			    }
			}
		}
		//else echo "No es directorio".$newPath."\n";
	}
	return $qzes;
}

function isValidQuiz($path){
	$allowedExtensions = array("json");
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	
	if(in_array($ext,$allowedExtensions)) return $ext;
	else return false;
}

function filterQuiz($q)
{
    if(isset($_GET['slug']) and $_GET['slug']!=$q['info']['slug']) return false;
    if(!isset($_GET['slug'])) unset($q['questions']);
    
    return $q;
}

function printJSON($content){
	if(!$content) $content = array();
	header("Content-type: application/json"); 
	echo json_encode($content);
	die();
}

if(isset($_SERVER['HTTP_REFERER']) and (strpos($_SERVER['HTTP_REFERER'], "breatheco") || strpos($_SERVER['HTTP_REFERER'], "4geeksacademy"))){
	header("Access-Control-Allow-Origin: *");
}

printJSON($quizes);