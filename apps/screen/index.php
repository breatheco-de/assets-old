<!DOCTYPE html>
<html>
    <head>
        <title>BreatheCode Event Screen</title>
        <link rel="stylesheet" href="style.css" type="text/css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <?php  if(empty($_GET['screen'])){ ?>
            <h1>Please specify a screen:</h1>
            <div class="row">
        	<?php foreach (glob("templates/*.php") as $file) { ?>
              <a class="col-12 col-sm-6 col-md-4 card p-0" href="<?php echo basename($file,".php"); ?>">
                <div class="card-img-top" style="background-image: url(templates/<?php echo basename($file,".php"); ?>.png)"></div>
                <div class="card-body">
                  <h5 class="card-title text-center"><?php echo basename($file,".php"); ?></h5>
                </div>
              </a>
            <?php } ?>
            </div>
        <?php } else require('templates/'.$_GET['screen'].'.php'); ?>
    </body>
</html>