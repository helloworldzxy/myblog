<?php
	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
	
	require_once("../config/connect.php");

	$login = false;
    session_start();

    if( (!empty($_SESSION['user'])) && ($_SESSION['user'] == 'admin') ) { //判断用户是否登录
        $login = true;
    }

	$id = $_GET['id'];
	//读取原文章信息
    $sql = "select * from article where id = $id";
    $query = mysql_query($sql); 
    $data = mysql_fetch_assoc($query);
    /*echo "<br><br><br><br><br><br>";
    print_r($data);*/

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>基于mysql的简易BLOG</title>
</head>
<body>

	<div class="topbar">
	    <div class="topbar_content">
	        <span id="backToIndex">
	            <a href="index_m.php?p=1">返回首页</a>
	        </span>

	        <span id="user">
	            <?php
	                if($login){
	                    echo "<a href='logout_m.php'>退出</a>";
	                }else{
	                    echo "<a href='login_m.php'>登录</a>";
	                }
	            ?>
	        </span>
	        
	        <span id="username">
	            <?php
	                if($login){
	                    echo "Hello, ".$_SESSION['user'];
	                }
	            ?>
	        </span>
	    </div>
	</div>
	
	
	<div class="detail_head">
		<h1 class="detail_head_content">
			<?php
				echo $_SESSION['user']."的博客"; 
			?>
		</h1>
		
	</div>
	
	<div class="detail_blog_body">	

		<div class="detail_blog_title">
			<?php
				echo $data['title'];
			?>
		</div>
		<div class="detail_blog_date">
			<?php
				echo $data['dateline'];
			?>
		</div>
		<div class="detail_blog_description">
			文章简介：<br>
			<?php
				echo $data['description'];
			?>
		</div>
		<div class="detail_blog_content">
			文章内容：<br>
			<?php
				echo $data['content'];
			?>
		</div>
	</div>

	<div class="detail_footer">
		<div class="detail_footer_content">
			Copyright @2016 
		</div>
		
	</div>
	
	

</body>
</html>