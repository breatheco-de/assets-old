[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Resources API

Academy recomendations and biblography, here is a list of the possible [types](https://assets.breatheco.de/apis/resources/type), [topics](https://assets.breatheco.de/apis/resources/topic) and [technologies](https://assets.breatheco.de/apis/resources/technology).

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

#### Get course profile resources
```
GET: resources/profile/{profile_slug}
```