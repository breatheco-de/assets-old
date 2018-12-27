<?php
require('../config.php');

use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;

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