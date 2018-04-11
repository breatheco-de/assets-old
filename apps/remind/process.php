<?php

    if(!isset($_POST['id']) || !isset($_POST['password']) || !isset($_POST['repeat']) || !isset($_POST['token'])){
        header("Location: index.php?error=missing");
        die();
    }
    
	require('../../vendor/autoload.php');
    require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
	require('../../apis/api_globals.php');
	
	use BreatheCode\BCWrapper as BC;
    BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
    BC::setToken(BREATHECODE_TOKEN);
    
    try{
        $user = BC::setPassword([
            'user_id'=>$_POST['id'],
            'password'=>$_POST['password'],
            'repeat'=>$_POST['repeat'],
            'token'=>$_POST['token']
        ]);
    }
    catch(Exception $e){
        header("Location: index.php?error=missing");
        die();
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Change Password - BreatheCode Platform</title>
        <link rel="stylesheet" href="bootstrap4.min.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div class="container">
            <div class="text-center p-2 mt-5 mb-5">
                <img src="/apis/img/images.php?blob&random&cat=icon&tags=breathecode,64"></img>
            </div>
            <?php if(isset($user)){ ?>
                <div class="alert alert-success text-center">You password has been successfully changed</div>
            <?php } else { ?>
                <div class="alert alert-success">There seems to be a problem updating your password</div>
            <?php } ?>
        </div>
    </body>
</html>