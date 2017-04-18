<?php

require_once('src/autoload.php');

$ds = '/';  //1
$secret = '6LfWah0UAAAAAOHQHVJA_rguSGTifS30leC6U4Dr';
 
//$recaptcha = new \ReCaptcha\ReCaptcha($secret);
//$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
//if ($resp->isSuccess()){
print_r($_FILES);
	if (!empty($_FILES) and !empty($_POST['song-type']) and !empty($_POST['song-name'])) {
	    
	    $storeFolder = 'sounds/'.$_POST['song-type'];
	    if(!file_exists(dirname( __FILE__ ).'/'.$storeFolder)) throw new Exception("Invalid song-type", 1);

	    $tempFile = $_FILES['file']['tmp_name'];          //3             
	    $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;  //4
	    $targetFile =  $targetPath. $_FILES['file']['name'];  //5
	    move_uploaded_file($tempFile,$targetFile); //6
	}
	else throw new Exception("Invalid form data, please review and try again.", 1);
	
//}

?>   