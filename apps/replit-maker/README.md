[<- Back to the APIs Readme](../docs/README.md) or [APPs Readme](../README.md)

# Replit Updater

This is a small React JS that updates the replits for any given breathecode 
cohort using the [/apis/replit](Replit API). It will be used by the breathecode teacher.

## Functionalities
Everythign starts when the user types the following URL to create/update all the replits for a particular cohort:
```
https://assets.breatheco.de/apps/replit-editor/:cohort_slug
```
1. The app renders a form with as many inputs as replits are supposed to be specified (you can check how many are supposed to be specified here: [/replit/templates](https://assets-alesanchezr.c9users.io/apis/replit/templates)
2. If the cohort has some pre-set replits already, the form should be prefilled with those values.
3. If the cohort has no previus replits, the form will render with empty values.
4. The user also has the option of bringing the links from a previous cohort.

Note: If no cohort slug is specified, the application should show "cohort not found".

## TODO
Everything, this app has not been started
