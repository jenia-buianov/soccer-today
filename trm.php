<?php
session_start();
include("bd.php");
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;

$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();


$id = htmlspecialchars($_POST[team],ENT_QUOTES);
$team = htmlspecialchars($_POST[id],ENT_QUOTES);
if ($deviceType=='computer'){$em_ = '1em';}else{$em_='0.7em';}

echo'<div class="mobile_c" style="margin-bottom:5em;width:100%;text-align:center;font-size:'.$em_.'">';
if ($deviceType!=='computer'){
	echo'<font '; if ($team=='hero') echo 'class="active" ';echo'onclick=ln("hero")>'.language('hero',$lang).'</font>';
}
echo'<font '; if ($team=='news') echo 'class="active" ';echo'onclick=ln("news")>'.language('news',$lang).'</font> <font '; if ($team=='team') echo 'class="active" ';echo'onclick=ln("team")>'.language('team',$lang).'</font> <font '; if ($team=='statistic') echo 'class="active" ';echo'onclick=ln("statistic")>'.language('statistic',$lang).'</font> <font '; if ($team=='calendar') echo 'class="active" ';echo'onclick=ln("calendar")>'.language('calendar',$lang).'</font> <font '; if ($team=='table') echo 'class="active" ';echo'onclick=ln("table")>'.language('online table',$lang).'</font>';
if ($deviceType!=='computer'){
	echo'<font '; if ($team=='goals') echo 'class="active" ';echo'onclick=ln("goals")>'.language('bombard',$lang).'</font>';
}
echo'</div>';

if ($team=='news')
{
	
			$sql_news = mysql_query("SELECT * FROM news WHERE (SELECT FIND_IN_SET('$echip[category]',category)) or (SELECT FIND_IN_SET('$echip[cup]',category)) or (SELECT FIND_IN_SET('$echip[eu_ch_l]',category)) or (SELECT FIND_IN_SET('$id',tags)) or (SELECT FIND_IN_SET('$echip[eu_ch_l]',tags)) or (SELECT FIND_IN_SET('$echip[cup]',tags))   order by val desc LIMIT 15");
			if (mysql_num_rows($sql_news)>0)
			{
				include("news.php");
			}
			
}

if ($team=='team')
{
	if ($deviceType=='computer') {$em = '200px';}else{$em='calc(100%)';}
	
	echo '<gazon>';
	$sql_sc = mysql_query("SELECT `schema`,`start_team` FROM `comands` WHERE `echip_id`='$id'");
	$sc = mysql_fetch_array($sql_sc);
	$sql_scheme = mysql_query("SELECT static FROM scheme where text='$sc[schema]'");
	$sc2 = mysql_fetch_array($sql_scheme);
	$ex_players = explode(",",$sc[start_team]);
	$ex_pos = explode(",",$sc2['static']);
	$k=0;
	do
	{
		$card = explode(";",$ex_pos[$k]);
		$sql_number = mysql_query("SELECT number FROM players WHERE player='$ex_players[$k]'");
		$nm = mysql_fetch_array($sql_number);
		echo'<player style="margin-top:'.$card[0].'px;margin-left:'.$card[1].'px;"><number>'.$nm[number].'</number><a onclick=open_("'.$home_url.'player/'.$ex_players[$k].'")>'.language('sname '.$ex_players[$k],$lang).'</a></player>';
		$k++;
	}
	while($k<11);
	echo'</gazon>';
	$sql_players = mysql_query("SELECT * FROM players where echip='$id' ORDER BY positsia");
	if (mysql_num_rows($sql_players)>0)
	{
		$ps = '';
		$players = mysql_fetch_array($sql_players);
		do
		{
			if ($ps!==$players[positsia]) { echo '<h3>'.language($players[positsia],$lang).'</h3>'; $ps = $players[positsia];}
			echo '<player><img src="'.$players[img].'" style="max-width:'.$em.';max-height:'.$em.';cursor:pointer" onclick=open_("'.$home_url.'player/'.$players[player].'")><a onclick=open_("'.$home_url.'player/'.$players[player].'") style="cursor:pointer">'.language('name '.$players[player],$lang).' '.language('sname '.$players[player],$lang).'</a></player>';
		}
		while($players = mysql_fetch_array($sql_players));
	}
}


if ($team=='statistic')
{
	
	$sql_table = mysql_query("SELECT * FROM `table` WHERE echip='$id' and sezon='$sezon'");
	if (mysql_num_rows($sql_table)>0)
	{
		$table = mysql_fetch_array($sql_table);
		echo '<font style="display:inline-block;width:calc(50%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$table[place].'</strong><br><i>'.language('place_in_table',$lang).'</i></font></font><font style="display:inline-block;width:calc(50%);text-align:center">'.language($table[leaga],$lang).'</font><br><br><font style="display:inline-block;width:calc(50%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$table[ball_w].'</strong><br><i>'.language('ball_w',$lang).'</i></font></font><font style="display:inline-block;width:calc(50%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$table[ball_l].'</strong><br><i>'.language('ball_l',$lang).'</i></font></font>';
	}
	
	if ($deviceType=='computer') {$em = '5em';$em1='20%';$em3='2em;';}else{$em='calc(20%)';$em1='100%';$em3='1.3em;';}
	$sql_the_best = mysql_query("SELECT * FROM games WHERE (diference>'0' and echip_1='$id' and sezon='$sezon' and competition='$table[leaga]')or(diference<'0' and echip_2='$id' and sezon='$sezon' and competition='$table[leaga]') ORDER BY diference DESC LIMIT 1");
	if (mysql_num_rows($sql_the_best)>0)
	{
		$t_best = mysql_fetch_array($sql_the_best);
		$sql_e1 = mysql_query("SELECT logo FROM comands WHERE echip_id='$t_best[echip_1]'");
		$sql_e2 = mysql_query("SELECT logo FROM comands WHERE echip_id='$t_best[echip_2]'");
		$e1 = mysql_fetch_array($sql_e1);
		$e2 = mysql_fetch_array($sql_e2);
		echo '<div style="text-align:center;margin-top:3em;margin-bottom:3em;">Самая крупная победа в чемпионате '.language($table[leaga],$lang).' '.$sezon.'</div>';
		echo'<table style="border:none;width:100%;vertical-align:middle" class="game_"><tbody><tr><td width="'.$em.'"><img src="'.$e1[logo].'" style="width:'.$em.';max-height:'.$em.'"></td><td>'.language($t_best[echip_1],$lang).'</td><td  width='.$em1.'><font style="font-size:'.$em3.';">'.$t_best[result_1].' - '.$t_best[result_2].'</font></td><td>'.language($t_best[echip_2],$lang).'</td><td width="'.$em.'" align="right"><img src="'.$e2[logo].'" style="width:'.$em.';max-height:'.$em.'"></td></tr></tbody></table>';
	}
	$sql_the_best = mysql_query("SELECT * FROM games WHERE (diference<'0' and echip_1='$id' and sezon='$sezon' and competition='$table[leaga]')or(diference>'0' and echip_2='$id' and sezon='$sezon' and competition='$table[leaga]') ORDER BY diference DESC LIMIT 1");
	if (mysql_num_rows($sql_the_best)>0)
	{
		$t_best = mysql_fetch_array($sql_the_best);
		$sql_e1 = mysql_query("SELECT logo FROM comands WHERE echip_id='$t_best[echip_1]'");
		$sql_e2 = mysql_query("SELECT logo FROM comands WHERE echip_id='$t_best[echip_2]'");
		$e1 = mysql_fetch_array($sql_e1);
		$e2 = mysql_fetch_array($sql_e2);
		echo '<div style="text-align:center;margin-top:3em;margin-bottom:3em;">Самая крупное поражение в чемпионате '.language($table[leaga],$lang).' '.$sezon.'</div>';
		echo'<table style="border:none;width:100%;vertical-align:middle" class="game_"><tbody><tr><td width="'.$em.'"><img src="'.$e1[logo].'" style="width:'.$em.';max-height:'.$em.'"></td><td>'.language($t_best[echip_1],$lang).'</td><td  width='.$em1.'><font style="font-size:'.$em3.'">'.$t_best[result_1].' - '.$t_best[result_2].'</font></td><td>'.language($t_best[echip_2],$lang).'</td><td width="'.$em.'" align="right"><img src="'.$e2[logo].'" style="width:'.$em.';max-height:'.$em.'"></td></tr></tbody></table>';
	}
	echo '<hr>';
	$sql_cm = mysql_query("SELECT * FROM comands WHERE echip_id='$id'");
	$com = mysql_fetch_array($sql_cm);
	 if ($deviceType=='computer') $ems = 33; else $ems = 100; 
	echo '
	<font style="display:inline-block;width:calc('.$ems.'%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$com[shoots].'</strong><br><i>'.language('shoots',$lang).'</i></font></font>
	<font style="display:inline-block;width:calc('.$ems.'%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$com[corners].'</strong><br><i>'.language('corners',$lang).'</i></font></font>
	<font style="display:inline-block;width:calc('.$ems.'%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$com[posession].'%</strong><br><i>'.language('posession',$lang).'</i></font></font><br><br>
	
	<font style="display:inline-block;width:calc('.$ems.'%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$com[foults].'</strong><br><i>'.language('faults',$lang).'</i></font></font>
	<font style="display:inline-block;width:calc('.$ems.'%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$com[offsides].'</strong><br><i>'.language('offsides',$lang).'</i></font></font>
	<font style="display:inline-block;width:calc('.$ems.'%);text-align:center;"><font class="statistic_small"><strong class="statistic_big">'.$com[cards].'</strong><br><i>'.language('cards',$lang).'</i></font></font><br><br>
	
	<div class="statistic_big" style="margin-bottom:10px;font-size:2.1em;margin-top:10px;text-align:center;color:orange">Rating: '.$com[rating].'</div>
	
	
	<font style="display:inline-block;width:calc(33%);text-align:center;margin-top:9em"><font class="statistic_small"><strong class="statistic_big" style="color:green;font-size:4em">'.$table[wins].'</strong><br><i>'.language('wins',$lang).'</i></font></font><font style="display:inline-block;width:calc(33%);text-align:center;"><font class="statistic_small"><strong class="statistic_big"style="font-size:4em">'.$table[egal].'</strong><br><i>'.language('egal',$lang).'</i></font></font><font style="display:inline-block;width:calc(33%);text-align:center;"><font class="statistic_small"><strong class="statistic_big"style="color:red;font-size:4em">'.$table[loses].'</strong><br><i>'.language('loses',$lang).'</i></font></font>';
	$sql_calendar = mysql_query("SELECT * FROM games WHERE (echip_1='$id' and sezon='$sezon' and `live`<>'')or(echip_2='$id' and `live`<>'' and sezon='$sezon')");
	include("show_games.php");
	
}

if ($team=='calendar')
{
	if ($deviceType=='computer') {$em = '5em';$em1='20%';$em3='2em;';}else{$em='calc(20%)';$em1='100%';$em3='1.3em;';}
	$sql_calendar = mysql_query("SELECT * FROM games WHERE (echip_1='$id' and sezon='$sezon')or(echip_2='$id' and sezon='$sezon')");
	include("show_games.php");
	
}

if ($team=='table')
{
	$sql_liga = mysql_query("SELECT leaga FROM comands WHERE echip_id='$id'");
	$lg = mysql_fetch_array($sql_liga);
	$lg =$lg[leaga];
$sql_tb = mysql_query("SELECT * FROM `table` WHERE leaga='$lg' ORDER BY bonus DESC, wins DESC, loses, egal, ball_w");
				if (mysql_num_rows($sql_tb)>0)
				{
					echo '<p style="text-align:center"><a onclick=open_("'.$home_url.'leaga/'.$lg.'")>'.language('full table',$lang).'</a></p>';
					echo '<table style="background-color:transparent" style="width:100%">';
					$table = mysql_fetch_array($sql_tb);$k=1;
					do
					{
						$upd = mysql_query("UPDATE `table` SET place='$k' WHERE id='$table[id]'");
						if (!$upd) echo mysql_error();
						$sql_echip = mysql_query("SELECT logo FROM comands WHERE echip_id='$table[echip]'");
						$echip_ = mysql_fetch_array($sql_echip);
						echo '<tr><td style="background-color:transparent;vertical-align:middle;font-size:0.8em" width=10%>'.$k.'</td><td width="3em"><a onclick=open_("'.$home_url.'team/'.$table[echip].'") style="border:none"><img src="'.$echip_[logo].'" style="max-width:2em;max-height:2em;"></a></td><td align=left style="vertical-align:middle;padding-left:1em;"><a onclick=open_("'.$home_url.'team/'.$table[echip].'") style="border:none;cursor:pointer">'.language($table[echip],$lang).'</a></td><td style="vertical-align:middle;font-weight:bold;">'.$table[bonus].'</td></tr>';
						$k++;
					}
					while($table = mysql_fetch_array($sql_tb));
					echo '</table>';
				}
}

if ($team=='goals')
{
	
	$sql_goals = mysql_query("SELECT * FROM players WHERE echip='$id' and goal_leag>'0' ORDER BY goal_leag DESC");
			if (mysql_num_rows($sql_goals)>0)
			{
				$goals_ = mysql_fetch_array($sql_goals);
				do
				{
					$ex_games = explode("/",$goals_[games]);
				
					echo'<table><tr><td style="vertical-align:middle;max-width:4em;max-height:4em;width:4em;"><img src="'.$goals_[img].'" style="max-width:4em;max-height:4em"></td><td style="vertical-align:middle;text-align:left"><a href="'.$home_url.'player/'.$goals_[player].'">'.language('name '.$goals_[player],$lang).' '.language('sname '.$goals_[player],$lang).'</a><div>'.language('goals',$lang).': '.$goals_[goal_leag].' <font style="margin-left:3%;display:inline-block">'.language('games',$lang).': '.$ex_games[0].'</font></div></td></tr></table><br>';
				}
				while($goals_ = mysql_fetch_array($sql_goals));
			}
	
}

if ($team=='hero')
{
	$sql_the_best = mysql_query("SELECT * FROM players WHERE echip='$id' ORDER BY goal_leag+goal_eu+gol_cup+pas_leag+pas_eu+pas_cup DESC LIMIT 1");
		if (mysql_num_rows($sql_the_best)>0)
		{
			$the_best = mysql_fetch_array($sql_the_best);
			$ex_games = explode("/",$the_best[games]);
			$ex_time = explode("/",$the_best[minutes_played]);
			$g_liga = $ex_games[0];
			$g_eu = $ex_games[1];
			$g_cup = $ex_games[2];
			$time_ = $ex_time[0]+$ex_time[1]+$ex_time[2];
			
		if ($deviceType=='computer'){$em_ = '1em';}else{$em_='0.7em';}
			echo'
			<table id="the_best" style="color:#666">
  <tbody><tr>
    <td align="center" valign="top"><img src="'.$the_best[img].'" style="max-width:7em;max-height:7em"><br><a href="'.$home_url.'player/'.$the_best[player].'">'.language('name '.$the_best[player],$lang).' '.language('sname '.$the_best[player],$lang).'</a></td>
    <td valign="top"><font class="statistic_small"><strong class="statistic_big" style="color:#666">'.$the_best[goal_leag].'</strong><br>'.language('goal_leag',$lang).'</font><font class="statistic_small" style="color:#66666"><strong class="statistic_big" style="color:#666">';
	if($the_best[goal_eu]>0) echo $the_best[goal_eu]; else echo $the_best[pas_leag];
	echo'</strong><br>';
	if ($the_best[goal_eu]>0) echo language('goal_eu',$lang); else echo language('pas_leag',$lang);
	echo'</font><br><br><font class="statistic_small" style="color:#666"><strong class="statistic_big" style="color:#666">'.$g_liga.'</strong><br>'.language('games_leag',$lang).'</font><font class="statistic_small" style="color:#666"><strong class="statistic_big" style="color:#666">'.$time_.'</strong><br>'.language('minutes played',$lang).'</font></td>
  </tr>
</tbody></table>';
		}
}

?>