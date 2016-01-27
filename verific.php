<?php
//echo "SELECT * FROM games WHERE (sezon='".$sezon."' and echip_1='".$id."' and copmetition='".$echip[leaga]."' and date<='$date')or(sezon='".$sezon."' and echip_2='".$id."' and copmetition='".$echip[leaga]."' and time_left<>'')";
$sql_liga_games = mysql_query("SELECT * FROM games WHERE (`sezon`='$sezon' and `echip_1`='$id' and `competition`='$echip[leaga]' and `live`<>'')or(`sezon`='$sezon' and `echip_2`='$id' and `competition`='$echip[leaga]' and `live`<>'')");
if (mysql_num_rows($sql_liga_games)>0)
{
	$upd_liga_games = mysql_query("UPDATE players SET goal_leag='0', pas_leag='0',games='0/0/0',minutes_played='0/0/0',rating='0' WHERE echip='$id'");
	
	$liga_games = mysql_fetch_array($sql_liga_games);
	$ball_w = 0;
	$ball_l = 0;
	$loses = 0;
	$wins = 0;
	$egal=0;
	$posession = 0;
	$rating = 0;
	$cards = 0;
	$upd_ = mysql_query("UPDATE comands SET posession = '$posession', shoots='$shoots', cards = '$cards', foults = '$foults', offsides = '$offsides',rating='0',corners='0' WHERE echip_id='$id'");
	
	do
	{
		
		if ($liga_games[echip_1]==$id) {$ec=1;$vs = 2;$em_ = 0; $em2_=1;}else{$em_ = 1; $em2_=0;$ec=2;$vs=1;}
			$expl_cards = explode("/",$liga_games[cards]);
			$v_=0;
			do
			{
				$whose = explode(",",$expl_cards[$v_]);
				if ($id==$whose[2]) $cards++;
				$v_++;
			}
			while($v_<count($expl_cards));
			$expl_statistic = explode("/",$liga_games['statistic']);
			$v_ = 0;
			$expl_rating = explode(",",$liga_games[rating]);
			$rating=$rating+$expl_rating[$em_];
			
			do
			{
				$e_ = explode(":",$expl_statistic[$v_]);
				$e2_ = explode(",",$e_[1]);
				if ($e_[0]=='posession') {$posession+=$e2_[$em_];}else{
					$upd_echip1 = mysql_query("UPDATE comands SET `".$e_[0]."`=".$e_[0]."+".$e2_[0]." where echip_id='$liga_games[echip_1]'");
					$upd_echip2 = mysql_query("UPDATE comands SET `".$e_[0]."`=".$e_[0]."+".$e2_[1]." where echip_id='$liga_games[echip_2]'");
				}
				$v_++;
			}
			while($v_<count($expl_statistic));
			
			$expl_rating_players = explode(",",$liga_games[rating_players]);
			$v_=0;
			do
			{
				$ex_ = explode(":",$expl_rating_players[$v_]);
				$upd_pl = mysql_query("UPDATE players SET rating=rating+".$ex_[1]." WHERE player='$ex_[0]'");
				$v_++;
			}
			while($v_<count($expl_rating_players));
			
			$ball_l+=$liga_games['result_'.$vs];
			$ball_w+=$liga_games['result_'.$ec];
			if ($liga_games['result_'.$ec]>$liga_games['result_'.$vs]) $wins++;
			if ($liga_games['result_'.$ec]==$liga_games['result_'.$vs]) $egal++;
			if ($liga_games['result_'.$ec]<$liga_games['result_'.$vs]) $loses++;
			$sostav = explode(",",$liga_games['players_'.$ec]);
			$r=0;
			do
			{
				$find = 'n';
				if (!empty($liga_games['zamena_'.$ec]))
				{
					
					$zamena = explode("/",$liga_games['zamena_'.$ec]);
					$m=0; $find = 'n';
					do
					{
						$ex = explode(",",$zamena[$m]);
						if ($ex[0]==$sostav[$r]) {$find='y';break;}
						$m++;
					}
					while($m<count($zamena));
				}
				if ($find=='y'){
					$time = $ex[1];
					$sql_time = mysql_query("SELECT minutes_played,games FROM players WHERE player='$ex[0]'");
					$pp = mysql_fetch_array($sql_time);
					$ex_time = explode("/",$pp[minutes_played]);
					$new_time = $ex_time[0]+$time;
					$ex_games = explode("/",$pp[games]);
					$new_games = $ex_games[0]+1;
					$new_time = $new_time.'/'.$ex_time[1].'/'.$ex_time[2];
					$new_games = $new_games.'/'.$ex_games[1].'/'.$ex_games[2];
					$upd_time = mysql_query("UPDATE players SET games='$new_games',minutes_played='$new_time' WHERE player='$sostav[$r]'");
					
					$sql_time = mysql_query("SELECT minutes_played,games FROM players WHERE player='$ex[2]'");
					$pp = mysql_fetch_array($sql_time);
					$ex_time = explode("/",$pp[minutes_played]);
					$new_time = $ex_time[0]-$time+$liga_games[time_left];
					$ex_games = explode("/",$pp[games]);
					$new_games = $ex_games[0]+1;
					$new_time = $new_time.'/'.$ex_time[1].'/'.$ex_time[2];
					$new_games = $new_games.'/'.$ex_games[1].'/'.$ex_games[2];
					$upd_time = mysql_query("UPDATE players SET games='$new_games',minutes_played='$new_time' WHERE player='$ex[2]'");
					
					
				}else{$time = $liga_games[time_left];
				$sql_time = mysql_query("SELECT minutes_played,games FROM players WHERE player='$sostav[$r]'");
					$pp = mysql_fetch_array($sql_time);
					//echo $pp[games];
					$ex_time = explode("/",$pp[minutes_played]);
					$new_time = $ex_time[0]+$time;
					$ex_games = explode("/",$pp[games]);
					$new_games = $ex_games[0]+1;
					$new_time = $new_time.'/'.$ex_time[1].'/'.$ex_time[2];
					$new_games = $new_games.'/'.$ex_games[1].'/'.$ex_games[2];
					//echo $pp[games].' () '.$new_games.' '.$new_time.' - '.$sostav[$r].'<br>';
					$upd_time = mysql_query("UPDATE players SET games='$new_games',minutes_played='$new_time' WHERE player='$sostav[$r]'");
					
				}
				$r++;
			}
			while($r<count($sostav));
			
			
			if(!empty($liga_games[gol]))
			{
				$explode_gol = explode("/",$liga_games[gol]);
				$k=0;
				do
				{
					$ex = explode(",",$explode_gol[$k]);
					$upd_gol = mysql_query("UPDATE players SET goal_leag=goal_leag+1 WHERE player='$ex[0]'");
					if (!$upd_gol) echo mysql_error();
					if (!empty($ex[2]))
					{
						$upd_pas = mysql_query("UPDATE players SET pas_leag=pas_leag+1 WHERE player='$ex[2]'");
				
					}
					$k++;
				}
				while($k<count($explode_gol));
				
			}
	}
	while($liga_games = mysql_fetch_array($sql_liga_games));
	$bonus = $wins*3+$egal;
	$rating/=mysql_num_rows($sql_liga_games);
	$posession/=mysql_num_rows($sql_liga_games);
	$sql_sostav = mysql_query("SELECT rating,games,player FROM players WHERE echip='$id'");
	if(mysql_num_rows($sql_sostav)>0)
	{
		$sost = mysql_fetch_array($sql_sostav);
		do
		{
			$time = explode("/",$sost[games]);
			if ($sost[rating]>0) {
				$rtng = $sost[rating]/$time[0];
				$upd = mysql_query("UPDATE players SET rating='$rtng' WHERE player='$sost[player]'");
			}
		}
		while($sost = mysql_fetch_array($sql_sostav));
	}
	$upd_ = mysql_query("UPDATE comands SET rating='$rating', posession='$posession',cards='$cards' WHERE echip_id='$id'");
	$upd_table = mysql_query("UPDATE `table` SET bonus='$bonus',wins='$wins',loses='$loses',egal='$egal',ball_w='$ball_w',ball_l='$ball_l' WHERE echip='$id' and sezon='$sezon'");
}
else
{
	$upd_liga_games = mysql_query("UPDATE players SET goal_leag='0', pas_leag='0',games='0/0/0',minutes_played='0/0/0' WHERE echip='$id'");
	$upd_table = mysql_query("UPDATE `table` SET bonus='0',wins='0',loses='0',egal='0',ball_w='0',ball_l='0' WHERE echip='$id' and sezon='$sezon'");
}
//EXIT;
?>