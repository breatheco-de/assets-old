<?php
/*
* @title: Every cohort must have a replit
*/

$CURRENT = dirname(__FILE__);
require_once($CURRENT.'/BaseTestCase.php');

class QuizzesTest extends BaseTestCase {

    public function setUp()
    {
        parent::setUp();
    }

    // function testRepeatedQuizzes(){
    //     foreach($this->data['quizzes'] as $slug => $quiz){
    //         $this->assertSame("El quiz $slug SI tiene idioma en",$this->tieneIdioma($slug, 'en'));
    //         $this->assertSame("El quiz $slug SI tiene idioma es",$this->tieneIdioma($slug, 'es'));
    //     }
    //     print_r($this->data['quizzes']);
    // }
    
    function tieneIdioma($slug,$lang){
        if(empty($this->data['quizzes'][$slug])) return "El quiz $slug NO existe";
        if(empty($this->data['quizzes'][$slug][$lang])) return "El quiz $slug NO tiene idioma $lang";
        else return "El quiz $slug SI tiene idioma $lang";
        
    }

}