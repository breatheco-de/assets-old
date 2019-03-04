[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# TodoList API
          
## 1. Get list of todo's for a particular user
```
  [GET] /todos/user/<username>
  PARAMS: None

  RESPONSE:
  
[
  { label: "Make the bed", done: false },
  { label: "Walk the dog", done: false },
  { label: "Do the replits", done: false }
]
```
## 2. Create list of todo's of a particular user
```
  [POST] /todos/user/<username>
  BODY:
  [
    { label: "Make the bed", done: false },
    { label: "Walk the dog", done: false },
    { label: "Do the replits", done: false }
  ]
  
  RESPONSE:
  
    {
        "result": "ok"
    }
```
## 2. Update list of todo's of a particular user
```
  [PUT] /todos/user/<username>
  BODY:
  [
    { label: "Make the bed", done: false },
    { label: "Walk the dog", done: false },
    { label: "Do the replits", done: false }
  ]
  
  RESPONSE:
  
    {
        "result": "ok"
    }
```
## 3. Delete all todos of a particular user
```
  [DELETE] /todos/user/<username>
  FORM PARAMS: none
  RESPONSE:
  
  [ "result": "ok" ]
```
