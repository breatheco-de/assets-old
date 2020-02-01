[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# TodoList API

⚠️ The use of [Postman](https://www.getpostman.com/downloads/) or [Insomnia.rest](https://insomnia.rest/) is strongly recomended for testing the API.

### How to use this API:

1. Create your todolist with `[POST] /todos/user/<username>`. (you can pick any username)
2. Update your todolist with `[PUT] /todos/user/<username>`, you have to pass the whole todolist every time.
3. Delete your todolist (if needed) `[DELETE] /todos/user/<username>`.


## 1. Get list of todo's for a particular user
```
  [GET] /todos/user/<username>
  Content-Type: "application/json"
  PARAMS: None

  RESPONSE:

[
  { label: "Make the bed", done: false },
  { label: "Walk the dog", done: false },
  { label: "Do the replits", done: false }
]
```
## 2. Create a new todo list of a particular user

This method is only for creation, you need to pass an empty array on the body because there are no todo yet.

```
  [POST] /todos/user/<username>
  Content-Type: "application/json"
  BODY: []

  RESPONSE:

    {
        "result": "ok"
    }
```
## 2. Update `the entire list` of todo's of a particular user

This method is to update only, if you want to create a new todo list for a particular user use the POST above.

```
  [PUT] /todos/user/<username>
  Content-Type: "application/json"
  BODY:
  [
    { label: "Make the bed", done: false },
    { label: "Walk the dog", done: false },
    { label: "Do the replits", done: false }
  ]

  RESPONSE:

    {
        "result": "A list with 3 todos was succesfully saved"
    }
```
## 3. Delete a user and all of their todo's
```
  [DELETE] /todos/user/<username>
  Content-Type: "application/json"
  FORM PARAMS: none
  RESPONSE:

  [ "result": "ok" ]
```
