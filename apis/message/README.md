[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Message API

Send an receive notifications between breathecode students

Types of messages: `nps_survey`

### Important information about the messages
 
1. Messages with hight priority are intrusive, the have to be answered or mark as read in order to disapear from the students face.
2. Actionable point to a URL were the student must do something.
3. non-actionable messages are just simple notifications, no action needed rather than "mark as read" if the priority is important.

## API Endpoints

#### Get all message types
```
GET: /message/types
```

#### Get all messaes
```
GET: /message/all
```

#### Render an email HTML message
```
GET: /message/render/email/{message_slug}
```

#### Get all messages from student

```
GET: /message/student/{student_id}

Possible url params:

    - read: boolean
    - answered :boolean
    - aswered :boolean
    - priority [low, high]
    - type: [nps_survey, ]
    - email: string

```

#### Send message to one student

```
POST /message/notify/student/6

{
    "slug": "nps_survey"
}
```

#### Mark Message as Answered

```
POST /message/{message_id}/answered

```

#### Mark Message as Read

```
POST /message/{message_id}/read

```


#### Delete all student messages

```
DELETE: /message/student/{message_id}
```

## Message Composition

```
    "nps_survey" => [ 
        "track_on_log" => true,
        "track_on_active_campaign" => true,
        "send_email" => true,
        "type" => 'actionable',
        "priority" => 'HIGH',
        "template" => [
            "subject" => "Take 20 seconds to give us some feedback",
    		"intro" => "Hello! We need some feedback from your side, it's the only way to become a better academy and it's only one small question, we would really appreciate your answer",
    		"question" => "How likely are you to recommend 4Geeks Academy to your friends or colleagues?"
        ],
        "getURL" => function($student){
            $id = '';
            if(!empty($student->id)) $id = $student->id;
            else if(is_string($student)) $id = $student;
            
            return 'https://assets.breatheco.de/apps/nps/survey/'.$id;
        }
    ]
```