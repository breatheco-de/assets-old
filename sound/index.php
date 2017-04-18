<!DOCTYPE html>
<html>
<head>
	<title>Breathe Code Sound Gallery</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="dropzone.css?1" type="text/css" rel="stylesheet" />
	<link href="styles.css?1" type="text/css" rel="stylesheet" />
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
	<div class="container-fluid">
		<h1 id="titleForm">Upload Songs...</h1>
		<form id="theForm" action="upload.php" method="post">
			<fieldset>
				<legend>Song Name</legend>
				<input id="songName" type="Text" name="song-name" placeholder="File Name">
			</fieldset>
			<fieldset>
				<legend>Song Type</legend>
				<select name="song-type">
					<option value="cartoons">All Cartoon Songs</option>
					<option value="mario">Mario Bross Songs</option>
					<option value="videogame">Other Video Games Songs</option>
					<option value="old">Old Classic Songs</option>
					<option value="trendy">Trendy Songs (only last 30 days)</option>
					<option value="other">Other Songs</option>
				</select>
			</fieldset>
			<fieldset id="captcha">
				<legend>Are you a human?</legend>
				<div class="g-recaptcha" data-sitekey="6LfWah0UAAAAAF2cJmOejMBnE9e86PM4Ys36QJvm"></div>
			</fieldset>
			<div class="dz-default dz-message">Drop files here</div>
		</form>
		<button id="submitForm" class="btn btn-primary form-control">Sumit</button>
	</div>
	<div id="trademark">
		<img src="http://assets.breatheco.de/img/logo.png">
		<p>All rights reserved, Breathe Code @ 2017</p>
		<p>www.breatheco.de</p>
	</div>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="./dropzone.js?1"></script>
	<script type="text/javascript">
		Dropzone.autoDiscover = false;
		$(document).ready(function(){
			var dropzone = new Dropzone("#theForm", {
			  maxFilesize: 4, // MB
			  addRemoveLinks: true,
			  autoProcessQueue: false,
			  acceptedFiles: ".mp3,.wav",
			  renameFilename: cleanFilename,
			  maxFiles: 1
			});

			$('#submitForm').click(function(){           
				$('#theForm').hide();
			    $('#titleForm').html("Loadding...");
			  dropzone.processQueue();
			});

			dropzone.on("addedfile", function(file) {
			    /* Maybe display some more file information on your page */
			    alert('file added successfully');
				$('#theForm').show();
			    $('#titleForm').html("Upload Songs...");
			 });
		});

		var cleanFilename = function (name) {
			var songName = $('#songName').val();
			if(!songName || songName==='') songName = name;
		   return songName.toLowerCase().replace(/[^\w]/gi, '');
		};
	</script>
</body>
</html>