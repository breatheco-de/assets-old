<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Image API - Breathe Code</title>
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
            <img alt="breathe code logo" src="./images.php?blob&random&cat=icon&tags=breathecode,16">
            <h1>The Image API</h1>
            <h4 style="font-size: 18px;"><span class="label label-default">HOST:</span> https://assets.breatheco.de/img/</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Get list of images in JSON</h3>
            <pre><code class="markdown">
  [GET] /images.php
  Params:
    - cat: funny (string)
    - tags: tag1,tag2,...tag (string of terms separated by comma)
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-2">
            <h3>Get only one random image</h3>
            <pre><code class="markdown">
  [GET] /images.php?random
  
  You can combine it with any other parameters.
              </code></pre>
          </div>
          <div class="col-sm-4">
            <h3>Get result as blob image</h3>
            <pre><code class="markdown">
  [GET] /images.php?blob
  
  You can combine it with any other parameters.
            </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-2">
            <h3>Categories</h3>
            <pre><code class="javascript">
[GET] /images.php?getcategories

//Example
[
  "bg",
  "class-diagrams",
  "funny",
  "icon",
  "logo",
  "meme",
  "other",
  "replit",
  "scientific"
]
              </code></pre>
          </div>
          <div class="col-sm-4">
            <h3>Tags</h3>
            <pre><code class="javascript">
[GET] /images.php?gettags

//Example
[
  "background-vertical",
  "small-mosaic",
  "kids",
  "rigoberto",
  "scared-baby",
  "jquery-dom-en",
  "ebbinghaus"
]
            </code></pre>
          </div>
        </div>
      </div>
      <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js"></script>
      <script>hljs.initHighlightingOnLoad();</script>
  </body>
</html>
