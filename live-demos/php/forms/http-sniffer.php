<!DOCTYPE html>
<html>
<head>
	<title>HTTP Sniffer</title>
</head>
<body>
	<?php var_dump(getallheaders()); ?>

	<h1>The server received the following:</h1>
	<?php var_dump($_REQUEST); ?>
</body>
</html>