<?php 
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

	require_once('../config/connect.php');

	$id = $_POST['id'];
	$title = $_POST['title'];
	$description = $_POST['description'];
	$content = $_POST['content'];
	$updatesql = "UPDATE article SET title='$title', description='$description', content='$content', dateline=now() WHERE id=$id";
	if(mysql_query($updatesql)){
		echo "<script>alert('修改文章成功');window.location.href='index_m.php';</script>";
	}else{
		//echo "<script>alert('修改文章失败');window.location.href='index_m.php';</script>";
		echo mysql_error();
	}
?>