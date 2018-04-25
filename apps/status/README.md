[<- Back to the APIs Readme](../docs/README.md) or [APPs Readme](../README.md)

# BreatheCode Status Application

This app is a dashboard that lets you see the entire status of the breathecode application.

Status that we need to check for:

1. ***General state of all the APIs:*** 

The status of each API can be ['success','warning','error']
| status    | reason                                    |
|-----------|-------------------------------------------|
| success   | all test runned smoothly                  |
| warning   | some test failed                          |
| error     | all test failed or not responding at all  |

2. ***Login, Logout and Remind Password***

We need to unit test the front-end and backend of the authentication service and the application

3. ***Important images***

The following resources are used everywhere and need to be tested: 
- BreatheCode Logo in all its versions: 
http://assets.breatheco.de/apis/img/images.php?blob&random&cat=icon&tags=breathecode,16
http://assets.breatheco.de/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32
http://assets.breatheco.de/apis/img/images.php?blob&random&cat=icon&tags=breathecode,128

4. ***Status of the BC API***

We need to know if all the services of api.breatheco.de are working properly, show a table of all the groups of services: badges, students, teachers, cohorts, assignments, etc.
| status    | reason                                    |
|-----------|-------------------------------------------|
| success   | all test runned smoothly                  |
| warning   | some test failed                          |
| error     | all test failed or not responding at all  |

## TODO

This applications needs to be developed