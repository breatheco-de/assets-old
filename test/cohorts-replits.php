<?php
/**
 * This test checks that all the cohorts have all the needed replits links
 */

    include './globals.php';
    include './vendor/autoload.php';
    include './test/utils.php';
    
    use ZendDiagnostics\Check;
    use ZendDiagnostics\Runner\Runner;
    use ZendDiagnostics\Runner\Reporter\BasicConsole;
    
    $assetsURL = ASSETS_HOST.'/apis';
    // Create Runner instance
    $runner = new Runner();
    
    $resp = get(BREATHECODE_HOST.'cohorts/');
    foreach($resp->data as $cohort){
        if($cohort->stage != 'not-started')
            $runner->addCheck(checkURL($assetsURL.'/replit/cohort/'.$cohort->slug, 'https:\/\/repl.it\/classroom\/invite\/'));
    }
    
    $runner->addReporter(new BasicConsole(80, true)); 
    // Run all checks
    $results = $runner->run();

    if($results->getFailureCount() > 0){
        sendError(
            ["a@4geeksacademy.com"], 
            "The cohort replits test is failing",
            "
Make sure all the cohorts have the replit urls setup.
            "
        );
        exit(1);  
    } 