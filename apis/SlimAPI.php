<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Respect\Validation\Validator as v;
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
            'https://student.breatheco.de',
            'https://admin.breatheco.de',
            'https://www.student.breatheco.de'
        ];
    var $allowedMethods = ['GET','POST','PUT','DELETE','OPTIONS'];
    
    function __construct($settings=null){
        if(!empty($settings['name'])) $this->appName = $settings['name'];
        if(!empty($settings['debug'])) $this->debug = $settings['debug'];

    	$c = new \Slim\Container([
    	    'settings' => [
    	        'displayErrorDetails' => $this->debug,
    	    ],
    	]);
    	if(!$this->debug)
    	{
        	$c['errorHandler'] = function ($c) {
        	    return function ($request, $response, $exception) use ($c) {
        	        
        	        $this->log(self::$ERROR, $exception->getMessage());

        	        $code = $exception->getCode();
                    if(!in_array($code, [500,400,301,302,401,404])) $code = 500;

        	        return $c['response']->withStatus($code)
        	                             ->withHeader('Content-Type', 'application/json')
        	                             ->write( json_encode(['msg' => $exception->getMessage()]));
        	    };
        	};
        	$c['phpErrorHandler'] = function ($c) {
        	    return function ($request, $response, $exception) use ($c) {
        	        
        	        $this->log(self::$ERROR, $exception->getMessage());
        	        $code = $exception->getCode();
                    if(!in_array($code, [500,400,301,302,401,404])) $code = 500;
        	        return $c['response']->withStatus($code)
        	                             ->withHeader('Content-Type', 'application/json')
        	                             ->write( json_encode(['msg' => $exception->getMessage()]));
        	    };
        	};
        	//$c['notFoundHandler'] = $phpErrorHandler;
    	}
        
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
    
    public function addReadme($path='/', $markdownPath='./README.md'){
        $slimAPI = $this;
        $this->app->get($path, function (Request $request, Response $response, array $args) use ($slimAPI, $markdownPath){
            return $response->write($slimAPI->readmeTemplate($markdownPath));
    	});
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
    function bigString($min=1, $max=2000){ 
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