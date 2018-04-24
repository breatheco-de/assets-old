<?php
use PHPUnit\Framework\TestCase;
use ZendDiagnostics\Check\HttpService;

class FirstTest extends TestCase
{
    public function testPushAndPop()
    {
        // Try to connect to google.com
        $checkGoogle = new HttpService('www.google.com');
        
        // Check port 8080 on localhost
        $checkLocal = new HttpService('127.0.0.1', 8080);
        
        // Check that the page exists (response code must equal 200)
        $checkPage = new HttpService('www.example.com', 80, '/some/page.html', 200);
        
        // Check page content
        $checkPageContent = new HttpService(
            'www.example.com',
            80,
            '/some/page.html',
            200,
            '<title>Hello World</title>'
        );
        print_r($checkPageContent); die();
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('nickname', $data);
    }
}