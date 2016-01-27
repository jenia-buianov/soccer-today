<?php
if (!empty($_POST[id])) session_start();
if (!empty($_POST[id])) include("bd.php");

if (!empty($_POST[id])) $nid = htmlspecialchars($_POST[id],ENT_QUOTES); else $nid = $news[abb];

$sql_news = mysql_query("SELECT id FROM news WHERE abb='$nid'");
if (mysql_num_rows($sql_news)>0)
{
	$nn = mysql_fetch_array($sql_news);
	$sql_comm = mysql_query("SELECT * FROM comments WHERE nid='$nn[id]' ORDER BY id DESC");
	if (mysql_num_rows($sql_comm)>0)
	{
		$com = mysql_fetch_array($sql_comm);
		do
		{
			$sql_user = mysql_query("SELECT name FROM userlist WHERE id='$com[uid]'");
			$user = mysql_fetch_array($sql_user);
			$mago = time_elapsed_string($com['date'].' '.$com['time'],$lang);
			echo '<table style="margin-bottom:3em;"><tr><td style="width:15%">'.$user[name].'</td><td style="font-size:0.9em;color:#666"><div style="color:#787878;font-size:0.7em">'.$mago.'</div>'.$com[message].'</td></tr></table>';
		}
		while($com = mysql_fetch_array($sql_comm));
	}
}
?>