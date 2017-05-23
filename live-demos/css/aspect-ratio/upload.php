<?php

if (empty($_FILES) and empty($_POST['song-type']) and empty($_POST['song-name']))
{
	header("HTTP/1.0 404 Not Found");
}

header('Content-Type: application/json');
$ds = '/';  //1
 
try
{
		if (!empty($_FILES)) {
		    $storeFolder = 'uploaded';
		    $filesToDelete = glob(dirname( __FILE__ ) . $ds. $storeFolder . $ds.'*'); // get all file names
			foreach($filesToDelete as $f){ // iterate files
			  if(is_file($f))
			    unlink($f); // delete file
			}
			
		    if(!file_exists(dirname( __FILE__ ).'/'.$storeFolder)) throw new Exception("The store folder does not exist", 1);
			foreach($_FILES as $file){
			    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
			    $ext = pathinfo($file['name'][0], PATHINFO_EXTENSION);
			    $tempName = $file['tmp_name'][0];
			    
			    $targetFileName = rand(0,10).'.'.$ext;
			    $targetFile =  $targetPath. $targetFileName;  //5
			    $result = move_uploaded_file($tempName,$targetFile); //6
			    if(!$result) throw new Exception("There was an error moving the files.", 1);
			}
		    echo json_encode(array("code"=>200, "url"=> $ds.$storeFolder.$ds.$targetFileName));
		}
		else throw new Exception("Invalid form data, please review and try again.", 1);
		
}
catch(Exception $e)
{
	echo json_encode(array("code"=>500, "msg"=>$e->getMessage()));
}

?>   