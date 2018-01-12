<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Kill The Bug! - Breathe Code</title>
    <meta name="viewport" content="width=device-width">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css?v1" type="text/css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/default.min.css">
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
      <div class="bcmenu container-fluid">
        <div class="row text-center">
          <div class="col-xs-12">
            <img alt="breathe code logo" src="../img/images.php?blob&random&cat=icon&tags=breathecode,128">
            <h1>Kill The Bug API</h1>
            <h4 style="font-size: 18px;"><span class="label label-default">HOST:</span> https://assets.breatheco.de/apis/kill-the-bug/api/</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Get list of all pending games</h3>
            <pre><code class="markdown">
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
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Get Game Levels</h3>
            <pre><code class="markdown">
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
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Get Single Game Level</h3>
            <pre><code class="markdown">
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
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Add one pending attempt</h3>
            <pre><code class="markdown">
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
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Delete One Pending attempt</h3>
            <pre><code class="markdown">
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
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Delete all pending attempts</h3>
            <pre><code class="markdown">
  [POST] /clean_attempts
  
  RESPONSE:
  
  {
      "data": "ok",
      "code": 200
  }
              </code></pre>
          </div>
        </div>
      </div>
      <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js"></script>
      <script>hljs.initHighlightingOnLoad();</script>
  </body>
</html>
