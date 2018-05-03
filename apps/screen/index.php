<!DOCTYPE html>
<html>
    <head>
        <title>BreatheCode Event Screen</title>
        <link rel="stylesheet" href="style.css" type="text/css" />
    </head>
    <body>
            
        <?php 
            if(empty($_GET['screen'])){
                echo "<h1>Please specify a screen:</h1>";
                echo '<ul>';
        		foreach (glob("templates/*.php") as $file)
        			echo '<li><a href="'.basename($file,".php").'">'.basename($file,".php").'</a></li>';
                echo '</ul>';
            } 
            else require('templates/'.$_GET['screen'].'.php');
        ?>
    </body>
</html>