[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Streaming Classes API

All BreatheCode classes can be streamied thanks to its integration with StreamingVideoProvider.com.

This is the API that interactes with StreamingVideoProvider.com to retrieve and organize all the streaming information related to a particular cohort.

#### Get streaming information from a cohort
```
GET: streaming/cohort/<slug>

RESPONSE:
{
    cohort_slug: "mdc-iii",
    playlist: [
        {
            video_id: "ADS32KJNSD324K",
            duration: 20
        },
        {
            video_id: "ADS32KJNSD324K",
            duration: 20
        }
    ]
}
```
