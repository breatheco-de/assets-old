<?php
require('vendor/autoload.php');
require('apis/BreatheCodeMessages.php');
require('apis/TestUtils.php');

$l = new Logger();

BreatheCodeMessages::connect([
    'projectId' => 'breathecode-197918',
    'keyFilePath' => 'breathecode-47bde0820564.json'
]);

$templates = BreatheCodeMessages::getTemplates();
foreach ($templates as $slug => $t){
    if(!isset($t["type"])) $l->err("The message $slug needs a type: actionable or non-actionable");
    if(!isset($t["priority"])) $l->err("The message $slug needs a priority: HIGH or LOW");
    if(!isset($t["template"])){
        debug($t);
        $l->err("The message $slug needs a template");
    }
    else{
        if(!isset($t["template"]["subject"])) $l->err("The message $slug template needs a subject");
    }

    if($t["type"] == "actionable"){
        if(!isset($t["getURL"])) $l->err("The message $slug template ".$t["type"]." needs to have a getURL method");
    }
}


$l->report();
exit($l->exitCode());