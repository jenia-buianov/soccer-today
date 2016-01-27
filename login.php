<?php
include("bd.php");
session_start();

$email = htmlspecialchars($_POST[email],ENT_QUOTES);
$pass = htmlspecialchars($_POST[password],ENT_QUOTES);
$sql_email = mysql_query("SELECT id FROM userlist WHERE email='$email' and password='$pass'");
if (mysql_num_rows($sql_email)>0)
{
	$user_ = mysql_fetch_array($sql_email);
	$_SESSION['user'] = $user_[id];
	echo 'ok';
}
else{
	echo language("error email or password",$lang);
}
?>