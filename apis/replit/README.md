[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Replit Exercises API

#### Get replt templates
```
GET: replit/templates
```

#### Get all replit from particular cohort
```
GET: replit/cohort/{cohort_slug}
```

#### Save all replits from a particular cohort
```
POST: replit/cohort/{cohort_slug}

Request (application/json):

    {
    	"label": "Boats.com",
    	"profile": "boats",
    	"weeks": []
    }
```