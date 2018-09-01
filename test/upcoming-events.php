<?php
/**
 * This test checks that there is always an upcoming 4geeks night
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
    
    $runner->addCheck(checkURL($assetsURL.'/event/all?status=upcoming&type=intro_to_coding,4geeks_night', 'description'));
    
    $runner->addReporter(new BasicConsole(80, true)); 
    // Run all checks
    $results = $runner->run();

    if($results->getFailureCount() > 0){
        sendError(
            ["a@4geeksacademy.com"], 
            "The upcoming events test is failing",
            "
Make sure there is a 4Geeks Nights scheduled to come.
            "
        );
        exit(1);  
    } 