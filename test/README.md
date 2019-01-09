[<- Back to the Main Readme](../docs/README.md)

# Tests

On this folder is were all the assets.breatheco.de tests are created, right now the only test available
are on diganosis.php.
```
./vendor/bin/phpunit test/ --colors
```


## diganosis.php

This file is using the [ZendDiagnostics](https://github.com/zendframework/ZendDiagnostics) package to check 
most of the GET request that this repository contains.

The idea is not only to check to assets.breatheco.co but also for any other GET 
request that needs to be available publicly for the BreatheCode Platform to function properly.

## Unit Testing

We also need to develop the unit test for all the API requests, mocking the calls and a the database, similar to how was done on api.breatheco.de tests.

[Code Coverage](https://phpunit.de/manual/6.5/en/code-coverage-analysis.html): We need to make sure to have a coverage bigger than 90%

## Continius Integration

Lastly, we need to update [buddy.works](http://buddy.works) to run the tests when we push to master, and only deploy if the tests are successfull.