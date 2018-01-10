<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Rigo Learn To Code - Breathe Code</title>
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
            <h1>Rigo Game API</h1>
            <h4 style="font-size: 18px;"><span class="label label-default">HOST:</span> https://assets.breatheco.de/apis/cup-game/api/</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Get list of all pending games</h3>
            <pre><code class="markdown">
  [GET] /pending_attempts
  PARAMS: None

  RESPONSE:
  
  {
      "data": {
          "pending_attempts": [
              {
                  "full_name": "Maria Perez",
                  "avatar_slug": "avatar_slug",
                  "moves": [
                      "move_up",
                      "move_up"
                  ]
              }
          ]
      },
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
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Delete all pending attempts</h3>
            <pre><code class="markdown">
  [POST] /add_attempts
  
  REQUEST BODY:
  
  {
    "full_name": "Maria Perez",
    "avatar_slug": "avatar_slug",
    "moves": ["move_up", "move_up"]
  }
  
  RESPONSE:
  
  {
    "full_name": "Maria Perez",
    "avatar_slug": "avatar_slug",
    "moves": ["move_up", "move_up"]
  }
              </code></pre>
          </div>
        </div>
      </div>
      <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js"></script>
      <script>hljs.initHighlightingOnLoad();</script>
  </body>
</html>
