[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Reminders API

Setup and mantain reminders for the academy operations, you can check the active log for reminders here: [errors](https://assets.breatheco.de/apis/reminders/_data/errors.json) and [execution log](https://assets.breatheco.de/apis/reminders/_data/execution_info.json)

#### Get all reminders
```
GET: /reminders/types
```

#### Get expired reminders (that should execute)
```
GET: /reminders/expired
```

#### Execute all reminders
```
GET: /reminders/execute/all
```

#### Execute next reminder by priority
```
GET: /reminders/execute/next
```

#### Execute particular reminder even if its not due
```
GET: /reminders/execute/single/{name}
```

Test all the reminders configuration by running:
```php
php apis/reminders/test.php 
```

## How to create a new reminder?

Reminders are small PHP scripts located under the directory `/src/apis/reminder/_reminders` created with the purpose of reminding BreatheCode team about something, the name of the file has to be unique and you need to have a function with the same name of the file inside of it, for example:

A reminder script called `notify_studets_birthdates.php` must have a function called `notify_studets_birthdates` like this:
```
<?php
/*
* @title: Sample Reminder
* @frequency: 1 seconds
* @status: draft
* @to: reminders_careersupport@4geeksacademy.com
*/

use \BreatheCode\BCWrapper as BC;
use MomentPHP\MomentPHP as Moment;

function notify_studets_birthdates(){
    //echo "hello";
}
```

Another thing you may notice is the initial comment with annotation on the top:

```
/*
* @title: Sample Reminder
* @frequency: 1 seconds
* @status: active
* @to: reminders_careersupport@4geeksacademy.com
*/
```

You need to specify the `title`, `frequency`, `status` and `to` what email or audience is the reminder addressed, the possible types of frequencies are: `seconds`, `day`, `month` or `year` and the possible status are `active` or `draft`

### Utility functions:

If you want to send an email just say `emailReminder($to, $subject, $message);`.
If you want to use the breathecode's api you have a PHP wrapper available.

Here is an example of script that reminds about all the cohorts that should be finished by now and need to be updated on the platform:
```php
<?php
/*
* @title: Remind cohorts with more than 90 days
* @frequency: 1 days
* @status: active
* @to: reminders_careersupport@4geeksacademy.com
*/

use \BreatheCode\BCWrapper as BC;
use MomentPHP\MomentPHP as Moment;

function remind_abandoned_cohorts(){
    //get cohorts with stage different from 'finished'.
    $cohorts = BC::getAllCohorts(["stage_not" => "finished"]);
    $expiredCohorts = [];
    foreach($cohorts as $c){
        //loop them and make sure they have a kickoff date
        if(!empty($c->kickoff_date) && $c->kickoff_date != '0000-00-00'){
            $now = new Moment(new DateTime());
            $kickoff = new Moment($c->kickoff_date, 'Y-m-d');
            //veryfy if more than 90 days have passed since the kickoffdate
            if($kickoff->add(90,'days')->isBefore($now)) $expiredCohorts[] = $c;
        }
    }
    
    // create the plan text content that will be sent by email
    $content = "The following cohorts have to be updated on breathecode: \n\n";
    foreach($expiredCohorts as $c) $content .= "    - ".$c->name." (".$c->slug.") started on ".$c->kickoff_date." and the stage is still ".$c->stage." \n";
    
    // send reminder
    emailReminder("reminders_careersupport@4geeksacademy.com", 'Expired Cohorts', $content);
}
```