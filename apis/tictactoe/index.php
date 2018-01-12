<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <title>TicTacToe! - Breathe Code</title>
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
            <h1 class="text-center">TicTacToe API</h1>
            <h4 class="text-center bg-dark text-white" style="font-size: 18px;"><span class="label label-default">HOST:</span> https://assets.breatheco.de/apis/tictactoe/api/</h4>
            <p><a target="_blank" download="TicTacToe.postman_collection.json" href="TicTacToe.postman_collection.json">Download Postman Collection</a></p>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h3>Get list of all pending games</h3>
            <pre><code class="markdown">
  [GET] /api/games
  PARAMS: None

  RESPONSE:
  
{
    "data": [
        {
            "player1": "ramon",
            "player2": "emilio",
            "winner": "ramon"
        },
        ...
    ],
    "code": 200
}
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h3>Save a game result</h3>
            <pre><code class="markdown">
  [POST] /game
  FORM PARAMS:
      - player1: string
      - player2: string
      - winner: string
  
  RESPONSE:
  
    {
        "data": [
            {
                "player1": "ramon",
                "player2": "emilio",
                "winner": "ramon"
            },
            ...
        ],
        "code": 200
    }
              </code></pre>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h3>Clean games</h3>
            <pre><code class="markdown">
  [POST] /game
  FORM PARAMS: none
  RESPONSE:
  
    []
              </code></pre>
          </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h2 class="text-center">Current Game Log</h2>
                <?php
                $fileName = 'data.json';
                $fileContent = file_get_contents($fileName);
                if(!$fileContent) throwError('Imposible to read the database file');
                $jSON = json_decode($fileContent);
                ?>
                <table class="table table-dark">
                <?php if(count($jSON)==0){ echo "<tr><td class='text-center'>No games</td></tr>"; ?> 
                <?php } else { ?>
                    <tr><td>Player 1</td><td>Player 2</td><td>Winner</td></tr>
                    <?php foreach($jSON as $game) { ?>
                        <tr>
                            <?php foreach($game as $item) { ?>
                            <td><?php echo $item; ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                <?php } ?>
                </table>
            </div>
        </div>
      </div>
      <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.11.0/highlight.min.js"></script>
      <script>hljs.initHighlightingOnLoad();</script>
    </body>
</html>