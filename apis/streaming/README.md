[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Streaming Classes API

All BreatheCode classes can be streamied thanks to its integration with StreamingVideoProvider.com.

This is the API that interactes with StreamingVideoProvider.com to retrieve and organize all the streaming information related to a particular cohort.

#### Get streaming information from a cohort

Here is and example for [Miami Downtown VII](http://assets.breatheco.de/apis/streaming/cohort/miami-downtown-vii)

```
GET: streaming/cohort/<slug>

RESPONSE:
{
    cohort_slug: "mdc-iii",
    "it": "2ol69hioq4is",
    "rtmp": "1k90nleph0m8c8cg4wws",
    "playlist": "146965",
    "player": "http://some/url/to/video/player",
    "iframe": "http://some/url/to/iframe",
    "rtmp": "rtmp://some/url",
    "videos": []
}
```

#### Add new cohort streaming info

This enpoing is not finished, [here is the issue](https://github.com/breatheco-de/assets/issues/57)

#### Get cohort videos
```
GET: /cohort/{cohort_slug}/videos

RESPONSE:
[
    {
        "ref_no": "2045652",
        "clip_key": "4u11zbuc5xgk",
        "title": "Recording #2ol69hioq4is (Mar 13, 2019 15:29:02)",
        "tags": {},
        "tag_number": {},
        "tag_string": {},
        "video_source": "ondemand",
        "stream_name": {},
        "channel_ref": "146965",
        "duration": "10828",
        "date_created": "1552523635",
        "date_modified": "1552523774",
        "file_size": "1452591227"
    },
    ...
]
```

#### Get all SVP playlists
```
GET: /playlists

RESPONSE:
[
    {
        "ref_no": "146965",
        "title": "Santiago PT 1"
    },
    ...
]
```