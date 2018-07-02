[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# Quiz API

Manage the quizzes available on the BC platform

1. Get all available quizzes:
```
GET: /quiz/all
Get all the quizes
```
2. Get single quiz:
```
GET: /quiz/<slug>
```

### Warning: The followin methods requier autentication

3. Create a quiz:
```
PUT: /quiz/<slug>?access_token=<token>

Request (application/json):

    {
        "info": {
            "slug": "the_quiz_slug"
        }
        "questions": [
            { 
                "q": "Is this the question?",
                "a": [ 
                    {"option": "Of course, my friend", "correct": false},
                    {"option": "Not at all", "correct": true}
                ] 
            }
        ]
    }
```

4. Update a quiz:
```
POST: /quiz/<slug>?access_token=<token>

Request (application/json):

    {
        "info": {
            "slug": "the_quiz_slug"
        }
        "questions": [
            { 
                "q": "Is this the question?",
                "a": [ 
                    {"option": "Of course, my friend", "correct": false},
                    {"option": "Not at all", "correct": true}
                ] 
            }
        ]
    }
```