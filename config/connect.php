<?php
	require_once('config.php');
	//Á¬¿â
	if(!($con = mysql_connect(HOST, USERNAME, PASSWORD))){
		echo mysql_error();
	}
	//Ñ¡¿â
	if(!mysql_select_db('info')){
		echo mysql_error();
	}
	//×Ö·û¼¯
	if(!mysqli_query($con,'set names utf8')){
		echo mysql_error();
	}
?>