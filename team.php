<?php
include("bd.php");

$id = htmlspecialchars($_POST[id],ENT_QUOTES);
//echo $id;
$sql_goals = mysql_query("SELECT * FROM players WHERE echip='$id' and goal_leag>'0' ORDER BY goal_leag DESC");
			if (mysql_num_rows($sql_goals)>0)
			{
				$goals_ = mysql_fetch_array($sql_goals);
				do
				{
					$ex_games = explode("/",$goals_[games]);
				
					echo'<table><tr><td style="vertical-align:middle;max-width:4em;max-height:4em;width:4em;"><img src="'.$goals_[img].'" style="max-width:4em;max-height:4em"></td><td style="vertical-align:middle;text-align:left;padding-left:1em"><a href="'.$home_url.'player/'.$goals_[player].'">'.language('name '.$goals_[player],$lang).' '.language('sname '.$goals_[player],$lang).'</a><span style="position:absolute;right:3%;display:inline-block;color:orange;font-weight:bold">'.$goals_[rating].'</span><div>'.language('goals',$lang).': '.$goals_[goal_leag].' <font style="margin-left:3%;display:inline-block">'.language('games',$lang).': '.$ex_games[0].'</font></div></td></tr></table><br>';
				}
				while($goals_ = mysql_fetch_array($sql_goals));
			}
?>