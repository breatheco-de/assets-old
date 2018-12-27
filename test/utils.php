<?php

    use ZendDiagnostics\Check\GuzzleHttpService;
    function checkURL($url,$string = null){
        return new GuzzleHttpService(
            $url,
            array(),
            array(),
            200,
            $string
        );
    }

    class HTTPChecker{
        private $p = [];
        function __construct($method,$url,$body){
            $this->p = [
                $url,
                array(),
                array(),
                200,
                null,
                null,
                $method,//POST
                $body//array
            ];
        }
        function assertSuccess(){
            if($this->p[6] == 'POST')
            {
                $this->p[1] = ['Content-Type' => 'application/json'];
                $this->p[2] = ['json' => $this->p[7]];
                
            }
            return new GuzzleHttpService($this->p[0],$this->p[1],$this->p[2],200,$this->p[4],$this->p[5],$this->p[6],null);
        }
        function assertFail(){
            if($this->p[6] == 'POST'){
                $this->p[1] = ['Content-Type' => 'application/json'];
                $this->p[2] = ['json' => $this->p[7]];
            } 
            return new GuzzleHttpService($this->p[0],$this->p[1],$this->p[2],500,$this->p[4],$this->p[5],$this->p[6],null);
        }
    }
    function checkEndpoint($method,$url,$body=null){
        return new HTTPChecker($method, $url, $body);
    }
    
    $samples=[];
    function getSample($slug){
        if(!empty($samples[$slug])) return $samples[$slug];
        else{
            $url = ASSETS_HOST.'/apis/hook/sample/'.$slug;
            $content = file_get_contents($url);
            if(!$content) throw new Exception('Invalid Sample URL: '.$url);
            
            $obj = json_decode($content);
            if(!$obj) throw new Exception('Invalid sample syntax for sample/'.$slug.' '.$content);
            if(empty($samples[$slug])) $samples[$slug] = $obj;
            
            return $samples[$slug];
        }
    }
    function get($url){
        $content = file_get_contents($url);
        if(!$content) throw new Exception('Invalid file url: '.$url);
        
        $obj = json_decode($content);
        if(!$obj) throw new Exception('Invalid sample syntax');
        if(empty($samples[$url])) $samples[$url] = $obj;
        
        return $samples[$url];
    }
    
    use Aws\Ses\SesClient;
    use Aws\Ses\Exception\SesException;
    function sendError($to,$subject,$message){
        
        if(!is_array($to)) $to = [$to];
        
        $ABS_PATH = dirname(__FILE__).'/';
        $loader = new \Twig_Loader_Filesystem($ABS_PATH);
        $twig = new \Twig_Environment($loader);
        
        $template = $twig->load('error_template.html');
        
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
                'ToAddresses' => $to,
            ],
            'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => 'UTF-8',
                        'Data' => $template->render(['subject' => 'Error! '.$subject, 'message' => $message]),
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
    

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
class BaseTestCase extends TestCase {
 
    /**
     * Default preparation for each test
     */
    public function setUp()
    {
        parent::setUp();
 
    	$this->app = new SlimAPI([
    		'debug' => API_DEBUG
    	]);
    }
 
    /**
       [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/catalog/countries/',
       ]
     */
    protected function mockAPICall($params, $body=null){
        $env = Environment::mock($params);
        
        $req = Request::createFromEnvironment($env);
        $bodyStream = $req->getBody();
        $bodyStream->write(json_encode($body));
        $bodyStream->rewind();
        $req = $req->withBody($bodyStream);
        $req = $req->withHeader('Content-Type', 'application/json');
        
        $this->app->getContainer()["environment"] = $env;
        $this->app->getContainer()["request"] =$req;
        $response = $this->app->run(true);
        
        $responseBody = $response->getBody();
        $responseObj = json_decode($responseBody);
        
        $this->assertSame($response->getStatusCode(), 200);
        $this->assertSame($responseObj->code, 200);
        
        return $responseObj;
    }
    
}