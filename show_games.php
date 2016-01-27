<?php


if (mysql_num_rows($sql_calendar)>0)
	{
		$cal = mysql_fetch_array($sql_calendar);
		do
		{
			$sql_e1 = mysql_query("SELECT logo FROM comands WHERE echip_id='$cal[echip_1]'");
			$sql_e2 = mysql_query("SELECT logo FROM comands WHERE echip_id='$cal[echip_2]'");
			$e1 = mysql_fetch_array($sql_e1);
			$e2 = mysql_fetch_array($sql_e2);
			echo'<table style="border:none;width:100%;vertical-align:middle" class="game_"';
			if (empty($cal[live])) echo 'onclick=open_("'.$home_url.'games/'.$cal[id].'") style="cursor:pointer"';
			echo'><tbody><tr><td width="'.$em.'"><img src="'.$e1[logo].'" style="width:'.$em.';max-height:'.$em.'"></td><td>'.language($cal[echip_1],$lang).'</td><td  width='.$em1.'><font style="font-size:'.$em3;
			if (!empty($cal[live])){
			if(($cal[echip_1]==$id and $cal[diference]>0)or($cal[echip_2]==$id and $cal[diference]<0)) echo 'color:green';
			if(($cal[echip_1]==$id and $cal[diference]<0)or($cal[echip_2]==$id and $cal[diference]>0)) echo 'color:red';
			}else{echo 'font-size:1.2em';}
			echo'">';$ex = explode(".",language($cal[competition],$lang));
			if (!empty($cal[live])){echo $cal[result_1].' - '.$cal[result_2].'<br><font style="font-size:0.3em;color:#787878">'.$ex[0].' <b>'.language('tur',$lang).' '.$cal[tur].'</b></font>';} else{echo '<font style="font-size:0.5em;">'.$cal['date'].', '.$cal['time'].'<br>'.$ex[0].' '.language('tur',$lang).' '.$cal[tur].'</font>';}
			echo'</font></td><td>'.language($cal[echip_2],$lang).'</td><td width="'.$em.'" align="right"><img src="'.$e2[logo].'" style="width:'.$em.';max-height:'.$em.'"></td></tr></tbody></table>';
		}
		while($cal = mysql_fetch_array($sql_calendar));
	}
	
	?>