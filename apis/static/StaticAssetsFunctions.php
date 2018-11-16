<?php

class StaticAssetsFunctions{
    
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
    
    public static function deleteUnusedImages($fake=true){
    	
		$UCApi = new \Uploadcare\Api(UC_PUBLIC_KEY, UC_SECRET_KEY);
		
		$log = [];
		$localImages = self::$api->db['json']->getJsonByName('images');
		$localImagesIds = array_column($localImages, 'uuid');
		function deleteUnsyncedImages($localImagesIds, $UCApi, $next='', &$log){
			$resp = $UCApi->request('GET',$next);
			$log[] = $resp->total." images were found on the CDN";
			$imgToDelete = [];
			foreach($resp->results as $remoteImage){
				if(!in_array($remoteImage->uuid, $localImagesIds)){
					if(!$fake) $UCApi->request('DELETE','/files/'.$remoteImage->uuid.'/');
					$imgToDelete[] = $remoteImage->uuid;
					$log[] = "The image ".$remoteImage->uuid." was supposed to be deleted";
				}
			}
					
			if(!empty($resp->next)) deleteUnsyncedImages($localImagesIds, $UCApi,$resp->next,$log);
		}
		deleteUnsyncedImages($localImagesIds, $UCApi, '/files/', $log);
		
		return $log;
    }
    
}