<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Replit not found</title>
    </head>
    <body>
      <style type="text/css">
      /* Styles of the 404 page of my website. */
      
      body {
          background: #081421;
          color: #d3d7de;
          font-family: "Courier new";
          font-size: 18px;
          line-height: 1.5em;
      	  cursor: default;
      }
      
      .code-area {
          position: absolute;
          width: 400px;
      	  min-width: 400px;
          top: 50%;
          left: 50%;
          -webkit-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
      }
      
      .code-area > span {
          display: block;
      }
      
      @media screen and (max-width: 320px) {
          .code-area {
      		font-size: 5vw;
      		min-width: auto;
              width: 95%;
      		margin: auto;
      		padding: 5px;
      		padding-left: 10px;
      		line-height: 6.5vw;
          }
      }

      </style>
      <div class="code-area">
        <p style="text-align: center;">
          <img src="/apis/img/images.php?blob&random&cat=icon&tags=breathecode,32"></img>
        </p>
        <span style="color: #777;font-style:italic;">
          There is a problem with the exercise
        </span>
        <span>
          {
        </span>
        <span>
          <span style="padding-left: 15px;color:#2796ec">
             Try accessing this link manually:
          </span>
          <span style="padding-left: 15px;">
            <a target="_blank" rel="nofollow" href="<?php echo $_GET['link']; ?>" style="color: #a6a61f"><?php echo $_GET['link']; ?></a>
          </span>
      	  <span style="display:block">}</span>
        </span>
      </div>
    </body>
</html>