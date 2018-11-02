<?php
function debug($s){print_r($s);die();}

require_once('apis/TestFunctions.php');
require_once('apis/JsonPDO.php');
$json = new JsonPDO('apis/course-report/data/','{}',false);
$catalog = (array) $json->getJsonByName('programs');
$schools = $json->getJsonByName('schools');
$template = $json->getJsonByName('_template');

$json = new JsonPDO('apis/course-report/data/programs/','{}',false);
$programs = (array) $json->getAllContent();
foreach($programs as $slug => $program){
    $newSlug = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($slug));
    $programs[$newSlug] = $program;
    unset($programs[$slug]);
}

class Logger{
    private $_res = ["err" => [],"warn" => []];
    private $_context = '';
    function context($slug){$this->_context=$slug;}
    function err($msg){$this->_res["err"][]=$this->_context.' -> '.$msg;}
    function warn($msg){$this->_res["warn"][]=$this->_context.' -> '.$msg;}
    function report(){
        echo "Running...\n\n";
        foreach($this->_res["err"] as $error){
            echo "\033[31m Error:\033[0m";
            echo " ".$error . "\n";
        } 
        foreach($this->_res["warn"] as $error){
            echo "\033[33m Warning: \033[0m";
            echo $error . "\n";
        } 
        echo "\n";
        echo "Errors: ".count($this->_res["err"]);
        echo " Warnings: ".count($this->_res["warn"]);
        echo "\n";
        return $this->_res;
    }
} 
$l = new Logger();
foreach($schools as $key => $school){
    $l->context($key);
    if($key != $school->slug) $l->err("The slug for $key does not match with ".$school->slug);
}

foreach($programs as $key => $p){
    $l->context($key);
    if($key != $p['program_slug'].'-'.$p['school_slug']) $l->err("The name of this file should be ".$p['program_slug'].'-'.$p['school_slug']." to be consistent with the specified school and program");
    if(empty($catalog[$p['program_slug']])) $l->err("The program ".$p['program_slug']." does not exists in the programs.json");
    if(empty($schools[$p['school_slug']])) $l->err("The school ".$p['school_slug']." does not exists in the list of schools.json");
}

$l->report();

