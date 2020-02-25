[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# API used for the Kill The Bug, intro to coding game

### 1. Get list of all pending games
```js
  [GET] /pending_attempts/:level_id
  PARAMS: None

  RESPONSE:
  
  {
      "data": {
          "pending_attempts": [
              {
                  "id": "5a57b11d6b8e0",
                  "created_at": 1515697384,
                  "username": "MariaPerez",
                  "character": "batman",
                  "commands": [
                      "runLeft",
                      "jumpRight"
                  ]
              }
              ...
          ]
      },
      "code": 200
  }
```

### 2. Get Game Levels
```js
  [GET] /get_levels
  
  RESPONSE:
  
  {
      "data": [
          {
              "slug": "1",
              "difficulty": "easy",
              "thumb": "3.png",
              "title": "Map 1"
          },
          ...
      ],
      "code": 200
  }
```

### 3. Get single game level
```
  [GET] /get_levels/:level_id
  
  RESPONSE:
  
  {
      "data": {
        "title": "Map 1",
        "difficulty": "easy",
        "thumb": "3.png",
        "tilewidth": 70,
        "type": "map",
        "version": 1,
        "width": 12,
        "height": 10,
        "layers": []
      },
      "code": 200
  }
```

4. Add one pending attempt
```js
  [POST] /add_attempt
  //the possible characters are: ["batman", "einstein"]
  
  REQUEST BODY:
  
  {
    "username": "alesanchezr",
    "character": "batman",
    "commands": ["runRight", "runLeft", "jumpRight", "jumpLeft", "climb", "open", "push", "kill"]
  }
  
  RESPONSE:
  
  {
    "data": "ok",
    "code": 200
  }
```

5. Delete One Pending attempt
```js
  [POST] /delete_attempt
  
  REQUEST BODY:
  
  {
    "id": "5a57b11d6b8e0"
  }
  
  RESPONSE:
  
  {
    "data": "ok",
    "code": 200
  }
```

6. Delete all pending attempts [clean now](https://assets.breatheco.de/apis/kill-the-bug/api.php?method=clean_attempts)


```js
  [POST] /clean_attempts
  
  RESPONSE:
  
  {
      "data": "ok",
      "code": 200
  }
```