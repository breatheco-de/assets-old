<!DOCTYPE html>
<html>
<head>
	<title>Breathe Code Sound Gallery</title>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link href="../css/dropzone.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<div class="container-fluid">
		<h1>Create User</h1>
		<form action="#" method="post" action="upload.php" class="dropzone">
			<fieldset>
				<legend>Song Name</legend>
				<label for="firstname">First:</label>
				<input type="Text" name="firstname" value="<?php echo $user["name"]; ?>" placeholder="Your Name">
			</fieldset>
			<fieldset>
				<legend>File Name</legend>
				<label for="lastname">Email:</label>
				<input type="Text" name="email" value="<?php echo $user["email"]; ?>" placeholder="username@domain.com">
			</fieldset>
			<fieldset>
				<legend>Role</legend>
				<label for="gender">Admin:</label>
				<input type="radio" name="role" value="admin" <?php if($user["role"]=='admin') echo 'checked="checked"'; ?>>
				<label for="gender">Subscriber:</label>
				<input type="radio" name="role" value="subscriber" <?php if($user["role"]=='subscriber') echo 'checked="checked"'; ?>>
			</fieldset>
			<input type="hidden" name="id" value="<?php echo $user["id"]; ?>">
			<input type='submit' value='SUBSCRIBE' />
		</form>
	</div>
	<script src="../dropzone.min.js"></script>
</body>
</html>