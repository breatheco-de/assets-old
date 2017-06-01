<?php
	function printAttribute($parameter){
		if(is_array($parameter))
		{
			$cont = 0;
			echo "[";
			foreach($parameter as $p) echo ($cont++==(count($parameter)-1)) ? $p : $p.', ';
			echo "]";
		}
		else
		{
			if(!empty($parameter)) echo substr($parameter,0,50); 
			if(strlen($parameter)>45) echo "...";
			else "<span class='empty'>empty</span>";
		} 
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>HTTP Sniffer</title>
	<link rel="stylesheet" type="text/css" href="style.css?v07">
</head>
<body>
	
	<div class="header">
		<h1>The server received the following HTTP package:</h1>
		<p>Send me an HTTP request and I will show you its content!</p>
		<a href="#" class="button" onClick="toggleAnimation();">Show Me</a>
	</div>
	
	<div class="email">
	  <div class="envelope">
	    <div class="back paper"></div>
	    <div class="note">
	    	<ul class="requestcontent">
	    		<li>
	    			<span class="requestproperty">Request Type</span>
	    			<?php echo $_SERVER['REQUEST_METHOD']; ?>
	    		</li>	
	    	<?php foreach($_REQUEST as $key => $val){ ?>
	    		<li><span class="requestproperty"><?php echo $key; ?></span> <?php printAttribute($val); ?></li>
	    	<?php } ?>
	    	</ul>
	    </div>
	    <div class="front paper">
	    <?php
	    $envelope = getallheaders();
	    ?>
	    	<ul class="envelopecontent">
	    		<li>
	    			<span class="contentproperty">Request Type</span>
	    			<?php echo $_SERVER['REQUEST_METHOD']; ?>
	    		</li>	
	    	<?php foreach($envelope as $key => $val){ ?>
	    		<li><span class="contentproperty"><?php echo $key; ?></span> <?php  ?></li>
	    	<?php } ?>
	    	</ul>
	    </div>
	  </div>
	</div>
<script type="text/javascript">
	function toggleAnimation()
	{
		var btns = document.querySelectorAll(".back");
		var notes = document.querySelectorAll(".note");
		for(var i =0; i<btns.length;i++)
		{
			toggleClass(btns[i],'animate');
			toggleClass(notes[i],'animate');
		}

	}

	function toggleClass(elm,className)
	{
		if(!elm.classList.contains(className))
		{
		  elm.classList.add(className);
		}
		else
		{
		  elm.classList.remove(className);
		}
	}
</script>
</body>
</html>