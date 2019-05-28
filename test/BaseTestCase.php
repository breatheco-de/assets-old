<?php

$CURRENT = dirname(__FILE__);
require($CURRENT.'/../globals.php');

use Aws\Ses\SesClient;
use Aws\Ses\Exception\SesException;
use PHPUnit\Framework\TestCase;
use ZendDiagnostics\Check\GuzzleHttpService;
use ZendDiagnostics\Check;
use ZendDiagnostics\Runner\Runner;
use ZendDiagnostics\Runner\Reporter\BasicConsole;
use GlLinkChecker\GlLinkChecker;
use GlLinkChecker\GlLinkCheckerReport;
use Symfony\Component\Finder\Finder;

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

function debug($something){
    print_r($something); die();
}

class BaseTestCase extends TestCase {

    var $routesAdded = false;
    var $runner;
    var $links; //all links being checked for errors in all the unit tests
    var $cache = [];
    var $samples = [];
    var $client = null;
    var $data = [
        'syllabis' => [],
        'replit-templates' => [],
        'quizzes' => [],
        'lessons' => [],
        'projects' => []
    ];
    const ASSETS_API = ASSETS_HOST.'/apis/';

    /**
     * Default preparation for each test
     */
    public function setUp(){
        parent::setUp();

        $this->createVirtualAPI();
        if(!defined('RUNING_TEST')) define('RUNING_TEST',true);

        $this->runner = new Runner();
        $this->runner->addReporter(new BasicConsole(80, true));

        $this->client = new \GuzzleHttp\Client();

        if(!defined('RUNING_TEST')) define('RUNING_TEST',true);
        $this->_loadInitialData();

    }

    private function createVirtualAPI(){
    	$this->app = new \SlimAPI\SlimAPI([
    		'debug' => true,
            'settings' => [
                'authenticate' => false,
                'displayErrorDetails' => true,
                'determineRouteBeforeAppMiddleware' => false,
                'addContentLengthHeader' => false
            ]
    	]);
    }

    /**
     * To add the routs being tested
     */
    public function addRoutes($func){
        $this->routesAdded = true;
        $this->app = $func($this)->app;
    }

    public function mockGET($url){
        $parts = explode('?',$url);

        $params = [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI' => $parts[0]
        ];

        if(!empty($parts[1])) $params['QUERY_STRING'] = $parts[1];

        return $this->mockRequest($params);
    }

    public function mockPOST($url, $bod=null){
        return $this->mockRequest([
            'REQUEST_METHOD' => 'POST',
            'REQUEST_URI' => $url
        ], $body);
    }

    public function mockPUT($url, $bod=null){
        return $this->mockRequest([
            'REQUEST_METHOD' => 'PUT',
            'REQUEST_URI' => $url
        ], $body);
    }

    public function mockDELETE($url, $bod=null){
        return $this->mockRequest([
            'REQUEST_METHOD' => 'DELETE',
            'REQUEST_URI' => $url
        ]);
    }

    /**
       [
            'REQUEST_METHOD' => 'GET',
            'REQUEST_URI'    => '/catalog/countries/',
       ]
     */
    protected function mockRequest($params, $body=null){

        $env = \Slim\Http\Environment::mock($params);

        $req = \Slim\Http\Request::createFromEnvironment($env);
        $bodyStream = $req->getBody();
        $bodyStream->write(json_encode($body));
        $bodyStream->rewind();
        $req = $req->withBody($bodyStream);
        $req = $req->withHeader('Content-Type', 'application/json');

        $this->app->getContainer()["environment"] = $env;
        $this->app->getContainer()["request"] = $req;
        $response = $this->app->run();
        $responseBody = $response->getBody();
        $responseObj = json_decode($responseBody);

        $assertion =  new \SlimAPI\AssertResponse($this, $response, $responseObj);
        //$this->_logTest($params, $response, $responseObj, $assertion);
        return $assertion;
    }

    private function _loadInitialData(){
        $syllabusFiles = $this->getAllFiles('./apis/syllabus/data', '.json');
        $replitFiles = $this->getAllFiles('./apis/replit/_templates', '.json');
        $quizFiles = $this->getAllFiles('./apis/quiz/data', '.json');

        foreach($syllabusFiles as $syllabus){
            $profileSlug = pathinfo($syllabus, PATHINFO_FILENAME);
            $versionNumberPosition = strrpos($profileSlug, ".v");
            if($versionNumberPosition) $profileSlug = substr($profileSlug, 0, $versionNumberPosition); //remove syllabus version

            $this->data['syllabis'][$profileSlug] = json_decode(file_get_contents($syllabus));
            $this->assertSame("Syllabus json for $profileSlug is good", "Syllabus json for $profileSlug is ".($this->data['syllabis'][$profileSlug] ? "good" : "wrong"));
            $this->assertSame($profileSlug, $this->data['syllabis'][$profileSlug]->profile);
        }
        foreach($replitFiles as $template){
            $profileSlug = pathinfo($template, PATHINFO_FILENAME);
            $this->data['replit-templates'][$profileSlug] = (array) json_decode(file_get_contents($template));
        }
        foreach($quizFiles as $quiz){
            $quizSlug = pathinfo($quiz, PATHINFO_FILENAME);
            $re = '/^([a-zA-Z\d-_]+)\.?([a-z]{2})?$/m';
            if(preg_match_all($re, $quizSlug, $matches, PREG_SET_ORDER, 0)){
                if(empty($matches[0][2])) $this->data['quizzes'][$matches[0][1]]["en"] = (array) json_decode(file_get_contents($quiz));
                else  $this->data['quizzes'][$matches[0][1]][$matches[0][2]] = (array) json_decode(file_get_contents($quiz));
            }
            else throw new Exception('There is quiz with a bad name: '.$quizSlug);
        }

        if(empty($this->data['lessons'])) $this->data['lessons'] = $this->get(self::ASSETS_API.'lesson/all/v2');

        $projects = $this->get('https://projects.breatheco.de/json');
        foreach($projects as $p){
            if(!isset($p->slug)) debug($p);
            $this->data['projects'][$p->slug] = $p;
        }
    }

    /**
     * End of the tests
     */
    public function tearDown(){
        // Run all checks
        $results = $this->runner->run();

        //check for all ZendDiagnostics tests
        $status = ($results->getFailureCount() + $results->getWarningCount()) > 0 ? 1 : 0;
        $this->assertSame($status, 0);

    }

    public function checkEndpoint($method,$url,$body=null){
        return new HTTPChecker($method, $url, $body);
    }

    public function checkURLAndContent($url, $string){
        $this->runner->addCheck(new GuzzleHttpService(
            $url,
            array(),
            array(),
            200,
            $string
        ));
    }

    public function get($url){
        $content = file_get_contents($url);
        if(!$content) throw new Exception('Invalid file url: '.$url);

        $obj = json_decode($content);
        if(!$obj) throw new Exception('Invalid sample syntax');
        if(empty($this->cache[$url])) $this->cache[$url] = $obj;

        return $this->cache[$url];
    }

    public function getSample($slug){
        if(!empty($this->samples[$slug])) return $this->samples[$slug];
        else{
            $url = ASSETS_HOST.'/apis/hook/sample/'.$slug;
            $content = file_get_contents($url);
            if(!$content) throw new Exception('Invalid Sample URL: '.$url);

            $obj = json_decode($content);
            if(!$obj) throw new Exception('Invalid sample syntax for sample/'.$slug.' '.$content);
            if(empty($samples[$slug])) $this->samples[$slug] = $obj;

            return $this->samples[$slug];
        }
    }

    private function sendError($to,$subject,$message){

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
                'secret' => S3_SECRET,
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

    public function checkLinksOnFiles($pathToFolder, $extentions){
        $linkChecker  = new GlLinkChecker('');

        //construct list of local html and json files to check
        $finder = new Finder();
        $links = [];
        foreach($extentions as $ext){
            $scanedFiles = $finder->files()->in($pathToFolder)->name($ext);
            $links = array_merge($links, $linkChecker->checkFiles($scanedFiles,
                function ($nbr) { /* called at beginning - $nbr urls to check */ },
                function ($url, $files) { /* called each $url - $files : list of filename containing $url link */ },
                function () { /* called at the end */ },
                ['absolute']
            ));
        }


        return $this->_getErrorsFromLinks($links, false);
    }

    private function _getErrorsFromLinks($links, $silent = true){
        /**
         * @var GlLinkCheckerError $link
         */
         $errorsFound = 0;
         $linkCount = count($links);
        foreach ($links as $link) {
            $errors = $link->getErrorMessages();
            if (count($errors) <= 0) continue;
            else $errorsFound += count($errors);

            if(!$silent){
                $url    = $link->getLink();
                $files  = implode(", ", $link->getFiles());

                echo "\033[31m Link:".$url . " \033[0m \033[33m->\033[0m " . implode(',', $errors). " \033[33min\033[0m ".$files."\n";
            }
        }

        if($errorsFound > 0){
            echo "\033[31m".$errorsFound." errors found. \033[0m";
            return 1;
        }
        else{
            echo "\n \033[32m Success! ".$errorsFound." errors found in ".$linkCount." links. \033[0m \n";
            return 0;
        }
    }

    function log($msg){
        if(DEBUG){
            echo "\033[33m";
            print_r($msg);
            echo "\033[0m";
        }
    }
    function _logTest($params, $response, $responseObj, $assertion=null){
        if(DEBUG){
            $code = $response->getStatusCode();
            $expected = (!$assertion) ? '' : $assertion->getExpectedRespCode();
            if($code != 200 && $code != 400 && $code != 404){
                if(!empty($responseObj)){
                    $logEntry = "\n \n [ \n".
                    "   [code]     => \033[33m".$responseObj->code."\033[0m \n".
                    "   [msg]      => \033[31m".$responseObj->msg."\033[0m \n".
                    "]\n \n";
                    echo "\033[31m \n ****    FOUND SOME MISMATCHES:    **** \n \033[0m";
                    print_r($logEntry);
                }
                else {
                    echo "\033[31m \n ****    FOUND SOME MISMATCHES:    **** \n \033[0m";
                    echo "   [request]  => \033[36m".$params['REQUEST_METHOD'].": ".$params['REQUEST_URI']."\033[0m \n";
                    echo "   [details]  => \033[33m No details or response was provided \033[0m \n \n";
                }
            }
        }
    }

    public function getAllFiles($path, $extension) {
        $files = [];
        if(is_dir($path)){
            $_upcomingFiles = scandir($path);
            foreach($_upcomingFiles as $newFile){
                if($newFile != '.' and $newFile != '..' and substr( $newFile, 0, 1 ) != '.' and is_dir($path.'/'.$newFile)){
                    $files = array_merge($files, $this->getAllFiles($path.'/'.$newFile, $extension));
                }
                else if(strpos($newFile, $extension) !== false)
                    $files[] = $path.'/'.$newFile;
            }
        }

        return $files;
    }
}