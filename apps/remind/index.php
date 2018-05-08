<?php
    use BreatheCode\BCWrapper as BC;
    $error = true;
    if(!isset($_GET['error'])){
        
        if(!isset($_GET['id']) || !isset($_GET['t'])){
            header("HTTP/1.0 404 Not Found");
            die();
        }
    	require('../../vendor/autoload.php');
        require('../../vendor_static/breathecode-api/BreatheCodeAPI.php');
    	require('../../globals.php');
    	
        BC::init(BREATHECODE_CLIENT_ID, BREATHECODE_CLIENT_SECRET, BREATHECODE_HOST, API_DEBUG);
        BC::setToken(BREATHECODE_TOKEN);
        try{
            $user = BC::getPasswordToken(['user_id'=>$_GET['id'],'token'=>$_GET['t']]);
        }
        catch(Exception $e){ 
            //print_r($e->getMessage()); die();
        }
        
        if(!empty($user)) $error=false;
    } 
    else $error = false;
?>
<!DOCTYPE html>
<html>
    <head>
        <?php if(isset($_GET['invite'])){ ?>
        <title>Invite - BreatheCode Platform</title>
        <?php } else { ?>
        <title>Remind Password - BreatheCode Platform</title>
        <?php } ?>
        <link rel="stylesheet" href="bootstrap4.min.css" type="text/css" />
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
        <div class="col-10 col-md-6 login-box p-4 mx-auto">
            <?php if(!$error){ ?>
                <div class="text-center">
                    <img src="/apis/img/images.php?blob&random&cat=icon&tags=breathecode,64"></img>
                    <?php if(isset($_GET['invite'])){ ?>
                    <h2 class="p-2">Welcome to BreatheCode</h2>
                    <p>Please choose a password to continue</p>
                    <?php } else { ?>
                    <h2 class="p-2">Reset your password</h2>
                    <?php } ?>
                </div>
                <div>
                    <form action="process.php" method="post" onSubmit="submitForm(e);">
                        <?php if(isset($_GET['error'])){ ?>
                            <div class="alert alert-danger">
                                Invalid information
                            </div>
                        <?php } ?>
                        <?php if(isset($user)){ ?>
                        <input type="hidden" name="id" value="<?php echo $user->id ?>"/>
                        <input type="hidden" name="token" value="<?php echo $user->token ?>"/>
                        <?php if(isset($_GET['invite'])){ ?>
                        <input type="hidden" name="invite" value="<?php echo $user->token ?>"/>
                        <?php } ?>
                        <?php if(isset($_GET['callback'])){ ?>
                        <input type="hidden" name="callback" value="<?php echo $_GET['callback']; ?>"/>
                        <?php } ?>
                        <div class="form-group text-left">
                            <label class="form-control-label">Your email</label>
                            <input type="text" class="form-control" readonly="readonly" value="<?php echo $user->username; ?>">
                        </div>
                        <?php } ?>
                        <div class="form-group text-left">
                            <label class="form-control-label">New Password</label>
                            <input type="password" class="form-control" name="password">
                        </div>
                        <div class="form-group text-left">
                            <label class="form-control-label">Repeat Password</label>
                            <input type="password" class="form-control" name="repeat">
                        </div>
        
                        <div class="text-right">
                            <?php if(isset($_GET['invite'])){ ?>
                            <button type="submit" class="btn btn-primary btn-lg form-control">Finish Sign Up</button>
                            <?php } else { ?>
                            <button type="submit" class="btn btn-primary btn-lg form-control">Change Password</button>
                            <?php } ?>
                        </div>
                    </form>
                </div>
            <?php } else {?>
            <div class="alert alert-danger">
                <?php if(isset($_GET['invite'])){ ?>
                Invalid invitation link
                <?php } else { ?>
                Invalid remind password link
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <script type="text/javascript">
            function submitForm(){ 
                document.querySelector('button').style.display = "none";
            }
        </script>
    </body>
</html>