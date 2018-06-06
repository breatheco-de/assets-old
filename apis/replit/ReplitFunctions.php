<?php

class ReplitFunctions{
    
    static $api;
    
    public static function setAPI($api){
        self::$api = $api;
    }
    
    public static function getTemplates(){
        $templatesPDO = new JsonPDO('_templates/','{}',false);
		$baseTemplates = $templatesPDO->getAllContent();
		$used = [];
		$templates = []; 
		foreach($baseTemplates as $temp){
			foreach($temp as $replit){
				if(!isset($used[$replit->slug])){
					$used[$replit->slug] = true;
					$templates[] = $replit;
				} 
			}
		} 
		return $templates;
    }
    
}