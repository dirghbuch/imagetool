<?php
require "model.php"; // loading model

// form submit for image upload

if(isset($_POST["caption"]))
{
	$image_name= $_FILES['image']['name'];
	$image_server_name=md5(time())."_".$image_name; // in order to avoid overwrite
	$image_caption=$_POST['caption'];
	$font_color=$_POST['font_color'];
	$strip_color=$_POST['strip_color'];
	$status=0;
	if(move_uploaded_file ( $_FILES['image']['tmp_name'] , "images/".$image_server_name ))
	{	
		resizeImage($image_server_name,$image_caption,$font_color,$strip_color);
		saveImage($image_name,$image_server_name,$image_caption); // saving image
		$status=1;
	}
	echo json_encode(array("status"=>$status));
	exit();
}

//image load event

if(isset($_GET['from']))
{
	$images=getImageList($_GET['from'],$_GET['limit']);
	if(count($images)>0)
	{
		echo json_encode(array("status"=>1,"images"=>$images));
	}
	else
	{
		echo json_encode(array("status"=>0));
	}
}

function resizeImage($image,$text,$font_color,$strip_color)
{
	$newWidth=320;
	$newHeight=240;
	$caption_height=20;
	$new_image = imagecreatetruecolor($newWidth, $newHeight);
	$image_info = getimagesize("images/".$image);
	$image_type=$image_info[2];
	$filename="images/thumb/".$image;
	
	// create gd image
	if( $image_type == IMAGETYPE_JPEG )
	{   $image = imagecreatefromjpeg("images/".$image); } 
	elseif( $image_type == IMAGETYPE_GIF )
	{   $image = imagecreatefromgif("images/".$image); } 
	elseif( $image_type == IMAGETYPE_PNG ) 
	{   $image = imagecreatefrompng("images/".$image); }
	
	// copy resized image
	imagecopyresized($new_image, $image, 0, 0, 0, 0, $newWidth, $newHeight-$caption_height, $image_info[0], $image_info[1]);
	
	//create caption box
	$caption_box= imagecreatetruecolor($newWidth, 20);
	$color = imagecolorallocate($caption_box, $strip_color['red'],$strip_color['green'],$strip_color['blue']); 
	$fontColor=imagecolorallocate($caption_box, $font_color['red'],$font_color['green'],$font_color['blue']); 
	
	//Fill ractagle with pink color
	imagefilledrectangle ($caption_box,0,0,$newWidth,20, $color);
	
	//Writing text 
	imagettftext($caption_box, 12, 0, 15, 15, $fontColor, "fonts.ttf", $text);
	
	//Merge with original Image	
	imagecopymerge($new_image,$caption_box,0,$newHeight-$caption_height,0,0,$newWidth,$caption_height,100);
	
	// save merged and resized image
	
	if( $image_type == IMAGETYPE_JPEG )
	{ imagejpeg($new_image,$filename,75); } 
	elseif( $image_type == IMAGETYPE_GIF )
	{   imagegif($new_image,$filename); } 
	elseif( $image_type == IMAGETYPE_PNG ) 
	{   imagepng($new_image,$filename); } 
}
?>