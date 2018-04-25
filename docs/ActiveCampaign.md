# BC + ActiveCampaign Integration

The BC Platform integrates very deep with Active Campaign, an integration can happend in one of the following situations:
1. During an automation: An automation could use a webhook to ask or save some information on BC.
2. During a website usage: Google Tag Manager is capable of registering events and statistics into Active Campaign.
3. During an BC API usage: It could be assets.breatheco.de or api.breatheco.de. Both API trigger events to Active Campaign.

## Events Triggered

The following events have been registered into Active Campaign and can be triggered of the integrations discussed above:

| Event Name                | When someone...                   | Where                         |
|---------------------------|-----------------------------------|-------------------------------|
| application_rendered      | renders an application form       | 4geeksacademy.com/apply       |
| coding_weekend_attendance | arrives to the office             | assets/apps/event-checkin/    |
| newsletter_signup         | signs up to the newsletter        | 4geeksacademy.com             |
| nps_survey_answered       | answers the nps survey            | assets/apps/nps/              |
| public_event_attendance   | checkins at event entrance        | assets/apps/event-checkin/    |
| student_application       | aplies to the academy             | 4geeksacademy.com/apply       |
| student_referral          | refers a student                  | 4geeksacademy.com/apply       |
| syllabus_download         | downloads syllabus                | 4geeksacademy.com (all site)  |

### Code to trigger an event

```php
use \AC\ACAPI;
ACAPI::start(AC_API_KEY);
ACAPI::setupEventTracking('25182870', AC_EVENT_KEY);
$result = ACAPI::trackEvent($email, 'nps_survey_answered');
```
An exception will rise if the event could not be registered into ActiveCampaign.

**Note: Events can only be triggered from PHP or from inside ActiveCampaign. No Javascript alternative is provided by ActiveCampaign.**
[Here is more about ActiveCampaign event tracking.](https://help.activecampaign.com/hc/en-us/articles/221870128-An-overview-of-Event-Tracking)