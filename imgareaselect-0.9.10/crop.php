<?php

    public function actionCrop($src_path,$x1,$x2,$y1,$y2){
        $pic =$src_path;
 
        $width = $x2-$x1;
        $height = $y2-$y1;
 
        $type=exif_imagetype($pic);  //判断文件类型
        $support_type=array(IMAGETYPE_JPEG , IMAGETYPE_PNG , IMAGETYPE_GIF);
        if(!in_array($type, $support_type,true)) {
            echo "this type of image does not support! only support jpg , gif or png";
            exit();
        }
        switch($type) {
            case IMAGETYPE_JPEG :
                $image = imagecreatefromjpeg($pic);
                break;
            case IMAGETYPE_PNG :
                $image = imagecreatefrompng($pic);
                break;
            case IMAGETYPE_GIF :
                $image = imagecreatefromgif($pic);
                break;
            default:
                echo "Load image error!";
                exit();
        }
 
        $copy = $this->PIPHP_ImageCrop($image, $x1, $y1, $width, $height);//裁剪
 
        imagejpeg($copy, $src_path);  //替换新图
        return ['result'=>'Success','path'=>$src_path]; //返回新图地址
    }

    function PIPHP_ImageCrop($image, $x, $y, $w, $h){
        $tw = imagesx($image);  
        $th = imagesy($image);  
 
        if ($x > $tw || $y > $th || $w > $tw || $h > $th) return FALSE;  
 
        $temp = imagecreatetruecolor($w, $h);  
        imagecopyresampled($temp, $image, 0, 0, $x, $y, $w, $h, $w, $h);  
        return $temp;  
    }

?>