<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/base.css" rel="stylesheet" />
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	<script src="js/base.js" type="text/javascript"></script>
    <title>Image Tool</title>
  </head>
 
  <body>
 
    <div class="columnsContainer">
 
      <div class="panel panel-primary image_display_area">
        <div class="imageContainer row">
			
		</div>
		<a href="#" class="loadMore" onclick="loadMoreImages()">Load More</a>
      </div>
 
      <div class="panel panel-default upload_area">
		  <div class="panel-heading">
			  <h3 class="panel-title">Choose Image</h3>
		  </div>
		  <div class="panel-body">
				<form id="imageform" action="controller.php" enctype="multipart/form-data">
					<div class="form-group">
						<input autocomplete="off" class="form-control" type="text" name="caption" placeholder="Add text here" id="bottom_line" onkeyup="updateBottomLine(this)" />
					</div>
					<div class="preview">
						<img id="uploadPreview" style="width: 320px; height: 220px;" />
						<div class="strip"></div>
					</div>
					<div class="file_input">
						<div ondrop="drop(event)" ondragover="allowDrop(event)" id="drop">Drop file here.</div>
						<h4><span class="label label-default">OR</span> <br/></h4>
						<div class="form-group">
							<input id="uploadImage" type="file" name="image" onchange="PreviewImage();"/>
						</div>
					</div>
					<div class="form-group" style="overflow:hidden">
						<h4><span class="label label-default">Strip Color</span> <br/> </h4>
						<input class="color red" name="strip_color[red]" type="text" value="255" max=255 placeholder="R"/>
						<input class="color green" name="strip_color[green]" type="text" value="192" max=255 placeholder="G"/>
						<input class="color blue" name="strip_color[blue]" type="text" value="203" max=255 placeholder="B"/>
						<input class="form-control color_demo" style="width:40px;" type="text" readonly="readonly"/>
					</div>
					<div class="form-group" style="overflow:hidden">
						<h4><span class="label label-default">Font Color</span> <br/></h4>
						<input class="fcolor fred" name="font_color[red]" type="text" value="0" max=255 placeholder="R"/>
						<input class="fcolor fgreen" name="font_color[green]" type="text" value="0" max=255 placeholder="G"/>
						<input class="fcolor fblue" name="font_color[blue]" type="text" value="0" max=255 placeholder="B"/>
						<input class="form-control fcolor_demo" style="width:40px;" type="text" readonly="readonly"/>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-primary" name="save" value="Save"> <input id="reset" type="reset" value="Reset" class="btn btn-default" onclick="clearImage()">
					</div>
				</form>
			</div>
      </div> 
 
    </div>
 
  </body>
</html>