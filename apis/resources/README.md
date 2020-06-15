[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Resources API

Academy recomendations and biblography, here is a list of the possible [types](https://assets.breatheco.de/apis/resources/types), [topics](https://assets.breatheco.de/apis/resources/topics) and [technologies](https://assets.breatheco.de/apis/resources/technologies).

## Sample resource object

```json
    {
        "type": "youtube",
        "topics": [],
        "technologies": [],
        "created_at": "20/10/1985",
        "up_votes": 23,
        "referrer": null,
        "slug": "the-net-ninja",
        "url": "https://www.youtube.com/channel/UCW5YeuERMmlnqo4oq8vwUpg?pbjreload=10"
    },
```

## Get topics
```
GET: resources/profile/{profile_slug}
```

## Get types
```
GET: resources/profile/{profile_slug}
```

## Get technologies
```
GET: resources/profile/{profile_slug}
```

## Vote up or down a resource (pending development)

Vote up or down for a resources.

```
POST: resources/{resource_down}/up
POST: resources/{resource_id}/down

Return the resource object

{
    "type": "youtube",
    "topics": [],
    "technologies": [],
    "created_at": "20/10/1985",
    "up_votes": 23,
    "referrer": null,
    "slug": "the-net-ninja",
    "url": "https://www.youtube.com/channel/UCW5YeuERMmlnqo4oq8vwUpg?pbjreload=10"
}

```

#### Get course profile resources
```
GET: resources/profile/{profile_slug}
```
