<?php
    use BreatheCode\BCWrapper as BC;
	require('../../vendor/autoload.php');
    require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
	require('../../globals.php');
	
    BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
    BC::setToken(BREATHECODE_TOKEN);
    
    $error = '';
    if(isset($_GET['error'])) $error = $_GET['error'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login - BreatheCode Platform</title>
        <link rel="stylesheet" href="bootstrap4.min.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div class="col-10 col-md-6 login-box p-4 mx-auto">
            <?php if(!$error){ ?>
                <div class="text-center">
                    <img src="/apis/img/images.php?blob&random&cat=icon&tags=breathecode,64"></img>
                    <h2 class="p-2">Welcome to BreatheCode</h2>
                </div>
                <div>
                    <form action="process_login.php" method="post" onSubmit="submitForm(e);">
                        <?php if($error){ ?>
                            <div class="alert alert-danger">
                                Invalid information
                            </div>
                        <?php } ?>
                        
                        <div class="form-group text-left">
                            <input type="text" class="form-control" name="username" placeHolder="type your email...">
                        </div>
                        <div class="form-group text-left">
                            <input type="password" class="form-control" name="password" placeholder="type your password...">
                        </div>
        
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary btn-lg form-control">Log In</button>
                        </div>
                    </form>
                </div>
            <?php } ?>
        </div>
    </body>
</html>