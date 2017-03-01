<?php
    error_reporting(E_ALL^E_NOTICE^E_WARNING^E_DEPRECATED);
    
    require_once("../config/connect.php");

    $login = false;
    session_start();

    if( (!empty($_SESSION['user'])) && ($_SESSION['user'] == 'admin') ) { //判断用户是否登录
        $login = true;
    }

    $id = $_GET['id'];
    //echo $id;
    //读取原文章信息
    $sql = "SELECT * FROM article WHERE id = $id";
    $query = mysql_query($sql); 
    $data = mysql_fetch_assoc($query);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>基于mysql的简易BLOG</title>

    <!-- jQuery插件 froala_editor-->
    <link href="../froala_editor/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../froala_editor/css/froala_editor.min.css" rel="stylesheet" type="text/css">
    <script src="../froala_editor/js/libs/jquery-1.11.1.min.js"></script>
    <style>
        section {
            width: 100%;
            margin: auto;
            text-align: left;
        }
    </style>


</head>
<body>


    <div class="topbar">
        <div class="topbar_content">
            <span id="backToIndex">
                <a href="index_m.php?p=1">返回首页</a>
            </span>
            <span id="user">
                <?php
                    if($login){
                        echo "<a href='logout_m.php'>退出</a>";
                    }else{
                        echo "<a href='login_m.php'>登录</a>";
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


    <div class="detail_head">
        <h1 class="detail_head_content">
            <?php
                echo $_SESSION['user']."的博客"; 
            ?>
        </h1>
        
    </div>

<!--页面主体框-->
<div class="editor" >

    <div class="editor_title" contenteditable="true" onfocus="if(this.innerText=='请输入标题')this.innerText='';" onblur="title_onblur()" >
                    <?php echo $data['title']; ?>
    </div>    

    <form method="POST" action="edit_handle_m.php">

        <input type="hidden" name="id" value="<?php echo $data['id'] ?>" />
        
        <input type="hidden" name="title" />
        
        <textarea name="description" cols="100" rows="5" placeholder="内容简介"> <?php echo $data['description']; ?> </textarea>
        
        <input type="hidden" name="content" /> 
        <br>
        <input type="submit" value="提交">
    </form>

    <!-- jQuery插件 froala_editor -->
    <section id="editor">
    <!-- id edit使得这个div中还会创建两个div，新建的第二个div中才显示这里的click and edit -->
       <div id='edit' style="margin-top: 30px;" onclick="func()"> 
            <?php echo $data['content']; ?>
      </div>
        
    </section>

    <script>
    flag_title = 0;
    flag_content = 0;
    $(function(){
      //将title内容借助隐藏的input表单传给php存储
        var title_val = $('.editor_title').text();
        $('input[name="title"]').val(title_val); 
        flag_title = 1;
      //将content内容借助隐藏的input表单传给php存储
        flag_content = 1;
        var content_val = $("#edit").find('.froala-element').html();
        $('input[name="content"]').val(content_val);

      if(flag_title == 0){
          console.log("yes null title");
          var title_val = $('.editor_title').text();
          $('input[name="title"]').val(title_val);
        }
        if(flag_content == 0){
          console.log("yes null content");
          var content_val = $("#edit").find('.froala-element').html();
          $('input[name="content"]').val(content_val);
        }
        
    });
    function title_onblur(){
        //移开title输入框时出现默认字样
        var that = event.srcElement;
        if(that.innerText=='')that.innerText='请输入标题';

        //将title内容借助隐藏的input表单传给php存储
        flag_title = 1;
        var title_val = $('.editor_title').text();
        $('input[name="title"]').val(title_val);
    }
    function func(){
        //获取编辑器中的内容
        var content = $("#edit").find('.froala-element').html();
        //console.log(content);
        //将content内容借助隐藏的input表单传给php存储
        flag_content = 1;
        var content_val = content;
        $('input[name="content"]').val(content_val);
    }
       
    </script>

      
      <script src="../froala_editor/js/froala_editor.min.js"></script>
      <!--[if lt IE 9]>
        <script src="../js/froala_editor_ie8.min.js"></script>
      <![endif]-->
      <script src="../froala_editor/js/plugins/tables.min.js"></script>
      <script src="../froala_editor/js/plugins/lists.min.js"></script>
      <script src="../froala_editor/js/plugins/colors.min.js"></script>
      <script src="../froala_editor/js/plugins/media_manager.min.js"></script>
      <script src="../froala_editor/js/plugins/font_family.min.js"></script>
      <script src="../froala_editor/js/plugins/font_size.min.js"></script>
      <script src="../froala_editor/js/plugins/block_styles.min.js"></script>
      <script src="../froala_editor/js/plugins/video.min.js"></script>
      <script src="../froala_editor/js/langs/zh_cn.js"></script>

      <script>
        $(function(){
          $('#edit').editable({
              inlineMode: false, 
              alwaysBlank: true,
              language: "zh_cn",
              imageUploadURL: 'imgUpload.php',//上传到本地服务器
              //imageUploadParams: {id: "edit"},
              /* imageDeleteURL: 'lib/delete_image.php',//删除图片
              imagesLoadURL: 'lib/load_images.php'//管理图片*/
            })/*.on('editable.afterRemoveImage', function (e, editor, $img) {
               // Set the image source to the image delete params.        
               editor.options.imageDeleteParams = {src: $img.attr('src')};
               // Make the delete request
               editor.deleteImage($img);
           });*/
        });


    </script>

</div> <!-- end of #editor -->



<div class="detail_footer">
    <div class="detail_footer_content">
        Copyright @2016 
    </div>
</div>

    



</body>
</html>