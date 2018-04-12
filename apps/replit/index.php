<?php
    require('../../globals.php');
    if(!isset($_GET['r'])){
        echo "No replit specified";
        die();
    }
    
    $cohorts = file_get_contents(ASSETS_HOST.'/apis/replit/cohort');
    $cohorts = (array) json_decode($cohorts);
    if(!$cohorts){
        echo "There was a problem loading the replits";
        die();
    }
    
    if(isset($_GET['c'])){
        if($cohorts[$_GET['c']]){
            $replits = (array) $cohorts[$_GET['c']];
            if(isset($replits[$_GET['r']])) header("Location: ".$replits[$_GET['r']]);
            else echo "This cohort does not have that replit setup yet, talk yo your teacher";
            die();
        } 
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Replit Selector</title>
        <link rel="stylesheet" href="bootstrap4.min.css" type="text/css" />
    </head>
    <body>
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <img class="float-left mr-2" src="/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32"></img>
                    <h3 class="ml-2">We could not find an exercise, please select your cohort:</h3>
                </div>
            </div>
            <select class="form-control" onChange="redirect(event);">
                <option value="-1" selected>Select a cohort</option>
                <?php foreach($cohorts as $key => $repls){ ?>
                    <option value="<?php echo $key; ?>"><?php echo $key; ?></option>
                <?php } ?>
            </select>
        </div>
        <script type="text/javascript">
            function redirect(e){ 
                if(e.target.value != -1) 
                    location.href = '/apps/replit/?r=<?php echo $_GET['r']; ?>&c='+e.target.value; 
            }
        </script>
    </body>
</html>