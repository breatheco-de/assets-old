[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# TicTacToe API
          
## 1. Get list of all pending games
```
  [GET] /game/all
  PARAMS: None

  RESPONSE:
  
{
    "data": [
        {
            "player1": "ramon",
            "player2": "emilio",
            "winner": "ramon"
        },
        ...
    ],
    "code": 200
}
```
## 2. Save a game result
```
  [POST] /game
  FORM PARAMS:
      - player1: string
      - player2: string
      - winner: string
  
  RESPONSE:
  
    {
        "data": [
            {
                "player1": "ramon",
                "player2": "emilio",
                "winner": "ramon"
            },
            ...
        ],
        "code": 200
    }
```
## 3. Clean games
```
  [DELETE] /game/all
  FORM PARAMS: none
  RESPONSE:
  
    []
```
