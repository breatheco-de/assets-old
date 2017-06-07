<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>Quiz API - Breathe Code</title>
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
            <h1>The Quiz API</h1>
            <h4 style="font-size: 18px;"><span class="label label-default">HOST:</span> https://assets.breatheco.de/quiz/</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Get list of all quizes JSON</h3>
            <pre><code class="markdown">
  [GET] /quizes.php
  Params: None
  
    [
        {
            "info": {
                "name": "Test Your Knowledge!!",
                "main": "Think you're smart enough to be on Jeopardy? Find out!",
                "results": "Learn More: Etiam scelerisque, nunc ac egestas t volutpat. Maurid sit amet purus.",
                "badges": [
                    { "slug": "css_master", "points": 23 },
                    { "slug": "html_master", "points": 45 }
                ],
                "slug": "events",
                "category": "javascript"
            }
        }
    ]
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-8 col-sm-offset-2">
            <h3>Get only one quiz</h3>
            <pre><code class="markdown">
  [GET] /quizes.php?slug=css
  
  The slug is a unique string id for each quiz in the platform.
  
    [
        {
            "info": {
                "name": "Test Your Knowledge!!",
                "main": "Think you're smart enough to be on Jeopardy? Find out!",
                "results": "Learn More: Etiam scelerisque, nunc ac egestas t volutpat. Maurid sit amet purus.",
                "badges": [
                    { "slug": "css_master", "points": 23 },
                    { "slug": "html_master", "points": 45 }
                ],
                "slug": "events",
                "category": "javascript"
            },
            "questions": [
                {
                    "q": "Which of the following is the correct event handler to detect a mouse click on a link?",
                    "a": [
                        { "option": "onMouseUp", "correct": false },
                        { "option": "onLink", "correct": false },
                        { "option": "onClick", "correct": true }
                    ]
                },
                {
                    "q": "Which of the following is the correct event handler to detect a mouse click on a link?",
                    "a": [
                        { "option": "onMouseUp", "correct": false },
                        { "option": "onLink", "correct": false },
                        { "option": "onClick", "correct": true }
                    ],
                    "select_any": true
                },
                ...
            ]
        },
        ...
    ]
              </code></pre>
          </div>
        </div>
      </div>
      <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js"></script>
      <script>hljs.initHighlightingOnLoad();</script>
  </body>
</html>
