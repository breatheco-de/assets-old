[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# ![alt text](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32) Events API

1. Get All the BreatheCode Events
```
GET: /apis/event/all

Request PARAMS:
    type (enum): types of the events separated by comma ['workshop','coding_weekend','hackathon','intro_to_coding','4geeks_night','other']
    location (string): slug of the academy location
    lang (enum): event language ['en','es']
    status (string): ['upcoming', 'past']
```

2. Get redirected to the next upcoming event
```
GET: /apis/event/redirect
        
Request PARAMS:
    fallback (url): where to redirect if no event has been found
    type (enum): types of the events separated by comma ['workshop','coding_weekend','hackathon','intro_to_coding','4geeks_night','other']
    location (string): slug of the academy location
    lang (enum): event language ['en','es']
    status (string): ['upcoming', 'past']
```

3. Get One Particular BC Event
```
GET: /apis/event/:event_id
```

4. Delete One Particular BC Event
```
DELETE: /apis/event/:event_id
```

5. Create one event
```
PUT: /apis/event/

Request (application/json)

    body:
    {
        "title": "asd",
        "description": "Description for the event",
        "url":"http://www.asdads.com",
        "invite_only": true,
        "city_slug": "ccs",
        "address": "3444 NW 43rd ST",
        "banner_url": "http://url/to/image.png",
        "type": "coding_weekend",
        "event_date": "2017-08-20 20:30:00",
        "logo_url":"http://www.asdads.com",
        "capacity":200
    }
```
Note: The valid event types are: workshop, hackathon, intro_to_coding, coding_weekend, 4geeks_night, other

6. Checking to one event
```
PUT: /apis/event/{evnet_id}/checkin

Request (application/json)

    {
      "email": "aalejo@gmail.com"
    }

```

7. Get event checkins
```
GET: /apis/event/{event_id}/checkin

Response (application/json)

    [
        {
            "event_id": "1",
            "email": "aalejo@gmail.com",
            "created_at": "2018-05-23 20:26:00",
            "id": "1"
        },
        {
            "event_id": "1",
            "email": "aalejo@gmail.com",
            "created_at": "2018-05-23 20:26:09",
            "id": "2"
        },
        {
            "event_id": "1",
            "email": "aalejo@gmail.com",
            "created_at": "2018-05-23 20:26:21",
            "id": "3"
        },
        {
            "event_id": "1",
            "email": "aalejo@gmail.com",
            "created_at": "2018-05-23 20:26:22",
            "id": "4"
        }
    ]
```