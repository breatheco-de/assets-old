<?php
    if(!isset($_GET['r'])){
        echo "<h1>No replit specified</h1>";
        die();
    }

    function sanitizeURL($redirectUrl){
        $assetsToken = '';
        if(isset($_GET['assets_token']) and $_GET['assets_token']!='') $assetsToken = $_GET['assets_token'];
        else return $redirectUrl;
        
        $hashPosition = stripos($redirectUrl,'/#');
        if($hashPosition && stripos($redirectUrl,'gitpod.io')){
            $after =  substr($redirectUrl, $hashPosition+2);
            $redirectUrl = "https://gitpod.io/#BC_ASSETS_TOKEN=$assetsToken/$after";
        }
        
        return $redirectUrl;
    }

    $templateReplits = file_get_contents("./data.json");
    $templateReplits = (array) json_decode($templateReplits);
    if(!$templateReplits || !isset($templateReplits[$_GET['r']])){
        echo "<h1>Exercise template not found for ".$_GET['r'].", contact your teacher immediately</h1>";
        die();
    }
    else if(!isset($templateReplits[$_GET['r']]->value)){
        echo "<h1>Missing value for ".$_GET['r'].", contact your teacher immediately</h1>";
        die();
    }    
    else{
        $redirectUrl = sanitizeURL($templateReplits[$_GET['r']]->value);
        header("Location: ".$redirectUrl, true, 302);
        echo "Redirecting to... ".$redirectUrl;
    }
