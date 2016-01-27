<?php
include("bd.php");

$lg = htmlspecialchars($_POST[lg],ENT_QUOTES);

$sql_tb = mysql_query("SELECT * FROM `table` WHERE leaga='$lg' ORDER BY bonus DESC, wins DESC, loses, egal, ball_w");
				if (mysql_num_rows($sql_tb)>0)
				{
					echo '<a onclick=open_("'.$home_url.'leaga/'.$lg.'")>'.language('full table',$lang).'</a>';
					echo '<table style="background-color:transparent">';
					$table = mysql_fetch_array($sql_tb);$k=1;
					do
					{
						$upd = mysql_query("UPDATE `table` SET place='$k' WHERE id='$table[id]'");
						if (!$upd) echo mysql_error();
						$sql_echip = mysql_query("SELECT logo FROM comands WHERE echip_id='$table[echip]'");
						$echip_ = mysql_fetch_array($sql_echip);
						echo '<tr><td style="background-color:transparent;vertical-align:middle;font-size:0.8em" width=10%>'.$k.'</td><td width="3em"><a onclick=open_("'.$home_url.'team/'.$table[echip].'") style="border:none"><img src="'.$echip_[logo].'" style="max-width:2em;max-height:2em;"></a></td><td align=left style="vertical-align:middle;padding-left:1em;"><a onclick=open_("'.$home_url.'team/'.$table[echip].'") style="border:none;cursor:pointer">'.language($table[echip],$lang).'</a></td><td style="vertical-align:middle;font-weight:bold;color:white">'.$table[bonus].'</td></tr>';
						$k++;
					}
					while($table = mysql_fetch_array($sql_tb));
					echo '</table>';
				}
?>