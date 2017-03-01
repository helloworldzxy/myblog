<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/7
 * Time: 10:24
 */

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

    <title>管理相册和照片</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="../jquery-3.1.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <!--Bootstrap CSS-->
    <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Bootstrap JS-->
    <script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>



    <style>
        body {
            font-family: sans-serif;
        }
        p {
            text-align: center;
            font-weight: bold;
        }
    </style>
    <script>
    function reallyWantToDel(albumName){
        //console.log(albumName.toString());
        if(confirm("确定删除吗")){
            location.href="deleteAlbum.php?dirname=" + albumName;
        }else{
            location.reload();
        }
    }

    function getOldAlbumInfo(albumName,albumDescr){
        $("#oldDirname").val(albumName);
        $("#newDirname").val(albumName);
        $("#editDescr").val(albumDescr);
    }

    function getPhotosByAlbumName(album,from){  //其实不应该用js读取服务器下的文件 应该由服务器传给前端读取
        //var albumUrl = '../zxxImgUpload/images/' + album;
        var imageUrlArr = new Array();
        $.ajax({
            async: false,
            type: "POST",
            url: "../images-grid/readFile.php",
            data: "album=" + album + "&from=" + from,
            success: function(data){
                var data = JSON.parse(data);
                //console.log(data); //object{success: "0", error_code: "查询成功", dataArr: array[5]}
                if (data.success == 0) {
                    imageUrlArr = data.dataArr;
                    //console.log(imageUrlArr); //正常 [5个image url]
                }}

                    });
        return imageUrlArr;
    }


    </script>

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

    <div id="container" style="margin: 30px auto 0 auto;">
        <?php 
            foreach($data as $value){ //对每一个相册
                $num = 0;
                /*print_r($value);
                echo "<br>";*/
                $albumid = $value['id'];
                $albumname = $value['name'];
                //对相册名称的空格等的处理
                if(strpos($albumname, ' ') > 0){ //如果相册名称中包含空格
                    $albumname = str_replace(' ', '_', $albumname);
                }
                $albumdescr = $value['description'];
                echo "<div>相册信息<a href='#editAlbumInfoModal' data-toggle='modal' onclick='getOldAlbumInfo(\"".$value['name']."\",\"".$albumdescr."\")'>修改</a>
                    <a id='albumDel-".$albumname."'>删除相册</a>    
                      </div>";
                echo "<script>
                        var albumDelNode = document.getElementById('albumDel-".$albumname."');
                        //console.log(albumDelNode);

                        var albumName = '".$albumname."';
                        //console.log(albumName);

                        albumDelNode.onclick = function(){
                            var con = confirm('确定要删除相册及其中所有照片吗？');
                            if(con == true){
                                window.location.href = 'deleteAlbum.php?dirname=' + albumName;
                            }
                        }
                      </script>";
                echo "<div><span>相册名称：</span><span id='albumname-".$albumid."'>".$albumname."</span></div>";
                echo "<div><span>相册描述：</span><span id='albumdescr-'".$albumdescr."'>".$albumdescr."</span></div>";
                echo "<div id='photos-for-".$albumname."'></div>";
                echo "<script>
                        var photosArr = getPhotosByAlbumName('".$value['name']."', 'imgManage.php');
                        for(var i = 0; i < photosArr.length; i++){
                            //相片盒子div
                            var photoDiv = document.createElement('div');
                            photoDiv.id = "."'".$albumid."-photo-' + i;
                            photoDiv.style.display = 'inline-block';
                            photoDiv.style.margin = '10px';
                            
                            //相片img
                            var imgNode = document.createElement('img');
                            imgNode.src = photosArr[i]; //这里中文目录读取照片路径时有问题
                            imgNode.height = '300';
                            imgNode.width = '300';

                            //删除照片的按钮链接a的包裹盒子
                            var imgDelDiv = document.createElement('div');
                            imgDelDiv.align = 'center';
                            imgDelDiv.style.margin = '5px';


                            //照片删除按钮
                            var imgDel = document.createElement('a');
                            var photoName = photosArr[i].substr(photosArr[i].lastIndexOf('/') + 1);
                            var albumName = '".$albumname."';
                            
                            //imgDel.href = 'deletePhoto.php?photoname=' + photoName
                             + '&albumname=' + albumName;

                            imgDel.id = '".$albumname."-imgDel-' + i;

                            $('#photos-for-".$albumname."').append(photoDiv); //注意添加子节点时原生js和jQuery不一样： js-appendChild() jQuery-append()
                            //两种方法都可以，但是要保证父元素在接收子节点前已经被添加到了DOM中！！
                            //document.getElementById(photoDiv.id).appendChild(imgNode); //原生js
                            $('#".$albumid."-photo-' + i).append(imgNode); //jQuery

                            $('#".$albumid."-photo-' + i).append(imgDelDiv);
                            imgDelDiv.append(imgDel);
                            $('#".$albumname."-imgDel-' + i).text('删除照片');
                            imgDel.onclick = function confirmDel(){
                                var con = confirm('确定要删除该照片吗？');
                                if(con == true){
                                    window.location.href = 'deletePhoto.php?photoname=' + photoName
                                        + '&albumname=' + albumName;
                                }
                            }
                        }

                    </script>";

                    //$num++;
                

            };

        ?>
    </div>

    <!-- 用于修改相册名称的模态框（Modal） -->
    <div class="modal fade" id="editAlbumInfoModal" tabindex="-1" role="dialog" aria-labelledby="editAlbumModalInfoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="editAlbumInfoModalLabel">修改相册信息</h4>
                </div>
                <div class="modal-body">
                    <form action="editAlbumInfo.php">
                        <input type="hidden" id="oldDirname" name="oldDirname">
                        相册名称：<input type="text" id="newDirname" name="newDirname">
                        <br>
                        相册描述：<input type="text" id="editDescr" name="editDescr" />
                        <br>
                        <input type="submit" value="提交修改">
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    
    <script type="text/javascript">
    /*$(document).ready(){
        
    }*/
    </script>

</body>
</html>
