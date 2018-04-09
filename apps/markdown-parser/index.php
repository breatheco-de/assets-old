<?php

if(!isset($_GET['path'])) die("Please specify the 'path': URL for the markdown file");

$flavor = 'github';
if(isset($_GET['skin'])) $skin = $_GET['skin'];

$readmeContent = file_get_contents($_GET['path']);
if(!$readmeContent) die("The markdown file could not be loaded:".$_GET['path']);

?>
<!DOCTYPE html>
<html>
	<!--
		Theme options: Markdown5</option>
		<option value="air">Air</option>
		<option value="modest">Modest</option>
		<option value="retro">Retro</option>
		<option value="github">Github</option>
	-->
    <head>
        <title>Readme Example</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/github-markdown-css/2.10.0/github-markdown.min.css" type="text/css" />
    </head>
    <body>
        <style type="text/css">
            img{max-height: 25px;}
            .markdown-body{ max-width: 800px; margin: 0 auto;}
        </style>
        <div class="markdown-body"></div>
        <script type="text/javascript">
            window.onload = function(){
                var converter = new showdown.Converter();
                converter.setFlavor('<?php echo $flavor; ?>');
                const html      = converter.makeHtml(`<?php echo $readmeContent?>`);
                document.querySelector('.markdown-body').innerHTML = html;
            };
        </script>
        <script type="text/javascript" src="https://cdn.rawgit.com/showdownjs/showdown/1.8.6/dist/showdown.min.js"></script>
    </body>
</html>