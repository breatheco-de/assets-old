<html>
<head>
	<title>Video Player</title>
	<link rel="stylesheet" type="text/css" href="style.css?v2">
</head>
<body>
	<nav id="videomenu" class="dr-menu">
		<div class="dr-trigger">
			<a id="menu-title" class="dr-label">Topics Covered</a>
			<span class="dr-icon icon-list-numbered"></span>
		</div>
		<ul id="menu-items">
		</ul>
	</nav>
	<!-- 1. The <iframe> (and video player) will replace this <div> tag. -->
    <div id="player"></div>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="player.js?v2"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			<?php if(isset($_GET['v'])){ ?>
			videotutorial.initialize({
				'selector':'player',
				'menu':'dr-menu',
				'menu-selector':'menu-items',
				'timeline-url':'video/index/<?php echo $_GET['v']; ?>.json',
				'menu-title':'menu-title'
			});
			<?php } ?>
		});
	</script>
</body>
</html>