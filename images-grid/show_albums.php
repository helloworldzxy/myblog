<?php 
    error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

    require_once("../config/connect.php");

    $login = false;
    session_start();

    if( (!empty($_SESSION['user'])) && ($_SESSION['user'] == 'admin') ) { //判断用户是否登录
        $login = true;
    }

    //获取某一目录下的所有文件夹/文件的名称
    function listDirOrFile($dir, $isDir){
        if(is_dir($dir)){
            if($dh = opendir($dir)){
                $dirList = array();
                $fileList = array();
                $i = 0;
                $j = 0;
                while(($file = readdir($dh)) !== false){
                    if((is_dir($dir."/".$file)) && $file != "." && $file != ".." ){ //如果是目录
                        //echo "<div id='img-$i' style='display:none;'>文件名：</div>".$file."<br><br>";
                        $dirList[$i] = $file;
                        $i++;
                    }else{	//如果是文件
                        if($file != "." && $file != ".."){
                            //echo $file."<br>";
                            $fileList[$j] = $file;
                            $j++;
                        }
                    }
                }	//end of while
                closedir($dh);
                //print_r($list);
                if($isDir){
                    return $dirList;
                }else{
                    return $fileList;
                }

            }
        }
    }

    //开始运行
    /*$url4albums = 'D:/XAMPP/htdocs/myblog/zxxImgUpload/images';
    $albumList = listDirOrFile($url4albums, 1); //[20161027, 20161028, ...]
    $url4files = array();	//该数组存放相册目录名称
    $images = array(); //二维关联数组：key为"....../20161027" value为另一个数组，为该key对应目录下遍历的所有文件
    for($i = 0; $i < count($albumList); $i++){
        $url4files[$i] = $url4albums."/".$albumList[$i];
        $images[$url4files[$i]] = listDirOrFile($url4files[$i], 0);
    }*/

    //通过数组的方式获取所有相册名称
    $sql = "SELECT * FROM album";
    $query = mysql_query($sql); 
    while($row = mysql_fetch_assoc($query)){
        $data[] = $row;
    }
    /*echo "<br><br><br>";
    print_r($data);
*/

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Images grid</title>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" href="../style.css">

        <script src="http://libs.baidu.com/jquery/1.11.3/jquery.min.js"></script>

        <!--for images-grid-->
        <script src="src/images-grid.js"></script>
        <link rel="stylesheet" href="src/images-grid.css">

        <style>
            body {
                font-family: sans-serif;
            }
            p {
                text-align: center;
                font-weight: bold;
            }
        </style>

    </head>
    <body>
        <div class="topbar" style="z-index: 1;">
            <div class="topbar_content">
                <span id="backToIndex">
                    <a href="../admin/index_m.php?p=1">返回首页</a>
                    <a href="../zxxImgUpload/imgUploadDemo.php">发照片</a>
                    <a href="../zxxImgUpload/imgManage.php">管理相册和照片</a>
                </span>

                <span id="user">
                    <?php
                    if($login){
                        echo "<a href='../admin/logout_m.php'>退出</a>";
                    }else{
                        echo "<a href='../admin/login_m.php'>登录</a>";
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

        <script>
            function getPhotosByAlbumName(album,from){  //不应该用js读取服务器下的文件 应该由服务器读取后传给前端处理展示
                //var albumUrl = '../zxxImgUpload/images/' + album;
                var imageUrlArr = new Array();
                $.ajax({
                    async: false,
                    type: "POST",
                    url: "readFile.php",
                    data: "album=" + album + "&from=" + from,
                    success: function(data){
                        var data = JSON.parse(data);
                        //console.log(data); //object{success: "0", error_code: "查询成功", dataArr: array[5]}
                        if (data.success == 0) {
                            imageUrlArr = data.dataArr;
                            //console.log(imageUrlArr); //正常 [5个image url]
                        }
                    }
                });
                return imageUrlArr;
            }
        </script>

        <div id="container" style="margin: 30px auto 0 auto;">
            <?php  
                foreach($data as $value){
                    /*print_r($value);
                    echo "<br>";*/
                    $albumid = $value['id'];
                    $albumname = $value['name'];
                    $albumdescription = $value['description'];
                    echo "<p>".$albumname."</p>";
                    echo "<div align='center' id='AlbumDescr-".$albumid."'>".$albumdescription."</div>";
                    echo "<div id='Album-".$albumid."'></div>";
                    echo "<script>"."$('#Album-".$albumid."').imagesGrid({
                        images: getPhotosByAlbumName('$albumname', 'show_albums.php'),
                        align: true
                });
                /*console.log('-----------------from show_albums.php: --------------------');
                console.log(getPhotosByAlbumName('$albumname', 'show_albums.php'));*/
                </script>";
                };
            ?>
        </div>

    </body>
</html>
