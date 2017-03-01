<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/2
 * Time: 11:47
 */
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
    $albumName = $_REQUEST['album'];    
    $albumUrl = '../zxxImgUpload/images/'.$albumName;
    //echo $albumUrl; //'../zxxImgUpload/images/20161028'
    $imageNameArr = listDirOrFile($albumUrl, 0);
    //print_r($imageNameArr);
    $imageUrlArr = array();

    $from = $_REQUEST['from'];

    /*$imageUrlArr[$i] 能不能用绝对路径，这样的话其它文件夹下的php文件也能使用*/

    if($from == 'show_albums.php'){
        for($i = 0; $i < count($imageNameArr); $i++){
            $imageUrlArr[$i] = "../zxxImgUpload/images/".$albumName."/".$imageNameArr[$i];
        }
    }else if($from == 'imgManage.php'){
        //echo "<br><br><br>"."from imgManage.php";
        for($i = 0; $i < count($imageNameArr); $i++){
            //$imageUrlArr[$i] = "../zxxImgUpload/images/".$albumName."/".$imageNameArr[$i];
            $imageUrlArr[$i] = "images/".$albumName."/".$imageNameArr[$i];
        }
    }
    /*for($i = 0; $i < count($imageNameArr); $i++){
        $imageUrlArr[$i] = "../zxxImgUpload/images/".$albumName."/".$imageNameArr[$i];
    }*/
    
    //print_r($imageUrlArr);

    $success = '0';
    $error_code = '查询成功';
    $json = array('success' => $success, 'error_code' => $error_code, 'dataArr' => $imageUrlArr);
    echo json_encode($json);


?>