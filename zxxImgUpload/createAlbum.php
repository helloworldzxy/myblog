<?php 

	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

	require_once('../config/connect.php');

	//新建文件夹
	function createDir($aimUrl) {
        $aimUrl = str_replace('', '/', $aimUrl); //把''替换为'/'
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        $result = true;
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!file_exists($aimDir)) {
                $result = mkdir($aimDir);
            }
        }
        return $result;
    }

	$albumName = $_POST['newAlbumName'];
	$albumDescription = $_POST['newAlbumDescription'];
	$albumPath = $_SERVER['DOCUMENT_ROOT'].'/myblog/zxxImgUpload/images/'.$albumName;
	//echo $albumPath;
	//echo "<br>换行后：albumName为：".empty($albumName).isset($albumName);
	$flag = $albumName == NULL; //==优先级更高
	//echo "<br>flag:".$flag; //1

	if(empty($albumName) || $flag){
		echo "<script>alert('相册名称不能为空');window.location.href='imgUploadDemo.php'</script>";
	}

	$insertsql = "INSERT INTO album(name, description, url) VALUES('$albumName', '$albumDescription','$albumPath')";

	if(mysql_query($insertsql) && createDir($albumPath)){
		echo "<script>alert('新建相册成功');window.location.href='imgUploadDemo.php'</script>";
	}else{
		echo "<script>alert('新建相册失败');window.location.href='imgUploadDemo.php'</script>";
	}


?>