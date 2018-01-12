<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Sounds API - Breathe Code</title>
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css" integrity="sha384-Zug+QiDoJOrZ5t4lssLdxGhVrurbmBWopoEl+M6BdEfwnCJZtKxi1KgxUyJq13dy" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/style.css?v1" type="text/css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/styles/default.min.css">
    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>
  <body>
      <div class="bcmenu container">
        <div class="row text-center">
          <div class="col-12">
            <img alt="breathe code logo" src="http://assets.breatheco.de/img/images.php?blob&random&cat=icon&tags=breathecode,128">
            <h1 class="text-center">Sounds API</h1>
            <h4 class="text-center bg-dark text-white" style="font-size: 18px;"><span class="label label-default">HOST:</span> https://assets.breatheco.de/apis/sounds/api/</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h3>Get list of all media sounds</h3>
            <pre><code class="markdown">
  [GET] /api/sounds
  PARAMS: None

  RESPONSE:
  
  {
  	"fx" : [
  		{ "id":1, "category":"game", "name":"Game Over", "url":"sounds/mario/fx_gameover.wav" },
  		...
  	],
  	"songs" : [
  		{ "id":1, "category":"game", "name":"Mario Castle", "url":"sounds/mario/songs/castle.mp3" },
  		...
  	]
  }
              </code></pre>
          </div>
        </div>
      </div>
      <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js"></script>
      <script>hljs.initHighlightingOnLoad();</script>
    </body>
</html>