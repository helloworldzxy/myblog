<?php
/**
 * Created by PhpStorm.
 * User: ZXY
 * Date: 2016/9/20
 * Time: 20:26
 */
error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

include '../config/auth.php';
session_start();

if( (isset($_POST['user'])) && (isset($_POST['pwd'])) ){
    $user = $_POST['user'];
    $pwd = $_POST['pwd'];

    //$pwd = md5($pwd);

    if( ($user != $AUTH['user']) || ($pwd != $AUTH['pwd']) ){
        echo "<strong><div  style='color:red'>用户名或密码错误！</div></strong>";
    }else{
        $_SESSION['user'] = $user;  //验证成功，设置session
        header("location: index_m.php?p=1");
    }
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
                <div class="blog_title">用户登录</div>
                <div class="blog_body">
                    <div class="blog_date"></div>
                    <table border="0">
                        <form method="POST" action="login_m.php">
                            <tr>
                                <td>用户名称：</td>
                                <td><input type="text" name="user" size="15"></td>
                            </tr>
                            <tr>
                                <td>用户密码：</td>
                                <td><input type="password" name="pwd" size="15"></td>
                            </tr>
                            <tr><td><input type="submit" value="登录"></td></tr>
                        </form>
                    </table>
                </div> <!--end of #blog_body-->
            </div> <!--end of blog_entry-->
        </div> <!--end of #left-->


        <div class="left">
            <div class="sidebar">
                <div class="menu_title">关于我</div>
                <div class="menu_body">每天进步一点点~</div>
            </div>
        </div> <!--end of #right-->

        

    </div> <!--end of #container-->

<div class="footer">CopyRight 2016</div>

</div> <!-- end of .blogbg -->

</body>
</html>
