<?php
function debug($s){print_r($s);die();}
class Logger{
    private $_res = ["err" => [],"warn" => []];
    private $_context = '';
    function exitCode(){ return (count($this->_res["err"]) == 0) ? 0 : 1; }
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