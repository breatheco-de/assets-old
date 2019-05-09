[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# BreatheCode User Activity API

The Activity API sits in the middle of our entire statistic database, here is where we track any interaction the student has between the academy.

BreatheCode is capable of looging all interactions on both platforms: ActiveCampaign and BreatheCode itself.

## Types of activities:

[Click here](/apis/activity/types) to get the possible activity types being tracked right now for each user.

## Endpoints for the user

#### Get recent user activity
```
GET: activity/user/{email_or_id}?slug=activity_slug
```

#### Add a new user activity (requiers autentication)

```
POST: activity/user/{email_or_id}

{
    'slug' => 'activity_slug',
    'data' => 'any aditional data (string or json-encoded-string)'
}
```

## Endpoints for the coding_error's

#### Get recent user coding_errors
```
GET: activity/coding_error/{email_or_id}?slug=activity_slug
```

#### Add a new coding_error (requiers autentication)

```
POST: activity/coding_error/

{
    "user_id" => "my@email.com",
    "slug" => "webpack_error",
    "data" => "optiona additional information about the error",
    "message" => "file not found",
    "name" => "module-not-found,
    "severity" => "900",
    "details" => "stack trace for the error as string"
}
```