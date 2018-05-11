[<- Back to the APIs Readme](../docs/README.md) or [APPs Readme](../README.md)

# Replit Updater

This is a small React JS that updates the replits of any given breathecode 
cohort using the [/apis/replit](Replit API).

## Functionalities
1. The application receives a cohort_slug in the URL, like this:
```
https://assets.breatheco.de/apps/replit-editor/:cohort_slug
```
2. If no cohort slug is not specified ir says "cohort not found"
3. If the cohort already has some replits they need to be prefilled on the array.
4. IF the cohort has no previus replits it needs to created them from scratch.
5. The app knows how many replits exercises should be added because you can find them as replit templates.

## TODO
Everything, this app has not been started