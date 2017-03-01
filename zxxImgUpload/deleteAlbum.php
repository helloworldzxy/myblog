<?php 

//删除某个目录及其下所有文件
function deldir($dir) {
	//先删除目录下的文件：
	$dh = opendir($dir);
	while ($file = readdir($dh)) {
		if($file != "." && $file != "..") {
			$fullpath = $dir."/".$file;
			if(!is_dir($fullpath)) { //如果不是目录
				unlink($fullpath);	//删除文件
			} else {
				deldir($fullpath);
			}
		}
	}
	closedir($dh);

	//删除当前文件夹：
	if(rmdir($dir)) {
		return true;
	} else {
		return false;
	}
}


$dirname = $_GET['dirname'];
$dirurl = $_SERVER['DOCUMENT_ROOT'].'/myblog/zxxImgUpload/images/'.$dirname;
//echo $dirurl;
//echo "<br><br><br>".$dirname;
if(deldir($dirurl) == true){
	echo "<script>alert('成功删除相册！');window.location.href='imgManage.php'</script>";
}else{
	echo "<script>alert('删除相册失败！');
	
	//window.location.href='imgManage.php'</script>";
}



?>