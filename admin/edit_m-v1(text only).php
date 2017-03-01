<?php
    error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
    
    require_once("../config/connect.php");

    $login = false;
    session_start();

    if( (!empty($_SESSION['user'])) && ($_SESSION['user'] == 'admin') ) { //判断用户是否登录
        $login = true;
    }

    $id = $_GET['id'];
    //echo $id;
    //读取原文章信息
    $sql = "select * from article where id = $id";
    $query = mysql_query($sql); 
    $data = mysql_fetch_assoc($query);

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



<div class="container">
    <div class="header">
        <h1>BLOG</h1>
    </div>

    <div class="title">----I have a dream...</div>

    <div class="right">
        <div class="blog_entry">
            <div class="blog_title">编辑日志</div>
            <div class="blog_body">
                

                    <div class="blog_date"></div>
                    <table border="0">
                        <form method="POST" action="edit_handle_m.php">
                            <input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
                            <tr><td>日志标题</td></tr>
                            <tr><td><input type="text" name="title" size="50" value="<?php echo $data['title'] ?>"></td></tr>
                            <tr><td>日志简介</td></tr>
                            <tr><td><textarea name="description" cols="49" rows="5"><?php echo $data['description'] ?></textarea></td></tr>
                            <tr><td>日志内容</td></tr>
                            <tr><td><textarea name="content" cols="49" rows="10"><?php echo $data['content'] ?></textarea></td></tr>
                            <tr><td>创建于：<?php echo $data['dateline'] ?></td></tr>
                            <tr><td><input type="submit" value="提交"></td></tr>
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