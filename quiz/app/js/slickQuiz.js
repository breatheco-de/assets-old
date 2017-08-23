/*!
 * SlickQuiz jQuery Plugin
 * http://github.com/QuickenLoans/SlickQuiz
 *
 * @updated September 23, 2013
 *
 * @author Julie Cameron - http://www.juliecameron.com
 * @copyright (c) 2013 Quicken Loans - http://www.quickenloans.com
 * @license MIT
 */

(function($){
    $.slickQuiz = function(element, options) {
        var plugin   = this,
            $element = $(element),
            _element = '#' + $element.attr('id'),
            _totalAnswers = [],
            _categoryPoints = [];
            _messages = {
                correct: ['That\'s right!'],
                incorrect: ['Uhh no.', 'Nop, you are wrong dude.']
            },
            _levels = [ 
                "You are ready",
                "You are a contender",
                "You are an amateur",
                "You know nothing :(",
                "Stay in school, kid..."
            ],
            defaults = {
                checkAnswerText:  'Check My Answer!',
                nextQuestionText: 'Next &raquo;',
                backButtonText: '',
                inputType: 'radio',
                tryAgainText: '',
                skipStartButton: false,
                numberOfQuestions: null,
                allowHTMLContent: false,
                hideQuestion: false,
                randomSort: false,
                inlineAnswers: false,
                displayQuestionNumber: false,
                randomSortQuestions: false,
                randomSortAnswers: false,
                preventUnanswered: false,
                completionResponseMessaging: false,
                disableResponseMessaging: false,
                onComplete: null,
                onStart: null,
                attempts: 1
            },

            // Class Name Strings (Used for building quiz and for selectors)
            questionCountClass     = 'questionCount',
            questionGroupClass     = 'questions',
            questionClass          = 'question',
            answersClass           = 'answers',
            responsesClass         = 'responses',
            correctClass           = 'correctResponse',
            correctResponseClass   = 'correct',
            incorrectResponseClass = 'incorrect',
            nextQuestionClass      = 'nextQuestion',
            backToQuestionClass    = 'backToQuestion',
            tryAgainClass          = 'tryAgain',

            // Sub-Quiz / Sub-Question Class Selectors
            _questionCount         = '.' + questionCountClass,
            _questions             = '.' + questionGroupClass,
            _question              = '.' + questionClass,
            _answers               = '.' + answersClass,
            _responses             = '.' + responsesClass,
            _correct               = '.' + correctClass,
            _correctResponse       = '.' + correctResponseClass,
            _incorrectResponse     = '.' + incorrectResponseClass,
            _nextQuestionBtn       = '.' + nextQuestionClass,
            _prevQuestionBtn       = '.' + backToQuestionClass,
            _tryAgainBtn           = '.' + tryAgainClass,

            // Top Level Quiz Element Class Selectors
            _quizStarter           = _element + ' .startQuiz',
            _quizName              = _element + ' .quizName',
            _quizArea              = _element + ' .quizArea',
            _quizResults           = _element + ' .quizResults',
            _quizResultsCopy       = _element + ' .quizResultsCopy',
            _quizHeader            = _element + ' .quizHeader',
            _quizScore             = _element + ' .quizScore',
            _quizLevel             = _element + ' .quizLevel',

            // Top Level Quiz Element Objects
            $quizStarter           = $(_quizStarter),
            $quizName              = $(_quizName),
            $quizArea              = $(_quizArea),
            $quizResults           = $(_quizResults),
            $quizResultsCopy       = $(_quizResultsCopy),
            $quizHeader            = $(_quizHeader),
            $quizScore             = $(_quizScore),
            $quizLevel             = $(_quizLevel)
        ;


        // Reassign user-submitted deprecated options
        var depMsg = '';

        if (options && typeof options.disableNext != 'undefined') {
            if (typeof options.preventUnanswered == 'undefined') {
                options.preventUnanswered = options.disableNext;
            }
            depMsg += 'The \'disableNext\' option has been deprecated, please use \'preventUnanswered\' in it\'s place.\n\n';
        }

        if (depMsg !== '') {
            if (typeof console != 'undefined') {
                console.warn(depMsg);
            } else {
                alert(depMsg);
            }
        }
        // End of deprecation reassignment


        plugin.config = $.extend(defaults, options);

        // Set via json option or quizJSON variable (see slickQuiz-config.js)
        var quizValues = (plugin.config.json ? plugin.config.json : typeof quizJSON != 'undefined' ? quizJSON : null);

        // Get questions, possibly sorted randomly
        var questions = plugin.config.randomSort || plugin.config.randomSortQuestions ?
                        quizValues.questions.sort(function() { return (Math.round(Math.random())-0.5); }) :
                        quizValues.questions;

        // Count the number of questions
        var questionCount = questions.length;

        // Select X number of questions to load if options is set
        if (plugin.config.numberOfQuestions && questionCount >= plugin.config.numberOfQuestions) {
            questions = questions.slice(0, plugin.config.numberOfQuestions);
            questionCount = questions.length;
        }
        
        //Apply the inline class if the option is set
        if(plugin.config.inlineAnswers) answersClass += ' inline-answers';
        
        if(typeof quizValues.info.type != 'undefined'){
            if(quizValues.info.type=='diagnostic' && (typeof quizValues.info.categories == 'undefined' || quizValues.info.categories.length==0))
                throw 'slickQuiz Error: You need to specify the cagetories for the Quizz diagnostic';
            else{
                quizValues.info.categories.forEach(function(cat){
                    _categoryPoints[cat.toLowerCase()] = 0;
                });
            }
        }else quizValues.info.type = 'quiz';

        plugin.method = {
            // Sets up the questions and answers based on above array
            setupQuiz: function() {
                if(!plugin.config.hideQuestion) $quizName.hide().html(quizValues.info.name).fadeIn(1000);
                $quizHeader.hide().prepend(quizValues.info.main).fadeIn(1000);
                $quizResultsCopy.append(quizValues.info.results);

                // add retry button to results view, if enabled
                if (plugin.config.tryAgainText && plugin.config.tryAgainText !== '') {
                    $quizResultsCopy.before('<a class="button ' + tryAgainClass + '" href="#">' + plugin.config.tryAgainText + '</a>');
                }

                // Setup questions
                var quiz  = $('<ol class="' + questionGroupClass + '"></ol>'),
                    count = 1;
                // Loop through questions object
                for (i in questions) {
                    if (questions.hasOwnProperty(i)) {
                        var question = questions[i];

                        
                        var questionHTML = $('<li class="' + questionClass +'" id="question' + (count - 1) + '" data-id="' + (count - 1) + '"></li>');
                        questionHTML.append('<div class="' + questionCountClass + '">Question <span class="current">' + count + '</span> of <span class="total">' + questionCount + '</span></div>');
                        
                        let displayNumber = '';
                        if(plugin.config.displayQuestionNumber) displayNumber = count + '. ';
                        questionHTML.append('<h3>' + displayNumber + plugin.method.transformEntities(question.q) + '</h3>');

                        // Count the number of true values
                        var truths = 0;
                        for (i in question.a) {
                            if (question.a.hasOwnProperty(i)) {
                                answer = question.a[i];
                                if (answer.correct) {
                                    truths++;
                                }
                            }
                        }

                        // Now let's append the answers with checkboxes or radios depending on truth count
                        var answerHTML = $('<ul class="' + answersClass + '"></ul>');

                        // Get the answers
                        var answers = plugin.config.randomSort || plugin.config.randomSortAnswers ?
                            question.a.sort(function() { return (Math.round(Math.random())-0.5); }) :
                            question.a;

                        // prepare a name for the answer inputs based on the question
                        var selectAny  = question.select_any ? question.select_any : false,
                            inputName  = 'question' + (count - 1),
                            inputType  = (truths > 1 && !selectAny ? 'checkbox' : plugin.config.inputType);

                        for (i in answers) {
                            if (answers.hasOwnProperty(i)) {
                                answer   = answers[i];
                                optionId = i.toString();

                                let answerContent = plugin.method.renderAnswer({
                                    answer: answer,
                                    optionId: optionId,
                                    inputType: inputType,
                                    inputName: i.toString(),
                                })
                                answerHTML.append(answerContent);
                            }
                        }

                        // Append answers to question
                        questionHTML.append(answerHTML);

                        // If response messaging is NOT disabled, add it
                        if (!plugin.config.disableResponseMessaging) {
                            // Now let's append the correct / incorrect response messages
                            var responseHTML = $('<ul class="' + responsesClass + '"></ul>');
                            if(question.correct) responseHTML.append('<li class="' + correctResponseClass + '">' + question.correct + '</li>');
                            else responseHTML.append('<li class="' + correctResponseClass + '"><p><span>' + _messages.correct[Math.floor(Math.random()*_messages.correct.length)] + '</p></span></li>');
                            
                            if(question.incorrect) responseHTML.append('<li class="' + incorrectResponseClass + '">' + question.incorrect + '</li>');
                            else responseHTML.append('<li class="' + incorrectResponseClass + '"><p><span>' + _messages.incorrect[Math.floor(Math.random()*_messages.incorrect.length)] + '</p></span></li>');
                            // Append responses to question
                            questionHTML.append(responseHTML);
                        }

                        // Appends check answer / back / next question buttons
                        if (plugin.config.backButtonText && plugin.config.backButtonText !== '') {
                            questionHTML.append('<a href="#" class="button ' + backToQuestionClass + '">' + plugin.config.backButtonText + '</a>');
                        }

                        // If response messaging is disabled or hidden until the quiz is completed,
                        // make the nextQuestion button the checkAnswer button, as well
                        if (plugin.config.inputType!='button') {
                            questionHTML.append('<a href="#" class="button ' + nextQuestionClass + '">' + plugin.config.nextQuestionText + '</a>');
                        }

                        // Append question & answers to quiz
                        quiz.append(questionHTML);

                        count++;
                    }
                }

                // Add the quiz content to the page
                $quizArea.append(quiz);
                
                if(plugin.config.inputType=='button')
                {
                    // Bind "next" buttons
                    $('.answer-option').on('click', function(e) {
                        e.preventDefault();
                        
                        var currentQuestion = $($(this).parents(_question)[0]);
                        _totalAnswers.push({
                            id: currentQuestion.attr('data-id'),
                            answers: [this.getAttribute('data-id')]
                        })
                        
                        plugin.method.nextQuestion(this);
                    });
                }

                // Toggle the start button OR start the quiz if start button is disabled
                if (plugin.config.skipStartButton || $quizStarter.length == 0) {
                    $quizStarter.hide();
                    $quizHeader.hide();
                    plugin.method.startQuiz(this);
                } else {
                    $quizStarter.fadeIn(500);
                    $quizHeader.fadeIn(500);
                }
            },
            
            renderAnswer: function(answer){

                var answerContent = $('<li></li>')
                    .append(plugin.method.getQuestionHTML(answer));
                return answerContent;
            },
            
            getQuestionHTML: function(answer){
              switch (answer.inputType) {
                  case 'button':
                      return '<button class="button answer-option" id="option' + answer.optionId + '"  data-id="' + answer.optionId +
                                '">' + plugin.method.transformEntities(answer.answer.option) + '</button>';
                      // code
                      break;
                  default:
                        return '<input id="' + answer.optionId + '" name="' + answer.inputName +
                                '" type="' + answer.inputType + '" />' + 
                                 '<label for="' + answer.optionId + '">' + plugin.method.transformEntities(answer.answer.option) + '</label>';
                      break;
              }  
            },
            
            transformEntities: function(strInput){
                
                if(plugin.config.allowHTMLContent)
                {
                    strInput = strInput.replace(/[\u00A0-\u9999<>\&]/gim, function(i) {
                        return '&#'+i.charCodeAt(0)+';';
                    });
                }
                
                return strInput;
            },

            // Starts the quiz (hides start button and displays first question)
            startQuiz: function() {
                function start() {
                    var firstQuestion = $(_element + ' ' + _questions + ' li').first();
                    if (firstQuestion.length) {
                        firstQuestion.fadeIn(500);
                    }
                }

                if (plugin.config.skipStartButton || $quizStarter.length == 0) {
                    start();
                } else {
                    $quizStarter.hide();
                    $quizHeader.fadeOut(300, function(){
                        start();
                    });
                }
            },

            // Resets (restarts) the quiz (hides results, resets inputs, and displays first question)
            resetQuiz: function(startButton) {
                $quizResults.fadeOut(300, function() {
                    $(_element + ' input').prop('checked', false).prop('disabled', false);

                    $quizLevel.attr('class', 'quizLevel');
                    $(_element + ' ' + _correct).removeClass(correctClass);

                    $(_element + ' ' + _question          + ',' +
                      _element + ' ' + _responses         + ',' +
                      _element + ' ' + _correctResponse   + ',' +
                      _element + ' ' + _incorrectResponse + ',' +
                      _element + ' ' + _nextQuestionBtn   + ',' +
                      _element + ' ' + _prevQuestionBtn
                    ).hide();

                    $(_element + ' ' + _questionCount + ',' +
                      _element + ' ' + _answers
                    ).show();

                    $quizArea.append($(_element + ' ' + _questions)).show();

                    plugin.method.startQuiz($quizResults);
                });
            },

            // Validates the response selection(s), displays explanations & next question button
            checkAnswer: function(checkButton) {
                var questionLI    = $($(checkButton).parents(_question)[0]),
                    questionIndex = parseInt(questionLI.attr('id').replace(/(question)/, ''), 10),
                    answers       = questions[questionIndex].a,
                    selectAny     = questions[questionIndex].select_any ? questions[questionIndex].select_any : false;

                // Collect the true answers needed for a correct response
                var trueAnswers = [];
                for (i in answers) {
                    if (answers.hasOwnProperty(i)) {
                        var answer = answers[i];

                        if (answer.correct) {
                            trueAnswers.push($('<div />').html(answer.option).text());
                        }
                    }
                }

                // NOTE: Collecting .text() for comparison aims to ensure that HTML entities
                // and HTML elements that may be modified by the browser match up

                // Collect the answers submitted
                var answerInputs  = questionLI.find('input:checked');
                var selectedAnswers = [];
                answerInputs.each( function() {
                    var inputValue = $(this).next('label').text();
                    selectedAnswers.push(inputValue);
                });

                if (plugin.config.preventUnanswered && selectedAnswers.length === 0) {
                    alert('You must select at least one answer.');
                    return false;
                }

                // Verify all/any true answers (and no false ones) were submitted
                var correctResponse = false;
                if(plugin.config.inputType!='button') correctResponse = plugin.method.compareAnswers(trueAnswers, selectedAnswers, selectAny);
                else correctResponse = plugin.method.isTheRightAnswer(questionLI.attr('data-id'),$(checkButton).attr('data-id'));

                if (correctResponse) {
                    questionLI.addClass(correctClass);
                }

                // If response messaging hasn't been disabled, toggle the proper response
                if (!plugin.config.disableResponseMessaging) {
                    // If response messaging hasn't been set to display upon quiz completion, show it now
                    if (!plugin.config.completionResponseMessaging) {
                        questionLI.find(_answers).hide();
                        questionLI.find(_responses).show();

                        $(checkButton).hide();
                        questionLI.find(_nextQuestionBtn).fadeIn(300);
                        questionLI.find(_prevQuestionBtn).fadeIn(300);
                    }

                    // Toggle responses based on submission
                    questionLI.find(correctResponse ? _correctResponse : _incorrectResponse).fadeIn(300);
                    
                }
            },

            // Moves to the next question OR completes the quiz if on last question
            nextQuestion: function(nextButton) {
                var currentQuestion = $($(nextButton).parents(_question)[0]),
                    nextQuestion    = currentQuestion.next(_question);
                    
                    if(quizValues.info.type=='diagnostic') plugin.method.saveAnswerInCategory(nextButton);
                    
                    if(plugin.config.attempts<=1 && plugin.config.onStart){
                        plugin.config.onStart();
                    }else plugin.config.attempts--;
                    
                    if(!plugin.config.disableResponseMessaging)
                    {
                        plugin.method.checkAnswer(nextButton);
                        setTimeout(plugin.method.moveToNexQuestion(currentQuestion,nextQuestion), 3000);
                    }
                    else plugin.method.moveToNexQuestion(currentQuestion,nextQuestion);
                
            },
            
            saveAnswerInCategory: function(nextButton){
                var currentQuestion = $($(nextButton).parents(_question)[0]);
                
                var answerInputs    = currentQuestion.find('input:checked');
                if(plugin.config.inputType=='button')
                {
                    var questionId = currentQuestion.attr("data-id");
                    var optionId = $(nextButton).attr("data-id");
                    _categoryPoints[questions[questionId].a[optionId].category] += 1;
                }
            },
            
            moveToNexQuestion: function(currentQuestion,nextQuestion){
                // If response messaging has been disabled or moved to completion,
                // make sure we have an answer if we require it, let checkAnswer handle the alert messaging
                var answerInputs    = currentQuestion.find('input:checked');
                if (plugin.config.inputType!='button' && plugin.config.preventUnanswered && answerInputs.length === 0) {
                    return false;
                }
                
                if (nextQuestion.length) {
                    currentQuestion.fadeOut(1000, function(){
                        nextQuestion.find(_prevQuestionBtn).show().end().fadeIn(500);
                    });
                } else {
                    plugin.method.completeQuiz();
                }
            },

            // Go back to the last question
            backToQuestion: function(backButton) {
                var questionLI = $($(backButton).parents(_question)[0]),
                    answers    = questionLI.find(_answers);

                // Back to previous question
                if (answers.css('display') === 'block' ) {
                    var prevQuestion = questionLI.prev(_question);

                    questionLI.fadeOut(300, function() {
                        prevQuestion.removeClass(correctClass);
                        prevQuestion.find(_responses + ', ' + _responses + ' li').hide();
                        prevQuestion.find(_answers).show();

                        // If response messaging hasn't been disabled or moved to completion, hide the next question button
                        // If it has been, we need nextQuestion visible so the user can move forward (there is no separate checkAnswer button)
                        if (!plugin.config.disableResponseMessaging && !plugin.config.completionResponseMessaging) {
                            prevQuestion.find(_nextQuestionBtn).hide();
                        }

                        if (prevQuestion.attr('id') != 'question0') {
                            prevQuestion.find(_prevQuestionBtn).show();
                        } else {
                            prevQuestion.find(_prevQuestionBtn).hide();
                        }

                        prevQuestion.fadeIn(500);
                    });

                // Back to question from responses
                } else {
                    questionLI.find(_responses).fadeOut(300, function(){
                        questionLI.removeClass(correctClass);
                        questionLI.find(_responses + ' li').hide();
                        answers.fadeIn(500);
                        questionLI.find(_nextQuestionBtn).hide();

                        // if question is first, don't show back button on question
                        if (questionLI.attr('id') != 'question0') {
                            questionLI.find(_prevQuestionBtn).show();
                        } else {
                            questionLI.find(_prevQuestionBtn).hide();
                        }
                    });
                }
            },

            // Hides all questions, displays the final score and some conclusive information
            completeQuiz: function() {
                var levels    = [
                                    quizValues.info.level1 ? quizValues.info.level1 : _levels[0], // 80-100%
                                    quizValues.info.level2 ? quizValues.info.level2 : _levels[1], // 60-79%
                                    quizValues.info.level3 ? quizValues.info.level3 : _levels[2], // 40-59%
                                    quizValues.info.level4 ? quizValues.info.level4 : _levels[3], // 20-39%
                                    quizValues.info.level5 ? quizValues.info.level5 : _levels[4]  // 0-19%
                                ],
                    score     = $(_element + ' ' + _correct).length,
                    levelRank = plugin.method.calculateLevel(score),
                    levelText = $.isNumeric(levelRank) ? levels[levelRank] : '';

                if(plugin.config.onComplete) plugin.method.sendResults(score,questionCount);
                    
                $(_quizScore + ' span').html(score + ' / ' + questionCount);
                $(_quizLevel + ' span').html(levelText);
                $(_quizLevel).addClass('level' + levelRank);

                $quizArea.fadeOut(300, function() {
                    // If response messaging is set to show upon quiz completion, show it
                    if (plugin.config.completionResponseMessaging && !plugin.config.disableResponseMessaging) {
                        $(_element + ' input').prop('disabled', true);
                        $(_element + ' .button:not(' + _tryAgainBtn + '), ' + _element + ' ' + _questionCount).hide();
                        $(_element + ' ' + _question + ', ' + _element + ' ' + _responses).show();
                        $quizResults.append($(_element + ' ' + _questions)).fadeIn(500);
                    } else {
                        $quizResults.fadeIn(500);
                    }
                });
            },
            
            sendResults: function(score,questionCount){
                if(quizValues.info.type=='diagnostic') plugin.config.onComplete(_categoryPoints);
                else plugin.config.onComplete(score,questionCount);
            },

            // Compares selected responses with true answers, returns true if they match exactly
            compareAnswers: function(trueAnswers, selectedAnswers, selectAny) {
                if ( selectAny ) {
                    return $.inArray(selectedAnswers[0], trueAnswers) > -1;
                } else {
                    // crafty array comparison (http://stackoverflow.com/a/7726509)
                    return ($(trueAnswers).not(selectedAnswers).length === 0 && $(selectedAnswers).not(trueAnswers).length === 0);
                }
            },
            

            // Compares selected responses with true answers, returns true if they match exactly
            isTheRightAnswer: function(questionId, answerId) {
                let questions = quizValues.questions[parseInt(questionId)].a;
                return questions[answerId].correct;
            },

            // Calculates knowledge level based on number of correct answers
            calculateLevel: function(correctAnswers) {
                var percent = (correctAnswers / questionCount).toFixed(2),
                    level   = null;

                if (plugin.method.inRange(0, 0.20, percent)) {
                    level = 4;
                } else if (plugin.method.inRange(0.21, 0.40, percent)) {
                    level = 3;
                } else if (plugin.method.inRange(0.41, 0.60, percent)) {
                    level = 2;
                } else if (plugin.method.inRange(0.61, 0.80, percent)) {
                    level = 1;
                } else if (plugin.method.inRange(0.81, 1.00, percent)) {
                    level = 0;
                }

                return level;
            },

            // Determines if percentage of correct values is within a level range
            inRange: function(start, end, value) {
                return (value >= start && value <= end);
            }
        };

        plugin.init = function() {
            // Setup quiz
            plugin.method.setupQuiz();

            // Bind "start" button
            $quizStarter.on('click', function(e) {
                e.preventDefault();
                plugin.method.startQuiz();
            });

            // Bind "try again" button
            $(_element + ' ' + _tryAgainBtn).on('click', function(e) {
                e.preventDefault();
                plugin.method.resetQuiz(this);
            });

            // Bind "back" buttons
            $(_element + ' ' + _prevQuestionBtn).on('click', function(e) {
                e.preventDefault();
                plugin.method.backToQuestion(this);
            });

            if(plugin.config.inputType!='button')
            {
                // Bind "next" buttons
                $(_element + ' ' + _nextQuestionBtn).on('click', function(e) {
                    e.preventDefault();
                    plugin.method.nextQuestion(this);
                });
            }
        };

        plugin.init();
    };

    $.fn.slickQuiz = function(options) {
        return this.each(function() {
            if (undefined === $(this).data('slickQuiz')) {
                var plugin = new $.slickQuiz(this, options);
                $(this).data('slickQuiz', plugin);
            }
        });
    };
})(jQuery);