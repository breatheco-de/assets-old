[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Message API

Send an receive notifications between breathecode students

Types of messages:

- nps_survey

## Mesage Composition

```
        "nps_survey" => [ 
            "track_on_log" => true,
            "type" => 'actionable',
            "priority" => 'HIGH'
        ]
```

### Important information about the messages
 
1. Messages with hight priority are intrusive, the have to be answered or mark as read in order to disapear from the students face.
2. Actionable point to a URL were the student must do something.
3. non-actionable messages are just simple notifications, no action needed rather than "mark as read" if the priority is important.

#### Get messages from student

```
GET: /student/:student_id

Possible url params:

    - read: boolean
    - answered :boolean
    - aswered :boolean
    - priority [low, high]
    - type: [nps_survey, ]
    - email: string

```
#### Test email message

```
POST: /test-email
```


#### Delete all student messages

```
DELETE: /student/{message_id}
```
