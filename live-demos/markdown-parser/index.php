<?php
require "vendor/autoload.php";

if(!isset($_GET['path'])) die("Please specify the 'path': URL for the markdown file");

$skin = 'markdown5';
if(isset($_GET['skin'])) $skin = $_GET['skin'];

$readmeContent = file_get_contents($_GET['path']);
if(!$readmeContent) die("The markdown file could not be loaded:".$_GET['path']);

$Parsedown = new Parsedown();

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body onload="onLoadFunction();">
<div style="position: absolute; top:0; right:0;">
	<select id="theme" onChange="onThemeChange(this);">
		<option value="markdown5">Markdown5</option>
		<option value="air">Air</option>
		<option value="modest">Modest</option>
		<option value="retro">Retro</option>
		<option value="splendor">Splendor</option>
	</select>
</div>
<?php echo $Parsedown->text($readmeContent); ?>
<script type="text/javascript">
var theme = '<?php echo $skin; ?>';  // you could encode the css path itself to generate id..
function changeCSS(){
	var link = document.getElementById('css-link');
	if (!link)
	{
	    var head  = document.getElementsByTagName('head')[0];
	    var link  = document.createElement('link');
	    link.id   = 'css-link';
	    link.rel  = 'stylesheet';
	    link.type = 'text/css';
	    link.href = 'themes/'+theme+'.css';
	    link.media = 'all';
	    head.appendChild(link);
	}
	else
	{
		link.href = 'themes/'+theme+'.css';
	}
	return true;
}

function onThemeChange(elem)
{
	console.log(elem.value);
	theme = elem.value;
	changeCSS();
	return false;
}

function onLoadFunction(){
	var selector = document.querySelector('#theme');
	selector.value = theme;
}

changeCSS(theme);
</script>
</body>
</html>