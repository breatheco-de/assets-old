# ![alt text](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32) Meetup Clone API

## Details

This API is for use with the Meetup Clone project that is found in the Breathecode platform.

API Methods and their corresponding response formats are detailed below. It is recommended that you test your endpoints first to ensure that you know how many records the API will return before you implement in your application. Then you can compare this against your implementation in React so that you can see if the complete set of data is being stored in your WebApp.

⚠️ The use of Postman is strongly recomended for testing this API, [download it here](https://getpostman.com/).

## API Methods

### 1. `GET` All Groups

```json
GET: /apis/fake/meetup/groups

RESPONSE:

[
    "ID":9,
    "post_content":"The nicest Meetup ever",
    "post_title":"Tech Enthusiasts",
    "members": [
        "robert",
        "jjtime",
        "username2",
        "cheeselover",
        "neweradude",
        "james1996"
    ]
]
```

### 2. `GET` All Events

```json
GET: /apis/fake/meetup/events

RESPONSE:

[
    {
        ID: 36,
        post_content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec libero consectetur risus vehicula interdum eu at elit. Proin a commodo erat, eu molestie ipsum. Aliquam tristique nunc a est tristique, et convallis risus ullamcorper. Fusce nec massa ac enim pellentesque ornare. Pellentesque non sapien varius, pellentesque tellus sit amet, facilisis justo. Duis rhoncus nunc id elementum dapibus. Sed dictum lacinia vestibulum.",
        post_title: "Lorem Event",
        meta_keys: {
            day: "20180428",
            time: "07:00:00",
            _meetup: "9",
            _rsvpNo: ["robert","jjtime","username2"],
            _rsvpYes: ["cheeselover","neweradude","james1996"]
        }
    }
]
```

### 3. `GET` All Sessions

```json
GET: /apis/fake/session

RESPONSE:

{
   "ID":2,
   "username":"newKid143",
   "user_friendly_name":"Joey",
   "token":"qwerty12345asdfgzxcv"
}
```
