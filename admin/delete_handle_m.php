<?php 
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	
	require_once("../config/connect.php");

	$id = $_GET['id'];
	$query = "delete from article where id = $id";
	if(mysql_query($query)){
		echo "<script>alert('删除文章成功');window.location.href='index_m.php?p=1';</script>";
	}else{
		//echo "<script>alert('删除文章失败');window.location.href='index_m.php';</script>";
		echo mysql_error();
	}

?>