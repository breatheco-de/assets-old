<?php

namespace BreatheCode;

class SlackWrapper {
    private static $oldEndpoints = [
    	"users.admin.invite" => [
    		"method" => "POST",
    		"path" => "users.admin.invite"
    	]
    ];
    public static function getWrappers($newToken, $oldToken) {
        
        return [
            'old' => new \wrapi\wrapi('https://slack.com/api/',	// base url for the API
                self::$oldEndpoints,
                [
                    "headers" => [
                        "Content-Type" => "application/x-www-form-urlencoded",
                        "User-Agent" => "slack-wrapi"
                    ],
                    "query" => ["token" => $oldToken]
                ]
            ),
            'new' => new \wrapi\slack\slack($newToken)
        ];
    }

}

?>
