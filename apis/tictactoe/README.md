# TicTacToe API

HOST: https://assets.breatheco.de/apis/tictactoe/
[Download Postman Collection](TicTacToe.postman_collection.json)
          
## 1. Get list of all pending games
```
  [GET] /api/games
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

## 3. Clean games
```
  [POST] /game
  FORM PARAMS: none
  RESPONSE:
  
    []
```
