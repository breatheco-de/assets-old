<?php

namespace BreatheCode\Activity;

use \Exception;

abstract class BCActivity{

    public $possibleTypes = [];
    abstract public function encode($student, $data);
    abstract public function decode($data);
    abstract public function filter($query, $filters);

    public static function factory($type){

        $ins = new ErrorActivity();
        if($ins->setType($type)) return $ins;
        $ins = new StudentActivity();
        if($ins->setType($type)) return $ins;

        if(empty($type)) throw new Exception('Empty Activity Type');
        else throw new Exception('Invalid Activity Type: '.$type);
    }

    public static function getAllTypes(){

        $error = new ErrorActivity();
        $student = new StudentActivity();
        $types = array_merge($error->possibleTypes, $student->possibleTypes);

        return $types;
    }

    public function setType($type){
        if(isset($this->possibleTypes[$type])){
            $this->type = $type;
            return true;
        }
        return false;
    }

    public function trackOnActiveCampaign(){ return $this->possibleTypes[$this->type]["track_on_active_campaign"]; }
    public function trackOnLog(){
        //if($this->type == "breathecode_login"){
            //print_r($this->type);die();
        //}
        return $this->possibleTypes[$this->type]["track_on_log"];
    }
}