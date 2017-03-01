<?php 

	$photoName = $_GET['photoname'];
	echo $photoName."<br>";
	$albumName = $_GET['albumname'];
	echo $albumName."<br>";

	$file = "images/".$albumName."/".$photoName;
	echo $file."<br>";
	if (unlink($file)){
		echo ("Photo $file deleted successfully!");
	}else{

		echo ("Failed to delete $photoName!");
	}


?>