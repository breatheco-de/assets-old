<?php

namespace AC;

use GuzzleHttp\Client;

class Connector{
    
    var $baseURL = null;
    var $token = null;
    var $client = null;
    
    function __construct($baseURL, $token){
        
        $this->baseURL = $baseURL;
        $this->token = $token;
        
        $this->client = new Client([
            'base_uri' => $this->baseURL,
            'timeout'  => 2.0,
        ]);
        
    }
    
    function sampleRequest(){
        $response = $this->client->get('/',['api_key' => $this->token]);
        $body = $response->getBody();
        print_r($body->getContents()); die();
    }
}