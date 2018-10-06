<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$images = array();
const BASE_PATH = './';
$images = createDirectory(BASE_PATH, $images);

function createDirectory($path,$imgs){
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
			if(is_dir($newPath)) $imgs = createDirectory($newPath,$imgs);
			$auxImg = array(
					"ext" => isImg($value),
					"category" => basename($path), 
					"name" => $value, 
					"url" => $path.$value
				);
			$auxImg = filterImage($auxImg);
			if($auxImg) array_push($imgs, $auxImg);
		}
		//else echo "No es directorio".$newPath."\n";
	}
	return $imgs;
}

function filterImage($img){
	
	if(!$img['ext']) return false;
	if($img['category']=='.') return false;
	if(isset($_GET['cat']) and $img['category']!=$_GET['cat']) return false;
	
	$tags = explode('.',$img['name']);
	array_pop($tags);
	$img['tags'] = $tags;
	
	if(isset($_GET['tags']) || isset($_GET['tag']))
	{
		$stringTags = '';
		if(isset($_GET['tags'])) $stringTags = $_GET['tags'];
		else if(isset($_GET['tag'])) $stringTags = $_GET['tag'];
		$tagArray = explode(',', $stringTags);
		foreach($tagArray as $t) if(!in_array($t,$tags)) return false;
	}
	
	return $img;
}

function isImg($path){
	$imageExtensions = array("png","jpg","jpeg","gif","ico");
	$ext = pathinfo($path, PATHINFO_EXTENSION);
	
	if(in_array($ext,$imageExtensions)) return $ext;
	else return false;
}

function printFile($files){
	
    if ($files and count($files)>0 and file_exists($files[0]['url'])) {
		$file = $files[0];
        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Cache-Control: public"); // needed for internet explorer
        header("Content-Type: ".mime_content_type($file['url']));
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length:".filesize($file['url']));
        
		if(isset($_GET['size']))
		{
			$sizeArray = explode(',',$_GET['size']);
			if(isset($sizeArray[0])){
				$width = $sizeArray[0];
				if(isset($sizeArray[1])) $heigh = $sizeArray[1];
				else $heigh = 0;
			}
			else throw new Exception('Invalid Image Size');
			// read the img
			$image = new \Imagick($file['url']);
			$image->scaleImage($width, $heigh);
			echo $image->getImageBlob();
		}
        else echo file_get_contents($file['url']);
        
        die();
    } else {
    	header("HTTP/1.0 404 Not Found");
        die("Error: Image not found.");
    } 
}

function printJSON($content){
	if(!$content) $content = array();
	header("Content-type: application/json"); 
	echo json_encode($content);
	die();
}

function printResult($result){
	if(isset($_GET['blob'])) printFile($result);
	else printJSON($result);
}

if(isset($_SERVER['HTTP_REFERER']) and (strpos($_SERVER['HTTP_REFERER'], "breatheco") || strpos($_SERVER['HTTP_REFERER'], "4geeksacademy"))){
	header("Access-Control-Allow-Origin: *");
}

if(isset($_GET['gettags']))
{
	$resultTags = array();
	foreach($images as $img)
		foreach($img['tags'] as $tag) if(!in_array($tag, $resultTags)) array_push($resultTags,$tag);
	
	printJSON($resultTags);
}


if(isset($_GET['getcategories']))
{
	$resultCategories = array();
	foreach($images as $img)
		if(!in_array($img['category'], $resultCategories)) array_push($resultCategories,$img['category']);
	
	printJSON($resultCategories);
}

if(count($images)==0) printResult(null);
$result = $images;
if(isset($_GET['random'])) $result = $images[rand(0,count($images)-1)];
printResult($result);