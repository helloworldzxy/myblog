<?php 

	error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);

	$album = $_POST['album']; //from params
	//echo "from upload_handle.php: ".$album;
	if(isset($_FILES["myfile"]))
	{
		$ret = array();
		$uploadDir = 'images'.DIRECTORY_SEPARATOR.$album.DIRECTORY_SEPARATOR;
		//自 PHP 4.0.2 起，__FILE__ 总是包含一个绝对路径（如果是符号连接，则是解析后的绝对路径）
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR.$uploadDir;
		file_exists($dir) || (mkdir($dir,0777,true) && chmod($dir,0777));
		if(!is_array($_FILES["myfile"]["name"])) //single file
		{
			//后台上传时，对文件的最终命名是：随机且随机的时间戳.扩展名
			$fileName = time().uniqid().'.'.pathinfo($_FILES["myfile"]["name"])['extension'];
			move_uploaded_file($_FILES["myfile"]["tmp_name"],$dir.$fileName);
			$ret['file'] = DIRECTORY_SEPARATOR.$uploadDir.$fileName;
		}
		echo json_encode($ret);
	}


?>