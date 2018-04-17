<?php
use PHPUnit\Framework\TestCase;

class StackTest extends TestCase
{
    public function testPushAndPop()
    {
        $headers = [];
        $headers['content-type'] = 'application/json';
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://0.0.0.0/',
        	'header' => $headers
        ]);
    
        $req = $client->get('apis/replit/cohort/');
        $response = $req->send();
        
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->hasHeader('Location'));
        $data = json_decode($response->getBody(true), true);
        $this->assertArrayHasKey('nickname', $data);
    }
}