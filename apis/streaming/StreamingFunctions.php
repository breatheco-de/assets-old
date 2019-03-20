<?php

class StreamingFunctions{
    
    static $api;
    
    public static function setAPI($api){
        self::$api = $api;
    }
    
	static function getStreamingLink($it, $cohort){
		$args = [
			"it" => $it,
			"is_link" => true,
			"w" => "710",
			"h" => "405",
			"pause" => true,
			"title" => $cohort->name,
			"skin" => 3,
			"repeat" => null,
			"brandNW" => true,
			"start_volume" => "100",
			"bg_gradient1" => "#ffffff",
			"bg_gradient2" => "#e9e9e9",
			"fullscreen" => true,
			"fs_mode" => 2,
			"skinAlpha" => 50,
			"colorBase" => "#000000",
			"colorIcon" => "#ffffff",
			"colorHighlight" => "#5cbbf5",
			"direct" => false,
			"no_ctrl" => null,
			"auto_hide" => true,
			"viewers_limit" => false,
			"cc_position" => "bottom",
			"cc_positionOffset" => 70,
			"cc_multiplier" => 0.03,
			"cc_textColor" => "#ffffff",
			"cc_textOutlineColor" => "#ffffff",
			"cc_bkgColor" => "#000000",
			"cc_bkgAlpha" => 0.1,
			//"image" => "https://static1.webvideocore.net/i/stores/2/items/bg/d/d9/d962119f2ee969ee770b5161efe120fb.png",
			"mainBg_Color" => "#ffffff",
			"aspect_ratio" => "16:9",
		];
		return "https://play.webvideocore.net/popplayer.php?".http_build_query($args);	
	}
	static function getIframeLink($it, $cohort){
		$args = [
			"live_id"=>$it,
			"l"=>155452,
			"w"=>720,
			"h"=>800,
			"p"=>"4GE35AGFE12D4C4",
			"title"=> $cohort->name,
			"bgcolor1"=>"#ffffff",
			"bgcolor2"=>"#e0e0e0",
			"hide_playlist" => null,
			"hide_description"=>null,
			"hide_live_chat"=>true,
			"layout"=>"default",
			"is_inversed"=>null,
			"theme"=>"light",
			"image"=>null,
			"use_html5"=>true,
			"sel_playlist"=>null,
			"sel_multiplaylist"=>null,
			"is_responsive"=>true,
			"is_vertical"=>null,
			"one_thumb_per_row"=> true,
			"disable_hash"=>true,
			"skinAlpha"=>50,
			"colorBase"=>null,
			"colorIcon"=>null,
			"colorHighlight"=>"#5cbbf5",
			"fs_popin"=>null,
			"start_volume"=>null,
			"close_button"=>null,
			"player_align"=>"NONE",
			"player_bar"=>true,
			"auto_play"=>null,
			"auto_hide_player_controls"=>true,
			"chat_position"=>null,
			"description_position"=>null,
			"playlist_position"=> 1,
			"allow_fullscreen"=>true,
			"player_start_volume"=>null,
			"widget_height_behavior"=>false
		];
		return "https://play.webvideocore.net/popapp.php?".http_build_query($args);	
	}
	static function getRTMPLink($key){
		return "rtmp://livecast.webvideocore.net/live/$key";	
	}
    
}