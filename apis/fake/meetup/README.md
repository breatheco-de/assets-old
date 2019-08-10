# ![alt text](/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32) Fake Contact-List API

⚠️ The use of Insomnia.rest is strongly recomended for this API, [download it here](https://insomnia.rest/).

#### 1) Get All meetups
```
GET: /apis/fake/meetup

RESPONSE:

[
    {
        ID: 9,
        post_content: "The nicest Meetup ever",
        post_title: "Tech Enthusiasts"
    }
]
```

#### 2) Get all events
```
GET: /apis/fake/events

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

#### 3) Get all sessions
```
DELETE: /apis/fake/session

RESPONSE:

{
    ID: 2,
    username: "theUser",
    token: "qwerty12345asdfgzxcv"
}
```
