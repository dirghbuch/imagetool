<?php
function connect_db()
{
	try{
		$db= new PDO('mysql:host=DB_HOST;dbname=DB_NAME', "DB_USER", "DB_PASS");
	}
	catch(Exception $e){die("Failed to connect database");}
	return $db;
}
function saveImage($image_name,$image_server_name,$image_caption,$caption_color="pink")
{
	$db=connect_db();
	$sql = "INSERT INTO images (image_name,image_server_name,image_caption,caption_color) VALUES (:image_name,:image_server_name,:image_caption,:caption_color)";
	$q = $db->prepare($sql);
	$q->execute(array(':image_name'=>$image_name,
					  ':image_server_name'=>$image_server_name,
					  ':image_caption'=>$image_caption,
					  ':caption_color'=>$caption_color));
}
function getImageList($from,$count)
{
	$sql = "SELECT * FROM images ORDER BY image_id DESC limit $from,$count";
	$db=connect_db();
    $images=array();
	foreach($db->query($sql) as $row)
	{
		$images[]=$row;
	}
	return $images;	
}
