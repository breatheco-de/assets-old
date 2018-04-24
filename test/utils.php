<?php

    use ZendDiagnostics\Check\GuzzleHttpService;
    function checkURL($url,$string = null){
        return new GuzzleHttpService(
            $url,
            array(),
            array(),
            200,
            $string
        );
    }