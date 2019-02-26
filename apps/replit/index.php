<?php
    require('../../globals.php');
    require_once '../../vendor/autoload.php';

    $loader = new Twig_Loader_Filesystem('./templates');
    $twig = new Twig_Environment($loader, array(
        //'cache' => './cache',
    ));
    
    if(!isset($_GET['r'])){
        echo $twig->render('pick-replit.html', array('msg' => 'No replit specified'));
        die();
    }
    $cohorts = file_get_contents(ASSETS_HOST.'/apis/replit/cohort?v'.mt_rand());
    $cohorts = (array) json_decode($cohorts);
    if(!$cohorts){
        echo $twig->render('error.html', array('msg' => "There was a problem loading the replits"));
        die();
    }
    
    if(isset($_GET['c'])){
        if(!empty($cohorts[$_GET['c']])){
            $replits = (array) $cohorts[$_GET['c']];
            if(isset($replits[$_GET['r']])){
                if (!empty($replits[$_GET['r']])) {
                    
                    $headers = get_headers($replits[$_GET['r']]);
                    foreach($headers as $h){
                        if(     strpos(strtolower($h),'SAMEORIGIN')>-1
                        //    ||  strpos(strtolower($h), 'x-frame-options: deny')>-1
                            || strpos(strtolower($h),"HTTP/1.1 404 Not Found")>-1
                        ){
                            header("Location: /apis/replit/404.php?link=".urlencode($replits[$_GET['r']]));
                            echo "<h2>";
                                echo "This exercise needs to be opened in a new window, please click on this url to opent it: ";
                                echo "<a target='_blank' href='".$replits[$_GET['r']]."'>".$replits[$_GET['r']]."</a>";
                            echo "</h2>";
                            die();
                        }
                    }
                    header("Location: ".$replits[$_GET['r']], true, 302);
                    echo "Redirecting to... ".$replits[$_GET['r']];
                
                } else {
                    echo $twig->render('error.html', array(
                        'msg' => "This cohort (".$_GET['c'].") does not have any exercises for '".$_GET['r']."'  setup yet, talk to your teacher to report the issue.",
                        'replits' => $replits[$_GET['r']]
                        ));
                    die();
                }
            } 
            else
            {
                echo $twig->render('error.html', array('msg' => "This cohort: ".$_GET['c']." does not have '".$_GET['r']."' excercises setup yet, talk to your teacher to report the issue."));
                die();
            }
        }
        else{
            echo $twig->render('pick-cohort.html', array('replit' => $_GET['r']));
            die();
        } 
    }
    echo $twig->render('pick-cohort.html', array('replit' => $_GET['r']));
    die();
?>