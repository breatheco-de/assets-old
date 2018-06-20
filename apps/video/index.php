<html>
<head>
	<title>Video Player</title>
	<link rel="stylesheet" href="assets/bootstrap4.min.css" type="text/css" />
	<link rel="stylesheet" type="text/css" href="style.css?v2">
</head>
<body>
	<?php if(!isset($_GET['v'])){ ?>
		<div class="alert alert-danger">You need to specify what tutorial you want to see using ?v=[vtutorial_slug]</div>
	<?php } else { ?>
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
	<script src="./assets/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="player.js?v2"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			<?php if(isset($_GET['v'])){
			$autoplay = 'false';
			if(isset($_GET['autoplay'])) $autoplay = ($_GET['autoplay'] == 'true') ? 'true' : 'false';
			?>
			videotutorial.initialize({
				'selector':'player',
				'autoplay': <?php echo $autoplay; ?>,
				'menu':'dr-menu',
				'menu-selector':'menu-items',
				'timeline-url': '<?php echo $_GET['v']; ?>',
				'menu-title':'menu-title'
			});
			<?php } ?>
		});
	</script>
	<?php } ?>
</body>
</html>