[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Add new contact

1. Get All the BreatheCode Events
```
GET: /apis/event/all
```

2. Get One Particular BC Event
```
GET: /apis/event/:event_id
```

3. Delete One Particular BC Event
```
DELETE: /apis/event/:event_id
```

4. Create one event
```
PUT: /apis/event/

Request (application/json)

    body:
    {
    	'description' => 'event description',
    	'title' => 'event title',
    	'url' => 'http://eventbrite_or_landing_page_url.com',
    	'capacity' => 100,
    	'logo_url' => 'http://url/to/logo',
    	'invite_only' => true
    }
```

5. Checking to one event
```
PUT: /apis/event/{evnet_id}/checkin

Request (application/json)

    {
      "email": "aalejo@gmail.com"
    }

```

5. Get event checkins
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