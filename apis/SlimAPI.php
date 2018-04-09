<?php

class SlimAPI{
    
    var $app = null;
    var $db = [];
    var $allowedURLs = [
            'https://coding-editor-alesanchezr.c9users.io',
            'https://assets-alesanchezr.c9users.io',
            'https://assets.breatheco.de',
            'https://student.breatheco.de'
        ];
    var $allowedMethods = ['GET','POST','PUT','DELETE','OPTIONS'];
    
    function __construct($settings=null){
    	$c = new \Slim\Container([
    	    'settings' => [
    	        'displayErrorDetails' => true,
    	    ],
    	]);
    	$c['errorHandler'] = function ($c) {
    	    return function ($request, $response, $exception) use ($c) {
    	        return $c['response']->withStatus(500)
    	                             ->withHeader('Content-Type', 'application/json')
    	                             ->write( json_encode(['msg' => $exception->getMessage()]));
    	    };
    	};
        
	    $this->app = new \Slim\App($c);
	    
	    $allowedURLs = $this->allowedURLs;
	    $allowedMethods = $this->allowedMethods;
        if(isset($_SERVER['HTTP_ORIGIN'])){
            foreach($allowedURLs as $o)
                if($_SERVER['HTTP_ORIGIN'] == $o)
                {
                    $this->app->add(function ($req, $res, $next) use ($allowedURLs,$allowedMethods) {
                        $response = $next($req, $res);
                        return $response
                                ->withHeader('Access-Control-Allow-Origin', '*')
                                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization, X-PINGOTHER')
                                ->withHeader('Access-Control-Allow-Methods', implode(",",$allowedMethods))
                                ->withHeader('Allow', implode(",",$allowedMethods));
                    });
                }
        }
    }
    
    public function addDB($key, $connector){
	    $this->db[$key] = $connector;
    }
    
    public function get($path, $callback){
        $this->app->get($path, $callback);
    }
    public function post($path, $callback){
        $this->app->post($path, $callback);
    }
    public function put($path, $callback){
        $this->app->put($path, $callback);
    }
    public function delete($path, $callback){
        $this->app->delete($path, $callback);
    }
    

    /*
     * Get an instance of the application.
     *
     * @return \Slim\App
     */
    public function run(){
        
        return $this->app->run();
    }
}