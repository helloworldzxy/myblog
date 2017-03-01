<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

	require_once('../config/connect.php');
	//把传递过来的信息入库,在入库之前对所有的信息进行校验。
	if(!(isset($_POST['title'])&&(!empty($_POST['title'])))){
		echo "<script>alert('标题不能为空');'</script>";
	}
	$title = $_POST['title'];
	$content = $_POST['content'];
	$description = $_POST['description'];
	//$dateline =  date("Y-m-d H:i:s", time());
	//$dateline = time();
	$insertsql = "insert into article(title, content, description, dateline) values('$title', '$content','$description', now())";
	if(mysql_query($insertsql)){
		echo "<script>alert('发布文章成功');window.location.href='index_m.php'</script>";
	}else{
		echo "<script>alert('发布失败');window.location.href='index_m.php'</script>";
	}
?>