<?php
    require('../../globals.php');
    require_once '../../vendor/autoload.php';

    $loader = new Twig_Loader_Filesystem('./templates');
    $twig = new Twig_Environment($loader, array(
        //'cache' => './cache',
    ));
    ///apps/replit?r=layouts&c=en-iii
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

    $assetsToken = '';
    if(isset($_GET['assets_token'])) $assetsToken = $_GET['assets_token'];

    function redirect_based_on_profile($_twig){
        if(!empty($_GET['profile'])){
            $templateReplits = file_get_contents(ASSETS_HOST.'/apis/replit/template/'.$_GET['profile']);
            $templateReplits = (array) json_decode($templateReplits);
            if(!$templateReplits){
                echo $twig->render('pick-cohort.html', array('replit' => $_GET['r']));
                die();
            }
            else{
                $replit_slug = $_GET['r'];
                forEach($templateReplits as $r){
                    $r = (array) $r;
                    if($r["slug"] == $replit_slug and !empty($r["value"])){
                        header("Location: ".$r["value"], true, 302);
                        echo "Redirecting to... ".$r["value"];
                    }
                }
                echo $_twig->render('pick-cohort.html', array('replit' => $_GET['r']));
                die();
            }
        }
    }

    // Redirection based on the cohort
    if(isset($_GET['c'])){
        if(!empty($cohorts[$_GET['c']])){
            $replits = (array) $cohorts[$_GET['c']];
            if(isset($replits[$_GET['r']])){
                if (!empty($replits[$_GET['r']])) {

                    $redirectUrl = $replits[$_GET['r']];
                    $hashPosition = stripos($redirectUrl,'/#');
                    if($hashPosition && stripos($redirectUrl,'gitpod')){
                        $after =  substr($redirectUrl, $hashPosition+2);
                        $redirectUrl = "https://gitpod.io/#BC_ASSETS_TOKEN=$assetsToken/$after";
                    }

                    $headers = get_headers($redirectUrl);
                    foreach($headers as $h){
                        if(     strpos(strtolower($h),'SAMEORIGIN')>-1
                        //    ||  strpos(strtolower($h), 'x-frame-options: deny')>-1
                            || strpos(strtolower($h),"http/1.1 404 not found")>-1
                        ){
                            header("Location: /apis/replit/404.php?link=".urlencode($redirectUrl));
                            echo "<h2>";
                                echo "This exercise needs to be opened in a new window, please click on this url to opent it: ";
                                echo "<a target='_blank' href='".$redirectUrl."'>".$redirectUrl."</a>";
                            echo "</h2>";
                            die();
                        }
                    }

                    header("Location: ".$redirectUrl, true, 302);
                    echo "Redirecting to... ".$redirectUrl;

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
                redirect_based_on_profile($twig);
                echo $twig->render('error.html', array('msg' => "This cohort: ".$_GET['c']." does not have '".$_GET['r']."' excercises setup yet, talk to your teacher to report the issue."));
                die();
            }
        }
        else{
            redirect_based_on_profile($twig);
            echo $twig->render('pick-cohort.html', array('replit' => $_GET['r']));
            die();
        }
    }
    
    redirect_based_on_profile($twig);
    echo $twig->render('pick-cohort.html', array('replit' => $_GET['r']));
    die();
?>