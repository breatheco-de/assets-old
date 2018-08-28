<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;
use \Firebase\JWT\JWT;

function debug($data){
    print_r($data); die();
}
class SlimAPI{
    
    public static $INFO = 'info';
    public static $NOTICE = 'notice';
    public static $WARNING = 'warning';
    public static $ERROR = 'error';
    private $logPaths = [
        'activity' => '../.logs/api_activity.log'
    ];
    
    var $app = null;
    var $appName = null;
    var $db = [];
    var $debug = false;
    var $allowedURLs = [
            'https://coding-editor-alesanchezr.c9users.io',
            'https://assets-alesanchezr.c9users.io',
            'https://assets.breatheco.de',
            'https://bc-js-clients-alesanchezr.c9users.io',
            'https://bc-admin-alesanchezr.c9users.io',
            'http://bc-admin-alesanchezr.c9users.io',
            'https://student.breatheco.de',
            'https://admin.breatheco.de',
            'https://www.student.breatheco.de'
        ];
    var $allowedMethods = ['GET','POST','PUT','DELETE','OPTIONS'];
    
    function __construct($settings=null){
        if(!empty($settings['name'])) $this->appName = $settings['name'];
        if(!empty($settings['debug'])) $this->debug = $settings['debug'];
        if(!empty($settings['allowedURLs']) && $this->debug){
            if(is_array($settings['allowedURLs']))
                $this->allowedURLs = array_push($settings['allowedURLs'], $this->allowedURLs);
            else if($settings['allowedURLs'] == 'all') $this->allowedURLs = ['all'];
            else throw new Exception('Invalid setting value for allowedURLs');
        }

    	$c = new \Slim\Container([
    	    'settings' => [
    	        'displayErrorDetails' => $this->debug,
    	    ],
    	]);
    	if(!$this->debug){
        	$c['errorHandler'] = function ($c) {
        	    return function ($request, $response, $exception) use ($c) {
        	        
        	        //$this->log(self::$ERROR, $exception->getMessage());
                
        	        $code = $exception->getCode();
                    if(!in_array($code, [500,400,301,302,401,404,403])) $code = 500;
    
        	        return $c['response']->withStatus($code)
        	                             ->withHeader('Content-Type', 'application/json')
        	                             ->withHeader('Access-Control-Allow-Origin', '*')
        	                             ->write( json_encode(['msg' => $exception->getMessage()]));
        	    };
        	};
        	$c['phpErrorHandler'] = function ($c) {
        	    return function ($request, $response, $exception) use ($c) {
        	        
        	        //$this->log(self::$ERROR, $exception->getMessage());
        	        $code = $exception->getCode();
                    if(!in_array($code, [500,400,301,302,401,404])) $code = 500;
        	        return $c['response']->withStatus($code)
        	                             ->withHeader('Content-Type', 'application/json')
        	                             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        	                             ->withHeader('Access-Control-Allow-Origin', '*')
        	                             ->write( json_encode(['msg' => $exception->getMessage()]));
        	    };
        	};
        	//$c['notFoundHandler'] = $phpErrorHandler;
    	}
    	else{
        	$c['phpErrorHandler'] = $c['errorHandler'] = function ($c) {
        	    return function ($request, $response, $exception) use ($c) {

        	        $code = $exception->getCode();
                    if(!in_array($code, [500,400,301,302,401,404,403])) $code = 500;
        	        return $c['response']->withStatus($code)
        	                             ->withHeader('Content-Type', 'application/json')
        	                             ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        	                             ->withHeader('Access-Control-Allow-Origin', '*')
        	                             ->write( json_encode([
        	                                 'msg' => $exception->getMessage(),
        	                                 'trace' => array_map(function($trace){ 
        	                                     if(!isset($trace['file'])) $trace['file'] = '';
        	                                     if(!isset($trace['line'])) $trace['line'] = '';
        	                                     if(!isset($trace['class'])) $trace['class'] = '';
        	                                     if(!isset($trace['function'])) $trace['function'] = '';
        	                                     return sprintf("\n%s:%s %s::%s", $trace['file'], $trace['line'], $trace['class'], $trace['function']);
        	                                 },debug_backtrace())
    	                                 ]));
        	    };
        	};
    	}
        
	    $this->app = new \Slim\App($c);
	    
	    $allowedURLs = $this->allowedURLs;
	    $allowedMethods = $this->allowedMethods;
        if(isset($_SERVER['HTTP_ORIGIN']) || in_array('all', $allowedURLs)){
            foreach($allowedURLs as $o)
                if((isset($_SERVER['HTTP_ORIGIN']) && $_SERVER['HTTP_ORIGIN'] == $o) || $o == 'all')
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
        return $this->app->get($path, $callback);
    }
    public function post($path, $callback){
        return $this->app->post($path, $callback);
    }
    public function put($path, $callback){
        return $this->app->put($path, $callback);
    }
    public function delete($path, $callback){
        return $this->app->delete($path, $callback);
    }
    
    public function addReadme($path='/', $markdownPath='./README.md'){
        $slimAPI = $this;
        $this->app->get($path, function (Request $request, Response $response, array $args) use ($slimAPI, $markdownPath){
            return $response->write($slimAPI->readmeTemplate($markdownPath));
    	});
    }
    
    public function auth(){
        
        return function ($request, $response, $next) {

            $path = $request->getUri()->getPath();
            if($path != 'token/generate'){
                $token = '';
                if(isset($_GET['access_token'])){
                    $parts = explode('.', $_GET['access_token']);
                    if(count($parts)!=3) throw new Exception('Invalid access token', 403);
                    $token = $_GET['access_token'];
                } 
                else{
                    $authHeader = $_SERVER["HTTP_AUTHORIZATION"];
                    if(!empty($authHeader)){
                        if(strpos($authHeader,"JWT") === false) throw new Exception('Authorization header must contain JWT', 403);
                        $authHeader = str_replace("JWT ", "", $authHeader);
                        $parts = explode('.', $authHeader);
                        if(count($parts)!=3) throw new Exception('Invalid access token', 403);
                        $token = $authHeader;
                        
                    }
                    else throw new Exception('Invalid access token', 403);
                }

            	$decoded = JWT::decode($token, JWT_KEY, array('HS256'));
            }
        	
        	$response = $next($request, $response);
        
        	return $response;
        };

    }
    
    public function addTokenGenerationPath(){
        $this->app->post('/token/generate', function (Request $request, Response $response, array $args){
                
            if(empty($_POST['client_id']) || empty($_POST['client_pass']))
                throw new Exception('Missing credentials');
            
            if(JWT_CLIENTS[$_POST['client_id']] != $_POST['client_pass'])
                throw new Exception('Invalid credentials');

    		$token = array(
    		    "clientId" => $_POST['client_id'],
    		    "iat" => time(),
    		    "exp" => time() + 31556952000 // plus one year in miliseconds
    		);
    	
    		$token['access_token'] = JWT::encode($token, JWT_KEY);
            
            return $response->withJson($token);
    	});
    }
    
    public function jwt_encode($payload, $expiration=31556952000){
		$token = array(
		    "clientId" => $payload,
		    "iat" => time(),
		    "exp" => time() + $expiration
		);
	
		return JWT::encode($token, JWT_KEY);
    }
    /*
     * Get an instance of the application.
     *
     * @return \Slim\App
     */
    public function run(){
        
        return $this->app->run();
    }
    
    public function getContainer(){
        return $this->app->getContainer();
    }
    
    function readmeTemplate($readmePath){
        if(!file_exists($readmePath)) throw new Exception('Readme not found in path '.$readmePath, 404);
        if(!$this->appName) throw new Exception('You need to set a name for the API in order to use the Readme Generator');
        return '
    <!DOCTYPE html>
    <html>
        <head>
            <title>'.$this->appName.'</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/2.10.0/github-markdown.min.css" type="text/css" />
        </head>
        <body>
            <style type="text/css">
                img{max-height: 25px;}
                .markdown-body{ max-width: 800px; margin: 0 auto;}
            </style>
            <div class="markdown-body"></div>
            <script type="text/javascript">
                window.onload = function(){
                    fetch("'.$readmePath.'")
                    .then(resp => resp.text())
                    .then(function(text){
                        var converter = new showdown.Converter();
                        converter.setFlavor("github");
                        const html      = converter.makeHtml(text);
                        document.querySelector(".markdown-body").innerHTML = html;
                    }).catch(function(error){
                        console.error(error);
                    });
                }
            </script>
            <script type="text/javascript" src="https://cdn.rawgit.com/showdownjs/showdown/1.8.6/dist/showdown.min.js"></script>
        </body>
    </html>
        ';
    }
    
    public function sendMail($to,$subject,$message){
        
        $loader = new \Twig_Loader_Filesystem('../');
        $twig = new \Twig_Environment($loader);
        
        $template = $twig->load('email_template.html');
        
        $client = SesClient::factory(array(
            'version'=> 'latest',     
            'region' => 'us-west-2',
            'credentials' => [
                'key'    => S3_KEY,
                'secret' => S3_SECRETE,
            ]
        ));
        
        try {
             $result = $client->sendEmail([
            'Destination' => [
                'ToAddresses' => [
                    $to,
                ],
            ],
            'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => 'UTF-8',
                        'Data' => $template->render(['subject' => $subject, 'message' => $message]),
                    ],
        			'Text' => [
                        'Charset' => 'UTF-8',
                        'Data' => $message,
                    ],
                ],
                'Subject' => [
                    'Charset' => 'UTF-8',
                    'Data' => $subject,
                ],
            ],
            'Source' => 'info@breatheco.de',
            //'ConfigurationSetName' => 'ConfigSet',
        ]);
             $messageId = $result->get('MessageId');
             return true;
        
        } catch (SesException $error) {
            throw new Exception("The email was not sent. Error message: ".$error->getAwsErrorMessage()."\n");
        }
    }
    
    public function log($level, $msg, $data=null){
        
        if(empty($level) || empty($msg)) throw new Exception('Mising level or message');
        // create a log channel
        $log = new Logger('activity');
        if(!file_exists($this->logPaths['activity'])) throw new Exception('Activity log not found: '.$this->logPaths['activity']);
        $log->pushHandler(new StreamHandler($this->logPaths['activity'], Logger::DEBUG));
        
        // add records to the log
        if($data) $msg .= json_encode($data);
        switch($level){
            case self::$NOTICE: $log->notice($msg); break;
            case self::$ERROR: $log->error($msg); break;
            case self::$WARNING: $log->warning($msg); break;
            case self::$INFO: $log->info($msg); break;
        }
    }
    
    function validate($value, $key=null){
        $val = new Validator($value, $key);
        return $val;
    }
    function optional($value, $key=null){
        $val = new ValidatorOptional($value, $key);
        return $val;
    }
    
}
class ArgumentException extends Exception{
    protected $code = 400;
}
class Validator{
    var $value = null;
    var $key = null;
    var $optional = false;
    
    function __construct($value, $key=null){
        //if there is a key, the $value is an object a we need to grab the value inside of it
        if($key){
            $value = (array) $value;
            if(isset($value[$key])) $value = $value[$key];
            else $value = null;
        }
        
        $this->value = $value;
        $this->key = $key;
    }
    function smallString($min=1, $max=255){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = v::stringType()->length($min, $max)->validate($this->value);
        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
    function slug(){ 
        if(empty($this->value) && $this->optional) return null;

        $validator = preg_match('/^([a-zA-z])(-|_|[a-z0-9])*$/', $this->value);
        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
    function enum($options=[]){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = in_array($this->value, $options);
        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        $for .= ' it has to match one of the following: '.implode($options,",");
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
    function bigString($min=1, $max=4000){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = v::stringType()->length($min, $max)->validate($this->value);

        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
    function int(){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = v::intVal()->validate($this->value);
        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
    function email(){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = v::email()->validate($this->value);
        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        if(!$validator) throw new ArgumentException('Invalid email value: '.$for);
        return $this->value;
    }
    function url(){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = v::url()->validate($this->value);
        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
    function date(){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = v::date()->validate($this->value);
        $for = ($this->key) ? $this->value.' for '.$this->key : $this->value;
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
    function bool(){ 
        if(empty($this->value) && $this->optional) return null;
        
        $validator = v::boolType()->validate((bool) $this->value);
        $for = ($this->key) ? $this->value.' for '.$this->key : '';
        if(!$validator) throw new ArgumentException('Invalid value: '.$for);
        return $this->value;
    }
}
class ValidatorOptional extends Validator{
    public function __construct($value, $key=null){
        parent::__construct($value, $key);
        $this->optional = true;
    }
}