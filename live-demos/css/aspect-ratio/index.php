<!DOCTYPE html>
<html>
    <head>
        <title>Aspect Ratio</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css?v2" type="text/css" /> 
    </head>
    <body>
        <div class="container">
            <h1>Aspect Ratio Tester</h1>
            <h3>Test if an image or box is good for a specific proportion!</h3>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<h3>A) Specify a width and height <small>and it will tell you the aspect ratio for those sizes:</small></h3>
						<div class="row">
							<div class="col-xs-12">
								<label for="pixel_width">Pixels width</label>
								<input class="form-control" type="number" placeholder="Type how many pixels" value="50" id="pixel_width">
							</div>
							<div class="col-xs-12">
								<label for="pixel_height">Pixels height</label>
								<input class="form-control" type="number" placeholder="Type how many pixels" value="50" id="pixel_height">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<h3>B) Or input an image <small>and it will tell you the aspect ratio for that image size:</small></h3>
						<div class="row">
							<div class="col-md-12">
								<form id="upload-form" action="upload.php" method="post" enctype="multipart/form-data">
			                        <div class="form-group">
									  <label for="pixel_width">Enter the URL</label>
									  <input class="form-control" type="url" placeholder="Enter your image URL" id="image-url">
			                          <span class="glyphicon form-control-feedback hidden" aria-hidden="true"></span>
			                        </div>
			                        <div class="form-group">
		            					<form id="aspectratio">
									      	<label for="pixel_width">Or upload the image</label>
											<div class="input-group">
						                        <input class="form-control" type="file" placeholder="Upload an image" id="image-upload">
												<span class="input-group-btn">
													<button class="btn btn-default" type="button" id="upload-button">Upload!</button>
												</span>
											</div>
										</form>
			                        </div>
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<label for="ratio_width">Calculated aspect ratio width</label>
						<input class="form-control" type="number" value="0" id="ratio_width" readonly>
					</div>
					<div class="col-md-6">
						<label for="ratio_height">Calculated aspect ratio height</label>
						<input class="form-control" type="number" value="0" id="ratio_height" readonly>
					</div>
				</div>
				<hr>
				<h3>This example box container <small>tells you how your box or image will fit in a parent container <?php echo (isset($_REQUEST['ratio'])) ? 'with a '.$_REQUEST['ratio'].' ratio.' : "with a new ratio that you can specify"; ?></small></h3>
				<div class="row">
					<div class="col-md-12">
					    <select class="form-control" id="example-ratios">
					        <option>Select a size</option>
					    </select>
					    <div>
					        <div id="example-container"><div id="example-child">s</div></div>
					    </div>
					</div>
				</div>
			</form>
        </div>
        <script type="text/javascript" src="script.js"></script>
        <script type="text/javascript">
            init({
                "ratio": <?php echo (isset($_REQUEST['ratio'])) ? '"'.$_REQUEST['ratio'].'"' : "false"; ?>
            });
        </script>
    </body>
</html>