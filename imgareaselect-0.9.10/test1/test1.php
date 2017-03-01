<?php
//原始图片的高度与宽度
list($src_width, $src_height) = getimagesize('../../Jcrop-0.9.12/portrait/we.jpg');

//提交截图处理代码
//来源：www.fkblog.org
if(isset($_POST) && !empty($_POST)) {

	  $x1=$_POST['x1'];
    $y1=$_POST['y1'];
    $newwidth=$_POST['w'];
    $newheight=$_POST['h'];

    // 指定文件路径和缩放比例
    //$src_file = '../../Jcrop-0.9.12/portrait/we.jpg';
    $src_file = $_POST['src_file'];
    $percent = 0.5;
    // 指定头文件Content type值
    header('Content-type: image/jpeg');
    // 获取图片的宽高
    list($width, $height) = getimagesize($src_file);
    // 创建一个图片。接收参数分别为宽高，返回生成的资源句柄
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    //获取源文件资源句柄。接收参数为图片路径，返回句柄
    $source = imagecreatefromjpeg($src_file);
    // 将源文件剪切全部域并缩小放到目标图片上。前两个为资源句柄
    imagecopyresampled($thumb, $source,0,0,$x1, $y1, $newwidth, $newheight, $newwidth, $newheight);
    // 输出给浏览器
    $createtime = date("His");
    $filename = '../../Jcrop-0.9.12/portrait/me'.$createtime.'.jpg';
    imagejpeg($thumb, $filename);
	  imagedestroy($thumb);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>test1</title>
  <script src="../../jquery-3.1.1.min.js"></script>

  <!-- for zxxImgUpload -->
  <link rel="stylesheet" href="imgUploadStyle.css" type="text/css" />
  <!--Bootstrap CSS-->
  <link href="../../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
  <!--Bootstrap JS-->
  <script src="../../bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>

  <!-- for imgareaselect -->
  <link rel="stylesheet" type="text/css" href="../css/imgareaselect-default.css"/>
  <script src="../scripts/jquery.imgareaselect.pack.js"></script>
  <script type="text/javascript">

    function croppedPreviewFunc(img, selection) {
      if (!selection.width || !selection.height)
          return;

      var scaleX = 100 / selection.width;
      var scaleY = 100 / selection.height;

      $('#crop-preview img').css({
          width: Math.round(scaleX * <?php echo $src_width;?>),   //500为图像的宽度
          height: Math.round(scaleY *  <?php echo $src_height;?>),//333为图像的高度
          marginLeft: -Math.round(scaleX * selection.x1),
          marginTop: -Math.round(scaleY * selection.y1)
      });

      $('#x1').val(selection.x1);
      $('#y1').val(selection.y1);
      $('#x2').val(selection.x2);
      $('#y2').val(selection.y2);
      $('#w').val(selection.width);
      $('#h').val(selection.height);
    }

    $(function () {
        $('#photo').imgAreaSelect({ 
          aspectRatio: '1:1', 
          handles: true,
          fadeSpeed: 200, 
          onSelectChange: croppedPreviewFunc, 
          x1: 120, 
          y1: 90, 
          x2: 280,
          y2: 210 
        });

    });

 </script>
</head>


<body>

  <h2>Step 1 选择图片</h2>
  <form id="uploadForm" action="upload_handle.php" method="POST" enctype="multipart/form-data">
  <!-- 传入上传目录(先传给上传插件的params.album，再由插件传给upload_handle.php) -->
  <input type="hidden" id="uploadToAlbum" name="uploadToAlbum"/>

        <div class="upload_box">
            <div class="upload_main">
                <div class="upload_choose">
                    <input id="fileImage" type="file" size="30" name="fileselect[]" multiple />
                    <span id="fileDragArea" class="upload_drag_area">或者将图片拖到此处</span>

                </div>
                <!-- <div id="preview" class="upload_preview"></div> -->

            </div>
            <div class="upload_submit">
              <button type="button" id="fileSubmit" class="upload_submit_btn">确认上传图片</button>
            </div>
            <div id="uploadInf" class="upload_inf"></div>
        </div>
    </form>


  
  <h2>Step 2 剪裁并上传图片</h2>

  <div class="container demo">

    <div style="float: left; width: 50%;">
      <p class="instructions">
        Click and drag on the image to select an area.
      </p>

      <div class="frame" style="margin: 0 0.3em; width: 300px; height: 300px;">
        <img id="photo" alt="waiting for user to choose an image"/>

      </div>
    </div>

    <div style="float: left; width: 50%;">
      <p style="font-size: 110%; font-weight: bold; padding-left: 0.1em;">
        Selection Preview
      </p>

      <div class="frame" style="margin: 0 26em; width: 100px; height: 100px;">
        <div id="crop-preview" style="width: 100px; height: 100px; overflow: hidden;">
          <!-- <img src="../../Jcrop-0.9.12/portrait/we.jpg" style="width: 100px; height: 100px;" /> -->
          <img style="width: 100px; height: 100px;" />
        </div>
      </div>

      <form method="POST" action="">
          
          <table style="margin-top: 8em; margin-left:26em;">
            <thead>
              <tr>
                <th colspan="2" style="font-size: 110%; font-weight: bold; text-align: left; padding-left: 0.1em;">
                  Coordinates
                </th>
                <th colspan="2" style="font-size: 110%; font-weight: bold; text-align: left; padding-left: 0.1em;">
                  Dimensions
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td style="width: 10%;"><b>X<sub>1</sub>:</b></td>
                <td style="width: 30%;"><input type="text" name="x1" id="x1" value="-" /></td>
                <td style="width: 20%;"><b>Width:</b></td>
                <td><input type="text" name="w" value="-" id="w" /></td>
              </tr>
              <tr>
                <td><b>Y<sub>1</sub>:</b></td>
                <td><input type="text" name="y1" id="y1" value="-" /></td>
                <td><b>Height:</b></td>
                <td><input type="text" name="h" id="h" value="-" /></td>
              </tr>
              <tr>
                <td><b>X<sub>2</sub>:</b></td>
                <td><input type="text" name="x2" id="x2" value="-" /></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td><b>Y<sub>2</sub>:</b></td>
                <td><input type="text" name="y2" id="y2" value="-" /></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                 <td><b>src:</b></td>
                 <td><input type="text" name="src_file" id="src_file" value="-" /></td>
              </tr>
            </tbody>
          </table>
          
          <!-- <input type="text" name="src_file" id="src_file" value="-" /> -->
          <input style="margin-left:39em;" type="submit" name="name" value="submit"/>

      </form>
    </div>
  </div>
  
</body>
</html>

<!-- for zxxImgUpload -->
<script src="zxxFile.js"></script>
<script>
var params = {
  fileInput: $("#fileImage").get(0),
  dragDrop: $("#fileDragArea").get(0),
  upButton: $("#fileSubmit").get(0),
  url: $("#uploadForm").attr("action"), //传给upload_handle.php处理

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


            /*前两种方法不可以的原因：jQuery改变img的src的方法是attr('src', path)，而我用的仅仅是获取src*/
            //$("#photo").src = $("#uploadImage_0").src;
            //$("#photo").src = e.target.result;  //
            $("#photo").attr('src', e.target.result);
            $("#crop-preview img").attr('src', e.target.result);
            $("#src_file").val(e.target.result);

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


