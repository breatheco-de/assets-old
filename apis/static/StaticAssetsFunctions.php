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

    public static function deletePendingImages(){
    	
		$UCApi = new \Uploadcare\Api(UC_PUBLIC_KEY, UC_SECRET_KEY);
		
		$log = [];
        $images = (array) self::$api->db['json']->getJsonByName('pending_delete');
        
        if(!is_file("./data/deleted.json")){
            file_put_contents("./data/deleted.json", "{}");     // Save our content to the file.
        }
        $allDeleted = (array) self::$api->db['json']->getJsonByName('deleted');
        $recentlyDeleted = [];
        foreach($images as $uuid => $img){
            $img = (array) $img;
            if($img["deleted"]){
                $resp = $UCApi->request('DELETE','/files/'.$uuid.'/');
                // print_r($resp);die();
            } 
            $allDeleted[$uuid] = $images[$uuid];
            $recentlyDeleted[$uuid] = $images[$uuid];
            unset($images[$uuid]);
            $log[] = "The image ".$uuid." deleted";
        }

        self::$api->db['json']->toFile('deleted')->save($allDeleted);
        self::$api->db['json']->toFile('pending_delete')->save($images);

		return $recentlyDeleted;
    }


    public static function deletePendingUploads(){
    	
		$UCApi = new \Uploadcare\Api(UC_PUBLIC_KEY, UC_SECRET_KEY);
		
		$log = [];
        if(!is_file("./data/being_uploaded.json")){
            file_put_contents("./data/being_uploaded.json", "{}");     // Save our content to the file.
        }
        $images = (array) self::$api->db['json']->getJsonByName('being_uploaded');
        foreach($images as $uuid => $img){
            $resp = $UCApi->request('DELETE','/files/'.$uuid.'/');
            unset($images[$uuid]);
            $log[] = "The image ".$uuid." was deleted";
        }
        self::$api->db['json']->toFile('being_uploaded')->save($images);

		return $recentlyDeleted;
    }
    
}