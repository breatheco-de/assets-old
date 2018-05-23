[<- Back to the APIs Readme](../docs/README.md) or [APPs Readme](../README.md)

# Event Checking Application

This application allows any BreatheCode user to register the attendance
of any person into the academy events.

## Functionalities
1. The application should know all the events that are taking place at the academy (use the [/apis/events](../../apis/events) for that)
2. There is no way to create a new event on this app, this app is only to checking users in already created events.
3. When some arrives to the event entrance there will be someone with an ipad of phone registering people.
4. If the emails is already registered into Breathecode we don't need to ask him for any other info and we can go ahead and check him in.
5. If the emails is not in BreatheCode but it is in ActiveCampaign, the app shows the information we have for confirmation and then we can check the user into the event.
6. If the user is not found on BreatheCode or AC then the app lets you add the user email, first_name and last_name. and then it checks the user inot the event.
