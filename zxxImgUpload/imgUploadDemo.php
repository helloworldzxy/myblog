<?php 
	//获取某一目录下的所有文件夹/文件的名称
	function listDirOrFile($dir, $isDir){
		if(is_dir($dir)){
			if($dh = opendir($dir)){
				$dirList = array();
				$fileList = array();
				$i = 0;
				$j = 0;
				while(($file = readdir($dh)) !== false){
					if((is_dir($dir."/".$file)) && $file != "." && $file != ".." ){ //如果是目录(每次readir都要过滤掉.和..)
						//echo "<div id='img-$i' style='display:none;'>文件名：</div>".$file."<br><br>";
						$dirList[$i] = $file;
						$i++;
					}else{	//如果是文件
						if($file != "." && $file != ".."){	//(每次readir都要过滤掉.和..)
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
	$url4albums = 'D:/XAMPP/htdocs/myblog/zxxImgUpload/images';
	$albumList = listDirOrFile($url4albums, 1); //[20161027, 20161028, ...]
	$url4files = array();	//该数组存放相册目录名称"....../20161027"，即下面的key
	$images = array(); //二维关联数组：key为"....../20161027" value为另一个数组，为该key对应目录下遍历的所有文件
	for($i = 0; $i < count($albumList); $i++){
		$url4files[$i] = $url4albums."/".$albumList[$i];
		$images[$url4files[$i]] = listDirOrFile($url4files[$i], 0);
	}
	//print_r($images);


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>基于HTML5的多图Ajax上传</title>
<link rel="stylesheet" href="imgUploadStyle.css" type="text/css" />
	<script src="../jquery-3.1.1.min.js"></script>
	<!--Bootstrap CSS-->
	<link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
	<!--Bootstrap JS-->
	<script src="../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>


<style>
.upload_box{width:800px; margin:1em auto;}
.upload_main{border-width:1px 1px 2px; border-style:solid; border-color:#ccc #ccc #ddd; background-color:#fbfbfb;}
.upload_choose{padding:1em;}
.upload_drag_area{display:inline-block; width:60%; padding:4em 0; margin-left:.5em; border:1px dashed #ddd; background:#fff url(drag.png) no-repeat 20px center; color:#999; text-align:center; vertical-align:middle;}
.upload_drag_hover{border-color:#069; box-shadow:inset 2px 2px 4px rgba(0, 0, 0, .5); color:#333;}
.upload_preview{border-top:1px solid #bbb; border-bottom:1px solid #bbb; background-color:#fff; overflow:hidden; _zoom:1;}
.upload_append_list{height:300px; padding:0 1em; float:left; position:relative;}
.upload_delete{margin-left:2em;}
.upload_image{max-height:250px; padding:5px;}
.upload_submit{padding-top:1em; padding-left:1em;}
.upload_submit_btn{display:none; height:32px; font-size:14px;}
.upload_loading{height:250px; background:url(preload.gif) no-repeat center;}
.upload_progress{display:none; padding:5px; border-radius:10px; color:#fff; background-color:rgba(0,0,0,.6); position:absolute; left:25px; top:45px;}
</style>
</head>

<body>
<div id="header">
	<!-- <a href="/" class="logo" title="回到鑫空间-鑫生活首页">
	    	<img src="http://www.zhangxinxu.com/php/image/zxx_home_logo.png" border="0" />
	    </a> -->
</div>
<div id="main">
	<h1>基于HTML5的多图Ajax上传实例页面</h1>
    <div id="body" class="light">
    	<div id="content" class="show">
        	<h3>上传照片</h3>
           <div class="article_new"><a href="../images-grid/show_albums.php">返回相册 »</a></div>
            <div class="demo">
            	<h2>Step 1 选择相册</h2>
            	<div>
            		<select name="album-select" id="album-select" onchange="selectAlbum()">
					<?php 
					$num = count($albumList);
					//echo $num;
					for($i = 0; $i < $num; $i++){
					?>
            			<option value="<?php echo $albumList[$i]; ?>"><?php echo $albumList[$i]; ?></option>
            		<?php 
            		}
            		?>
            		</select>
					&nbsp;
					<!--通过链接触发模态框-->
					<span><a id="newAlbumLink" href="#newAlbumModal" data-toggle="modal">新建相册</a></span>

            	</div>

            	<h2>Step 2 选择并上传照片</h2>
            	<form id="uploadForm" action="upload_handle.php" method="POST" enctype="multipart/form-data">
					<!-- 传入上传目录(先传给上传插件的params.album，再由插件传给upload_handle.php) -->
        			<input type="hidden" id="uploadToAlbum" name="uploadToAlbum"/>

                    <div class="upload_box">
                        <div class="upload_main">
                            <div class="upload_choose">
                                <input id="fileImage" type="file" size="30" name="fileselect[]" multiple />
                                <span id="fileDragArea" class="upload_drag_area">或者将图片拖到此处</span>

                            </div>
                            <div id="preview" class="upload_preview"></div>
                        </div>
                        <div class="upload_submit">
                        	<button type="button" id="fileSubmit" class="upload_submit_btn">确认上传图片</button>
                        </div>
                        <div id="uploadInf" class="upload_inf"></div>
                    </div>
                </form>
                
            </div>
        </div>       
    </div>
</div>
<div id="footer">
    <!-- Designed &amp; Powerd by <a href="http://www.zhangxinxu.com/">zhangxinxu</a><br />
      Copyright© 张鑫旭-鑫空间-鑫生活<br>
      <a href="http://www.miibeian.gov.cn/" target="_blank">鄂ICP备09015569号</a>     -->  
</div>

<!-- 用于新建相册的模态框（Modal） -->
<div class="modal fade" id="newAlbumModal" tabindex="-1" role="dialog" aria-labelledby="newAlbumModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="newAlbumModalLabel">新建相册</h4>
			</div>
			<div class="modal-body">
				<form action="createAlbum.php" method="POST">
					相册名称：<input type="text" id="newAlbumName" name="newAlbumName"/>
					<br>
					相册描述：<input type="text" id="newAlbumDescription" name="newAlbumDescription" />
					<br>
					<input type="submit" value="提交"/>
				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	
	$(function () {
		selectAlbum();
	});
	
	function selectAlbum(){
		$('#uploadToAlbum').val($('#album-select option:selected').val());
	}
</script>
<script src="zxxFile.js"></script>
<script>
var params = {
	fileInput: $("#fileImage").get(0),
	dragDrop: $("#fileDragArea").get(0),
	upButton: $("#fileSubmit").get(0),
	url: $("#uploadForm").attr("action"),	//传给upload_handle.php处理
	album: $('#uploadToAlbum').val(),	//这是初始值，后面若不更改则album一直是打开该网页时的最初的那个值 即使select onchange了也不会改变这里的album参数
	onSelectAlbum: function(){  //用于改变self.album值
		self.album =  $('#uploadToAlbum').val();
		//console.log("imgUploadDemo-from onSelectAlbum(): self.album = " + self.album);
		return self.album;
	},

	filter: function(files) {
		var arrFiles = [];
		for (var i = 0, file; file = files[i]; i++) {
			if (file.type.indexOf("image") == 0) {
				if (file.size >= 512000) {
					alert('您这张"'+ file.name +'"图片大小过大，应小于500k');	
				} else {
					arrFiles.push(file);	
				}			
			} else {
				alert('文件"' + file.name + '"不是图片。');	
			}
		}
		
		return arrFiles;
	},
	onSelect: function(files) {
		var html = '', i = 0;
		$("#preview").html('<div class="upload_loading"></div>');
		var funAppendImage = function() {
			file = files[i];
			if (file) {
				var reader = new FileReader()
				reader.onload = function(e) {
					html = html + '<div id="uploadList_'+ i +'" class="upload_append_list"><p><strong>' + file.name + '</strong>'+ 
						'<a href="javascript:" class="upload_delete" title="删除" data-index="'+ i +'">删除</a><br />' +
						'<img id="uploadImage_' + i + '" src="' + e.target.result + '" class="upload_image" /></p>'+ 
						'<span id="uploadProgress_' + i + '" class="upload_progress"></span>' +
					'</div>';
					
					i++;
					funAppendImage();
				}
				reader.readAsDataURL(file);
			} else {
				$("#preview").html(html);
				if (html) {
					//删除方法
					$(".upload_delete").click(function() {
						ZXXFILE.funDeleteFile(files[parseInt($(this).attr("data-index"))]);
						return false;	
					});
					//提交按钮显示
					$("#fileSubmit").show();	
				} else {
					//提交按钮隐藏
					$("#fileSubmit").hide();	
				}
			}
		};
		funAppendImage();		
	},
	onDelete: function(file) {
		$("#uploadList_" + file.index).fadeOut();
	},
	onDragOver: function() {
		$(this).addClass("upload_drag_hover");
	},
	onDragLeave: function() {
		$(this).removeClass("upload_drag_hover");
	},
	onProgress: function(file, loaded, total) {
		var eleProgress = $("#uploadProgress_" + file.index), percent = (loaded / total * 100).toFixed(2) + '%';
		eleProgress.show().html(percent);
	},
	onSuccess: function(file, response) {
		$("#uploadInf").append("<p>上传成功，图片地址是：" + response + "</p>");
	},
	onFailure: function(file) {
		$("#uploadInf").append("<p>图片" + file.name + "上传失败！</p>");	
		$("#uploadImage_" + file.index).css("opacity", 0.2);
	},
	onComplete: function() {
		//提交按钮隐藏
		$("#fileSubmit").hide();
		//file控件value置空
		$("#fileImage").val("");
		$("#uploadInf").append("<p>当前图片全部上传完毕，可继续添加上传。</p>");
	}
};
ZXXFILE = $.extend(ZXXFILE, params);
ZXXFILE.init();
</script>


</body>
</html>
