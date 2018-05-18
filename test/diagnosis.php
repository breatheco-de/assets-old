<?php

    include './globals.php';
    include './vendor/autoload.php';
    include './test/utils.php';
    
    use ZendDiagnostics\Check;
    use ZendDiagnostics\Runner\Runner;
    use ZendDiagnostics\Runner\Reporter\BasicConsole;

    
    //$assetsURL = 'https://assets.breatheco.de/apis';
    $assetsURL = ASSETS_HOST.'/apis';
    // Create Runner instance
    $runner = new Runner();
    
    $runner->addCheck(checkURL($assetsURL.'/nps/student/5', 'full_name'));
    $runner->addCheck(checkURL($assetsURL.'/nps/responses'));
    
    $runner->addCheck(checkURL($assetsURL.'/quiz/rest/user/5/response'));
    $runner->addCheck(checkURL($assetsURL.'/quiz/html', "info"));
    $runner->addCheck(checkURL($assetsURL.'/quiz/css', "info"));
    $runner->addCheck(checkURL($assetsURL.'/quiz/rest', "info"));
    $runner->addCheck(checkURL($assetsURL.'/quiz/events', "info"));
    
    $runner->addCheck(checkURL($assetsURL.'/replit/cohort/mdc-iii', "html"));
    $runner->addCheck(checkURL($assetsURL.'/replit/cohort', "en-iii"));
    $runner->addCheck(checkURL($assetsURL.'/replit/templates', "object-oriented-programing"));
    
    $runner->addCheck(checkURL($assetsURL.'/kill-the-bug/api/pending_attempts', '"code":200'));

    $runner->addCheck(checkURL($assetsURL.'/hook/referral_code/'.getSample('user')->username, '"referral_code"'));
    $runner->addCheck(checkEndpoint(
        'POST',
        $assetsURL.'/hook/sync/contact',
        ['email'=> getSample('user')->username]
    )->assertSuccess());
    
    $runner->addCheck(checkURL($assetsURL.'/fake/hello.php', '"content" : "hello world"'));
    $runner->addCheck(checkURL($assetsURL.'/fake/project1.php', 'Amazon eCommerce'));
    
    $runner->addCheck(checkURL($assetsURL.'/img/images.php?blob&random&cat=icon&tags=breathecode,16'));
    
    $runner->addCheck(checkURL($assetsURL.'/video/why-pair-programming'));
    
    $runner->addCheck(checkURL($assetsURL.'/project/all',"readme"));
    
    $runner->addCheck(checkURL($assetsURL.'/syllabus/full-stack','"label"'));
    $runner->addCheck(checkURL($assetsURL.'/syllabus/web-development','"label"'));
    
    // Add console reporter
    $runner->addReporter(new BasicConsole(80, true));
    
    // Run all checks
    $results = $runner->run();

    if($results->getFailureCount() > 0) exit(1);