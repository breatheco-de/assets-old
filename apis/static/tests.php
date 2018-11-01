<?php
require('api/config.php');

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
use \BreatheCodeAPI;

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
    
    protected function assertContainsProperties($obj, $properties){
        
        foreach($properties as $prop) if(!property_exists($obj, $prop)) return false;
        
        return true;
    }
}

class CatalogTest extends BaseTestCase
{
    protected $app;
    
    public function setUp(){
        parent::setUp();
        $this->app->addRoutes(['catalog']);
    }
    
    public function testForCountry() {
        
        $responseObj = $this->mockAPICall(['REQUEST_METHOD' => 'GET','REQUEST_URI' => '/catalog/countries/']);
        $this->assertSame($responseObj->data->chile, ['Santiago']);
    } 
}