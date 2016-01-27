<?php
include("bd.php");


$sql_comands = mysql_query("select * from comands");
if (mysql_num_rows($sql_comands)>0)
{
	$com = mysql_fetch_array($sql_comands);
	do
	{
		$expl = explode("500mb.net/",$com[logo]);
		if  (count($expl)>1)
		{
			$logo = 'http://soccer-today.ru/'.$expl[1];
			$upd = mysql_query("UPDATE comands SET logo='$logo' WHERE id='$com[id]'");
			if ($upd)echo $logo.'<br>';
		}
	}
	while($com = mysql_fetch_array($sql_comands));
	
}
?>