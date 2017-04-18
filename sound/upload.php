<?php

require_once('src/autoload.php');
if (empty($_FILES) and empty($_POST['song-type']) and empty($_POST['song-name']))
{
	header("HTTP/1.0 404 Not Found");
}

header('Content-Type: application/json');
$ds = '/';  //1
$secret = '6LfWah0UAAAAAOHQHVJA_rguSGTifS30leC6U4Dr';
 
$recaptcha = new \ReCaptcha\ReCaptcha($secret);
$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

try
{
	if ($resp->isSuccess()){
		if (!empty($_FILES) and (empty($_POST['song-type']) or empty($_POST['song-name']))) {
		    
		    $storeFolder = 'sounds/'.$_POST['song-type'].'/songs/uploded';
		    if(!file_exists(dirname( __FILE__ ).'/'.$storeFolder)) throw new Exception("Invalid song-type", 1);

		    $tempFile = $_FILES['file']['tmp_name'];          //3             
		    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
		    $targetFile =  $targetPath. $_FILES['file']['name'];  //5
		    $result = move_uploaded_file($tempFile,$targetFile); //6
		    if(!$result) throw new Exception("There was an error moving the files.", 1);
		    else echo json_encode(array("code"=>200));
		}
		else throw new Exception("Invalid form data, please review and try again.", 1);
		
	}else throw new Exception("Invalid captcha.", 1);
}
catch(Exception $e)
{
	echo json_encode(array("code"=>500, "msg"=>$e->getMessage()));
}

?>   