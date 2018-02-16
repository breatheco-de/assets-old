<!DOCTYPE html>
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="content-type">

        <title>SlickQuiz Demo</title>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="css/reset.css" media="screen" rel="stylesheet" type="text/css">
        <link href="css/slickQuiz.css" media="screen" rel="stylesheet" type="text/css">
        <link href="css/master.css?v4" media="screen" rel="stylesheet" type="text/css">
    </head>

    <body id="slickQuiz">
        <h1 class="quizName"><!-- where the quiz name goes --></h1>

        <div class="quizArea">
            <div class="quizHeader">
                <!-- where the quiz main copy goes -->

                <a class="button btn-primary startQuiz" href="#">Get Started!</a>
            </div>

            <!-- where the quiz gets built -->
        </div>

        <div class="quizResults">
            <h3 class="quizScore">You Scored: <span><!-- where the quiz score goes --></span></h3>

            <h3 class="quizLevel"><strong>Ranking:</strong> <span><!-- where the quiz ranking level goes --></span></h3>

            <div class="quizResultsCopy">
                <!-- where the quiz result copy goes -->
            </div>
        </div>

        <script src="js/jquery.js"></script>
        <script src="js/slickQuiz.js?v4"></script>
        <script type="text/javascript">
            $(function() {
                
                $.ajax({
                   url: '../../apis/quiz_api/quiz/<?php echo $_GET['slug']; ?>', 
                   dataType: 'json',
                   cache: false,
                   success: function(quizz){
                       if(quizz && typeof(quizz.info) != 'undefined')
                       {
                           quizz.questions = cleanOptions(quizz.questions);
                            $('#slickQuiz').slickQuiz({
                                json: quizz,
                                onComplete: sendFinishActivity,
                                randomSortAnswers: true,
                                disableResponseMessaging: false,
                                inlineAnswers: true,
                                hideQuestion: true,
                                inputType: 'button',
                                attempts: 1,
                                onStart: sendStartActivity
                            });
                       }
                   },
                   error: function(p1,p2,p3){
                       console.log("Error: "+p3);
                   }
                });
                
                function sendFinishActivity(passed, total){
                    //console.log('You passed '+passed+' from '+total);
                    window.parent.postMessage({ passedQuestions: passed, totalQuestions: total }, '*'); 
                }
                
                function sendStartActivity(){
                    //console.log('You passed '+passed+' from '+total);
                    window.parent.postMessage({ started: true }, '*'); 
                }
                
                function cleanOptions(questions){
                    if(questions) questions.forEach(function(questionObj){
                        
                        questionObj.q = transofrmEntities(questionObj.q);
                        
                        questionObj.a.forEach(function(answer){
                            answer.option = transofrmEntities(answer.option);
                            return answer;
                        });
                        
                        return questionObj;
                    });
                    
                    return questions;
                    
                }
                
                function transofrmEntities(strInput){
                    var result = strInput.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
                        return '&#'+i.charCodeAt(0)+';';
                    });
                    
                    return result;
                }
                
            });
        </script>
    </body>
</html>
