[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# BreatheCode User Activity API

The Activity API sits in the middle of our entire statistic database, here is where we track any interaction the student has between the academy.

BreatheCode is capable of looging all interactions on both platforms: ActiveCampaign and BreatheCode itself.

## Types of activities:

```
[
    {
        slug: "breathecode_login", //when a students logs in
        track_on_active_campaign: true,
        track_on_log: true
    },
    {
        slug: "online_platform_registration" //when a students signs up for the onlie platform
        "track_on_active_campaign" => true,
        track_on_log:true
    },
    {
        slug: "public_event_attendance", //when a students comes to an event
        track_on_active_campaign: true,
        track_on_log: true
    },
    }
        slug: "nps_survey_answered", //when a NPS survay is answered
        track_on_active_campaign: true,
        track_on_log: true
    }
]
```

# Endpoints

#### Get recent student activity
```
GET: activity/user/{email_or_id}?slug=activity_slug
```

#### Add a new activity (requiers autentication)

```
GET: activity/user/{email_or_id}

{
    'slug' => 'activity_slug',
    'data' => 'any aditional data (string or json-encoded-string)'
}
```