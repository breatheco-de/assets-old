<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="EN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>W3Lessons.com Full Windows, Full Screen Iframe</title>
<style type="text/css">
html 
{
 overflow: auto;
 text-align: center;
}
 
html, body, div, iframe 
{
 margin: 0px; 
 padding: 0px; 
 height: 100%; 
 border: none;
}
 
iframe 
{
 display: block; 
 width: 100%; 
 border: none; 
 overflow-y: auto; 
 overflow-x: hidden;
}

form{
    margin-top: 50px;
}
form input, form input[type=submit]{
    padding: 5px;
}
.error{
    background: #ffcdc1;
}
</style>
</head>
<body>
<?php if(isset($_GET['url']) && strpos($_GET['url'], 'https') >= 0){ ?>
    <iframe id="tree" name="myiframe" src="<?php echo $_GET['url']; ?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" scrolling="auto"></iframe>
<?php } else { ?>
    <?php if(strpos($_GET['url'], 'https') == false){ ?>
        <p class="error">You need to include https in your link URL: <?php echo strpos($_GET['url'], 'https'); echo $_GET['url']; ?></p>
    <?php } ?>
<form action="" method="get">
    <input type="text" name="url" placeHolder="<?php echo (isset($_GET['url'])) ? $_GET['url'] : "https://"; ?>" />
    <input type="submit" value="Access"/>
</form>
<?php } ?>
</body>
</html>