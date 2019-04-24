<?php
use \JsonPDO\JsonPDO;

class ReplitFunctions{
    
    static $api;
    
    public static function setAPI($api){
        self::$api = $api;
    }
    
    public static function getTemplates($profile_slug=null){
        $templatesPDO = new JsonPDO('_templates/','{}',false);
		$baseTemplates = $templatesPDO->getAllContent();
		$used = [];
		$templates = []; 
		foreach($baseTemplates as $fileName => $temp){
			if($profile_slug && $fileName != ('_templates/'.$profile_slug.'.json')) continue;
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