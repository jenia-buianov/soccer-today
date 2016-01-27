<?php
session_start();
include("bd.php");


$text = htmlspecialchars($_POST[text],ENT_QUOTES);
$nid = htmlspecialchars($_POST[nid],ENT_QUOTES);
$sql_news = mysql_query("SELECT id FROM news WHERE abb='$nid'");
if (mysql_num_rows($sql_news)>0)
{
	$nn = mysql_fetch_array($sql_news);
	$ins = mysql_query("INSERT INTO comments(uid,message,nid,date,time)VALUES('$_SESSION[user]','$text','$nn[id]','$date','$t_now')");
	if ($ins)
	{
		?>
		<button class="mybutton" onclick=add_comment()><? echo language('add comment',$lang) ?></button>
		<?
	}else{echo mysql_error();}
}else echo 'e1';
?>