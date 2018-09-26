[<- Back to the APIs Readme](../docs/README.md) or [APIs Readme](../README.md)

# FAKE APIs
A collection of API ideal for fetch.js exercises.

There are 3 very complete apis to play with: [Contact List](./apis/fake/contact/README.md), [Meetup.com](./apis/fake/meetup/README.md) or [Tic Tac Toe](./apis/fake/tictactoe/README.md).  
But you also have these small apis for very particular purposes:

Hello World API Example
```
[GET] /hello.php
Small hello world example, for those how love to start with a Hello World.
```

Fake portfolio API:
```
[GET] /project1.php
A Project object json example, is the perfect first example.

[GET] /project_list.php
A list of projects json example, is great for the first JSON array example, you can make them loop the projects.
```

Very weird json
```
[GET] /weird_portfolio.php
A Wordpress blog example, is good to teach the students how to work with very big JSONs.
```

Big sized file that takes some time to download
```
[GET] /zips.php
All zips in the US (very big json), is great to teach students that some requests take longer and AJAXs is asynchronous.
```

Price of bitcoing (live!)
```
[GET] /crypto.php
Get the current bitcoin price in real time. Request limit: every 5 sec.
```