<?php
/**
 * Created by PhpStorm.
 * User: ZXY
 * Date: 2016/9/22
 * Time: 20:36
 */
error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

session_start();
$info = '';

if(isset($_SESSION['user'])){
    $_SESSION['user'] = '';
    $msg = '您已经成功退出，<a href="index_m.php?p=1">返回首页</a>';
}else{
    $msg = '您未曾登录或已经超时退出，<a href="index_m.php?p=1">返回首页</a>';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>基于mysql的简易BLOG</title>
</head>
<body>


<div class="blogbg">

<div class="container">
    <div class="header">
        <h1>BLOG</h1>
    </div>

    <div class="title">----I have a dream...</div>

    <div class="right">
        <div class="blog_entry">
            <div class="blog_title">退出登录</div>
            <div class="blog_body">
                <?php
                    echo $msg;
                ?>
            </div> <!--end of #blog_body-->
        </div> <!--end of blog_entry-->
    </div> <!--end of #left-->


    <div class="left">
        <div class="sidebar">
            <div class="menu_title">关于我</div>
            <div class="menu_body">每天进步一点点~</div> <!--end of #menu_body-->
        </div> <!--end of #sclassebar-->
    </div> <!--end of #right-->

    
</div> <!--end of #container-->

<div class="footer">CopyRight 2016</div>

</div> <!-- end of .blogbg -->


</body>
</html>
