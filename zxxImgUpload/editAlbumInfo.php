<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/7
 * Time: 11:18
 */

error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

require_once('../config/connect.php');


$oldDirname = $_GET['oldDirname'];
$newDirname = $_GET['newDirname'];
$editDescr = $_GET['editDescr'];
/*echo $oldDirname;
echo "<br>";
echo $newDirname;
echo "<br>";
echo $editDescr;*/
$oldPath = $_SERVER['DOCUMENT_ROOT'].'/myblog/zxxImgUpload/images/'.$oldDirname;
$newPath = $_SERVER['DOCUMENT_ROOT'].'/myblog/zxxImgUpload/images/'.$newDirname;
//echo $oldPath."<br>".$newPath."<br>";


//在数据库中修改
//$albumid = ; //字段name是UNIQUE KEY 但是最好是通过id修改
$updatesql = "UPDATE album SET name = '$newDirname', description = '$editDescr', url = '$newPath' WHERE name = '$oldDirname'"; //php中写sql语句 UPDATE SET 的给字段赋的值要加单引号

if(rename($oldPath, $newPath) && mysql_query($updatesql)){  //rename()用于在磁盘里修改
    echo "<script>alert('修改成功！')</script>";
    echo "<script>window.location.href='../images-grid/show_albums.php'</script>";
}else{
    echo "<script>alert('修改失败！')</script>";
    //echo mysql_error();
    echo "<script>window.location.href='../images-grid/show_albums.php'</script>";
}


?>