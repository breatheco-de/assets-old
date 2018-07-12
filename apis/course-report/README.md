[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Course Report API

#### Get all programs
```
GET: programs/
```
#### Get all schools
```
GET: schools/
```
#### Get school information
```
GET: school/{school_slug}
```
#### Get program information
```
GET: program/{program_slug}
```
#### Get school program
```
GET: {school_slug}/{program_slug}
```
#### Compare schools
```
GET: course-report/compare/full-stack?schools=4geeks,wyncode,ironhack

Response example:

{
    "4geeks" : {
        
    },
    "wyncode":{
        
    },
    "ironhack":{
        
    }
}
```