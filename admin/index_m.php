<?php
/**
 * Created by PhpStorm.
 * User: ZXY
 * Date: 2016/9/20
 * Time: 20:41
 */
    error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

    $pageSize = 8;
    $showPage = 5;

    require_once('../config/connect.php');

    $login = false;
    session_start();

    if( (!empty($_SESSION['user'])) && ($_SESSION['user'] == 'admin') ) { //判断用户是否登录
        $login = true;
    }

    /*1、传入页码*/
    /*echo "<br><br><br>";
    echo $_GET['p'];
    echo "<br>";*/
    if($_GET['p'] == null){
        $page = 1;
    }else{
        $page = $_GET['p'];
    }
    
    /*2、根据页码 取出数据 php+mysql*/
    $a = ($page - 1) * $pageSize;
    $b = $page * $pageSize - 1;
    //编写sql语句获取分页数据 select * from article limit a, $pageSize
    $sql = "SELECT * FROM article LIMIT ".$a.",{$pageSize}";
    $result = mysql_query($sql);
    //echo "<br><br><br><br>";
    while($row = mysql_fetch_assoc($result)){
        //echo $row['id'].'-'.$row['title'].'<br>';
        $data[] = $row;
    }
    mysql_free_result($result);

    //获取数据总数
    $total_sql = "SELECT COUNT(*) FROM article";
    $total_result = mysql_fetch_array(mysql_query($total_sql));
    $total = $total_result[0];
    //echo "总条数: ".$total."<br>";
    //计算总页数
    $total_pages = ceil($total / $pageSize);
    //$total_pages = 1;   //````````````````````````````````
    //计算偏移量
    $pageoffset = ($showPage - 1) / 2;
    //初始化数据
    $start = 1;     
    $end = $total_pages;

    /*3、显示数据 分页条*/
    $page_banner = "<div class='page'>";
    if($page > 1){
        $page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=1'>首页</a>";
        $page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($page - 1)."'><上一页</a>";
    }else{
        $page_banner .= "<span class='page_disable'>首页</span>";
        $page_banner .= "<span class='page_disable'><上一页</span>";
    }

    //前...的显示
    if($page > $pageoffset + 1){    //其实就是看页码1左移几个单位会移出去 所以与pageoffset有关
        $page_banner .= "...";
    }

    if($total_pages > $showPage){
        if($page > $pageoffset){
            $start = $page - $pageoffset;
            $end = $total_pages > $page + $pageoffset ? $page + $pageoffset : $total_pages;
        }else{
            $start = 1;
            $end = $total_pages > $showPage ? $showPage : $total_pages; //取值$showPage
        }
    }
    // if($page + $pageoffset > $total_pages ){
    //     // $start = $start - ($page + $pageoffset - $end); //这样有可能会出现0或负数开头！！！
    //     $start = $end - 2 * $pageoffset;
    // }

    for($i = $start; $i <= $end; $i++){
        if($page == $i){
            $page_banner .= "<span class='current_page'><a href='".$_SERVER['PHP_SELF']."?p=".$i."'>{$i}</a></span>";
        }else{
            $page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".$i."'>{$i}</a>";
        }
       
    }

    //后...的显示
    if($total_pages > $showPage && $total_pages > $page + $pageoffset){
        $page_banner .= "...";
    }


    if($page < $total_pages){
        $page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p=".($page + 1)."'>下一页></a>";
        $page_banner .= "<a href='".$_SERVER['PHP_SELF']."?p={$total_pages}'>尾页</a>";
    }else{
        $page_banner .= "<span class='page_disable'>下一页</span>";
        $page_banner .= "<span class='page_disable'>尾页</span>";
    }
    
    
    $page_banner .= "共{$total_pages}页，";
    //页面跳转
    $page_banner .= "<form action='index_m.php' method='GET'>";
    $page_banner .= "到第<input type='text' name='p' size='2'/>页";
    $page_banner .= "<input type='submit' value='确定' />";
    $page_banner .= "</form></div>";


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>基于mysql的简易BLOG</title>
    <!-- for jQuery-nav-star -->
    <script src="../jquery-3.1.1.min.js"></script>
    <script type="text/javascript" src='../jquery-nav-star/js/style.js'></script>
    <link rel="stylesheet" href="../jquery-nav-star/css/style.css">
    <!-- for sNav -->
    <script src="../sNav-master/js/sNav.js"></script>
    <link rel="stylesheet" type="text/css" href="../sNav-master/css/sNav.css">

    

</head>
<body>

<!-- <div class="nav">
    <div class="nav_li">
        <ul>
            <li><a href="">首页</a></li>
            <li><a href="">日志</a></li>
            <li><a href="">相册</a></li>
            <li><a href="">说说</a></li>
            <li><a href="">音乐</a></li>
        </ul>
    </div>
</div> -->

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
        
        <!-- jquery-nav-star -->
        <!-- <div class="nav">
            <div class="nav_li">
                <ul>
                    <li><a href="">首页</a></li>
                    <li><a href="index_m.php?p=1">日志</a></li>
                    <li><a href="../images-grid/show_albums.php">相册</a></li>
                </ul>
            </div>
        </div> -->

        <!-- sNav -->
        <ul class="example1">
            <a href="#"> <li>HOME</li> </a>
            <a href="#"> <li>ARTICLE</li> </a>
            <a href="../images-grid/show_albums.php"> <li>PHOTOGRAPHY</li> </a>
        </ul>

        <script type="text/javascript">
            var example1 = new sNav('example1');
            example1.setText({
                "0":"Be Best",
                "1":"Reading",
                "2":"Salon"
            });

        </script>


        <div>
            <?php
                if($login){
            ?>
                    <div class="add-div">
                        <span>
                            <a href='add_m.php'>发博文</a>
                        </span>  
                        <span>
                            <a href='../zxxImgUpload/imgUploadDemo.php'>发照片</a>
                        </span>   
                    </div>
                   
            <?php
                }
            ?>
        </div>
        
        <div class="right">
            <?php
                if(empty($data)){   
                    echo "当前没有文章";
                }else{
                    foreach($data as $value){
            ?>

            <div class="blog_entry">
                <div class="blog_title"><?php echo $value['title']?></div>
                <div class="blog_body">
                    <div class="blog_date"><?php echo $value['dateline']?></div>
                    <div class="blog_description">
                        简介：<?php echo $value['description']?>
                    </div>
                    <div class="blog_option">
                        <span id="edit"><a href="show_detail_m.php?id=<?php echo $value['id'] ?>">查看全文</a></span>&nbsp;
                        <?php
                            if($login){
                                echo '<span id="edit"><a href="edit_m.php?id='.$value['id'].'">编辑</a></span>&nbsp;';
                                echo '<span id="edit"><a href="delete_handle_m.php?id='.$value['id'].'">删除</a></span>&nbsp;';
                            }
                        ?>
                    </div>
                </div> <!--end of .blog_body-->
            </div> <!--end of .blog_entry-->
            <?php
                } //end of foreach
            }//end of else
            ?>

            <div class="page">
            <?php
               echo $page_banner;
            ?>

            </div>


        </div> <!--end of .right-->

        <div class="left">
            <div class="sidebar">
                <div class="menu_title">Fighting</div>
                <div class="menu_body">每天进步一点点~</div>
                <br/><br/>
            </div>
            <div class="sidebar">
                <div class="menu_title">头像</div>
                <div class="menu_body">我不管我最美~</div>
                <img id="portraitImg" height='100' width='100' src="../Jcrop-0.9.12/portrait/me.jpg"><div style='height=20px;'>
                    <a href="../Jcrop-0.9.12/changePortrait.html">更换头像</a>
                </div>
            </div>



        </div> <!--end of .left-->

    

    </div> <!--end of .container-->

    <div class="footer">CopyRight 2016</div>

</div> <!-- end of .blogbg -->

</body>
</html>
