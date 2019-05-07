<?php
/*
* @title: Every cohort must have a replit
*/
$CURRENT = dirname(__FILE__);
require_once($CURRENT.'/BaseTestCase.php');

class SyllabusTest extends BaseTestCase {

    public function setUp()
    {
        parent::setUp();

    }

    function testAllSyllabis(){
        foreach($this->data['syllabis'] as $syllabus){
            foreach($syllabus->weeks as $week){
                foreach($week->days as $day){
                    if(isset($day->replits))
                        foreach($day->replits as $replit){
                            $this->assertSame($this->_replitExists($syllabus->profile, $replit->slug), $syllabus->profile.' => replit:'.$replit->slug);
                        }
                    if(isset($day->lessons))
                        foreach($day->lessons as $lesson){
                            $this->assertSame($this->_lessonExists($syllabus->profile, $lesson->slug), $syllabus->profile.' => lesson:'.$lesson->slug);
                        }
                    if(isset($day->quizzes))
                        foreach($day->quizzes as $quiz){
                            $this->assertSame($this->_quizExists($syllabus->profile, $quiz->slug), $syllabus->profile.' => quiz:'.$quiz->slug);
                        }
                    if(isset($day->assignments))
                        foreach($day->assignments as $aSlug){
                            $this->assertSame($this->_assignmentExists($syllabus->profile, $aSlug), $syllabus->profile.' => assignment:'.$aSlug);
                        }
                }
            }
        }
    }

    function testAllSyllabusLinks(){
        $errors = $this->checkLinksOnFiles('./apis/syllabus/data', ["*.json", "*.md"]);
        $this->assertSame($errors, 0);
    }

    private function _lessonExists($profileSlug, $lessonSlug){
        foreach($this->data['lessons'] as $l)
            if($l->slug == $lessonSlug) return $profileSlug.' => lesson:'.$lessonSlug;

        return "lesson $lessonSlug does not exists";
    }
    private function _quizExists($profileSlug, $quizSlug){
        foreach($this->data['quizzes'] as $key => $q){
            if(!isset($q["en"])) return "no english language for for ".$key;
            if($key == $quizSlug and $q["en"]['info']->slug == $quizSlug) return $profileSlug.' => quiz:'.$quizSlug;
        }
        return "quiz $quizSlug does not exist";
    }
    private function _replitExists($profileSlug, $replitSlug){
        foreach($this->data['replit-templates'][$profileSlug] as $replit)
            if($replit->slug == $replitSlug) return $profileSlug.' => replit:'.$replitSlug;

        return false;
    }
    private function _assignmentExists($profileSlug, $assignmentSlug){
        if(isset($this->data['projects'][$assignmentSlug])) return $profileSlug.' => assignment:'.$assignmentSlug;
        else return "assignment $assignmentSlug does not exists";
    }

}