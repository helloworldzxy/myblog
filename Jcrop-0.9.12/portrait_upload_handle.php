<?php 


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{

  echo "<br><br><br>"."hello!"; //会执行


	$targ_w = $targ_h = 200;
	$jpeg_quality = 90;

	$src = 'demo_files/pool.jpg';
	$img_r = imagecreatefromjpeg($src);
	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x1'],$_POST['y1'],
	$targ_w,$targ_h,$_POST['w'],$_POST['h']);

  	//在新的网页中输出裁剪后的图片
	header('Content-type: image/jpeg');
  	imagejpeg($dst_r,null,$jpeg_quality);

	exit;
}



?>