<?php
if (!empty($_POST[url])) session_start();
if (!empty($_POST[url]))include("bd.php");

//exit;
if (!empty($_POST[url])) $page = substr($_POST[url],strlen($home_url),strlen($_POST[url])-strlen($home_url)); else $page = $_GET['route'];

require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;

$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();

if(!empty($page)) {
    $arr = explode( '/', trim($page,'/') );
    $count = count($arr); #смотрим сколько там всего записей
	$k=-1; $page = '';
	do
	{
		$k++;
		$arr[$k] = htmlspecialchars($arr[$k],ENT_QUOTES);
		$page.='/'.$arr[$k];
		$page = substr($page,1,strlen($page));
		if ($arr[$k]=='confirm') break;
		if ($arr[$k]=='news') break;
		if ($arr[$k]=='team') break;
		if ($arr[$k]=='player') break;
		if ($arr[$k]=='leaga') break;
	}
	while($k<$count-1);
}

if (empty($page))
{
	$page = 'home';
}
$sql_page = mysql_query("SELECT * FROM pages WHERE page='$page'");
if (mysql_num_rows($sql_page)>0)
{
	$page_info = mysql_fetch_array($sql_page);
	
	$title = language($page_info[title_lang],$lang).' - '.$site_name;
	if ($arr[$k]=='confirm')
	{
		if (count($arr)>2) error('fatal',404);
		$code = htmlspecialchars($arr[$k+1],ENT_QUOTES);
		$sql_confirm = mysql_query("SELECT id FROM userlist WHERE code='$code'");
		if (mysql_num_rows($sql_confirm)>0)
		{
			$conf = mysql_fetch_array($sql_confirm);
			$_SESSION[user] = $conf[id];
			header( 'Location: '.$home_url, true, 301 );
		}
		else
		{
	echo error('fatal',404);
	
	exit;
		}
	}
	if($arr[$k]=='team')
	{
		if (count($arr)!==2) error('fatal',404);
		$id = htmlspecialchars($arr[$k+1],ENT_QUOTES);
		if (!empty($id))
		{
		$sql_confirm = mysql_query("SELECT echip_id FROM comands WHERE echip_id='$id'");
		if (mysql_num_rows($sql_confirm)>0)
		{
			$pp = mysql_fetch_array($sql_confirm);
			$title = language($pp[echip_id],$lang).' - '.$title;
		}
		else
		{
			echo '<html><head>
	<link rel="stylesheet" href="'.$home_url.'/buianov.css"></head>
	<body>';
	echo error('fatal',404);
	echo'</body></html>';
	exit;
		}
		}
	}
	 
	if($arr[$k]=='player')
	{
		if (count($arr)!==2) error('fatal',404);
		$id = htmlspecialchars($arr[$k+1],ENT_QUOTES);
		if (!empty($id))
		{
			$sql_player = mysql_query("SELECT id FROM players WHERE player='$id'");
			if (mysql_num_rows($sql_player)>0)
			{
				$title = language('name '.$id,$lang).' '.language('sname '.$id,$lang).' - '.$title;
			}else error('fatal',404);
		}
		else
		{
			echo '<html><head>
	<link rel="stylesheet" href="'.$home_url.'/buianov.css"></head>
	<body>';
	echo error('fatal',404);
	echo'</body></html>';
	exit;
		}
	}

	
	if($arr[$k]=='news')
	{
		if (count($arr)>2) error('fatal',404);
		$id = htmlspecialchars($arr[$k+1],ENT_QUOTES);
		if (!empty($id))
		{
		$sql_confirm = mysql_query("SELECT title_lang FROM news WHERE abb='$id'");
		if (mysql_num_rows($sql_confirm)>0)
		{
			$pp = mysql_fetch_array($sql_confirm);
			$title = language($pp[title_lang],$lang).' - '.$title;
		}
		else
		{
	echo error('fatal',404);
	exit;
		}
		}
	
	} 
}
else
{
	echo error('fatal',404);
	exit;
}
if($page=='home'||($page=='news'&&empty($id))){ ?>
			<img src="<?echo $home_url;?>logo.png" width="35%" style="margin-top:1em;text-align:center;margin-left:35%;">
			<section id="one">
			<?
			$sql_news = mysql_query("SELECT * FROM news order by val desc LIMIT 15");
			if (mysql_num_rows($sql_news)>0)
			{
				include("news.php");
			}
			?>

			</section>
			<? }
if ($page=='news' and !empty($id))
{
	
	$sql_news = mysql_query("SELECT * FROM news WHERE abb='$id'");
	if (mysql_num_rows($sql_news)>0) $news = mysql_fetch_array($sql_news);
	$sql_ip = mysql_query("SELECT id FROM views WHERE ip='$ip' and type='n' and fid='$news[id]'");
		if (mysql_num_rows($sql_ip)>0) $sql_views = mysql_query("SELECT id FROM views WHERE type='n' and fid='$news[id]'"); else {
			$sql_ins = mysql_query("INSERT INTO views(ip,type,fid)VALUES('$ip','n','$news[id]')");
			$sql_views = mysql_query("SELECT id FROM views WHERE type='n' and fid='$news[id]'");}
			$lst = '';
			$ex_tags = explode(',',$news[tags]); $m=0; $fnd = 0;
			do
			{
				$fnd=0;
				$sql_comand = mysql_query("SELECT title,logo FROM comands WHERE echip_id='$ex_tags[$m]'");
				if (mysql_num_rows($sql_comand)>0)
				{
					$cmd = mysql_fetch_array($sql_comand);
					$fnd=1;
					$lst.= '<table style="width:auto;border:none;cursor:pointer;display:inline-block" onclick=open_("'.$home_url.'team/'.$ex_tags[$m].'")><tr><td width="3em"><img src="'.$cmd[logo].'" style="max-width:5em"></td></tr><tr><td valign=middle align=left style="padding-left:1em;">'.language($ex_tags[$m],$lang).'</td></tr></table>';
				}
				
				$sql_player = mysql_query("SELECT name,familia,img FROM players WHERE player='$ex_tags[$m]'");
				if (mysql_num_rows($sql_player)>0)
				{
					$cmd = mysql_fetch_array($sql_player);
					$fnd=1;
					$lst.= '<table style="width:auto;border:none;cursor:pointer;display:inline-block" onclick=open_("'.$home_url.'player/'.$ex_tags[$m].'")><tr><td width="3em"><img src="'.$cmd[img].'" style="max-width:5em"></td></tr><tr><td valign=middle align=left style="padding-left:1em;">'.language('name '.$ex_tags[$m],$lang).' '.language('sname '.$ex_tags[$m],$lang).'</td></tr></table>';
				}
				
				$sql_comand = mysql_query("SELECT logo FROM leags WHERE leaga_id='$ex_tags[$m]'");
				if (mysql_num_rows($sql_comand)>0)
				{
					$fnd=1;
					$cmd = mysql_fetch_array($sql_comand);
					$ls = explode(".",language($ex_tags[$m],$lang));
					if (count($ls)>1) $ls = $ls[0]; else $ls = language($ex_tags[$m],$lang);
					$lst.= '<table style="width:auto;cursor:pointer;display:inline-block" onclick=open_("'.$home_url.'leaga/'.$ex_tags[$m].'")><tr><td width="3em"><img src="http://soccer-today.ru/images/leags/'.$cmd[logo].'" style="max-width:5em"></td></tr><tr><td valign=middle align=left style="padding-left:1em">'.$ls.'</td></tr></table>';
				}
				if ($fnd==0) $lst.='<font><i>'.$ex_tags[$m].'</i></font>';
				$m++;
			}
			while($m<count($ex_tags));
			?><section id="one"><header class="major" style="margin-bottom:3em"><h2 style="border-bottom:1px solid #ccc;color:#000">
			<?
			echo language($news[title_lang],$lang);
			?>
			<font style="position:absolute;display:inline-block;font-size:0.4em;right:2em;"> <span class="icon fa-eye" id="views" style="margin-right:1em"> <? echo mysql_num_rows($sql_views); ?> </span><span class="icon fa-calendar"> <? $dt = explode("-",$news['date']); echo $dt[2].'.'.$dt[1].'.'.$dt[0]; ?> </span></font>
			</h2>
			</header>
			<? echo '<font style=color:#666;fon-size:5em;font-family:"Montserrat">'.language($news[text_lang],$lang).'</font>'; ?>
			<h2 style="border-bottom:1px solid #ccc;margin-top:3em"><? echo language('comments',$lang); ?></h2>
			<div id="comments">
			<?
			include("comm.php")
			?>
			</div>
			<?
			if (!empty($_SESSION[user]))
			{
			?>
			<textarea id="com" style="width:100%;height:5em;"></textarea>
			<div id="ad_" style="text-align:center;margin-top:3em;"><button class="mybutton" onclick=add_comment()><? echo language('add comment',$lang) ?></button></div>
			<? }?>
			<script>
			$("#lg").html('<? echo $lst; ?>');
			upd_com();update_comment='y';
			</script>
			</section>
<?	
	
}
if ($page=='team')
{
		if ($deviceType=='computer'){
		$sql_echip = mysql_query("SELECT * FROM comands WHERE echip_id='$id'");
		$echip = mysql_fetch_array($sql_echip);
		include("verific.php");
		
		echo '<font style=background-image:url("http://i.eurosport.com//2014/02/25/1191408-24905958-1600-900.jpg");background-position:bottom;width:75%;display:block;height:20em;position:absolute;margin-top:-12px;margin-left:-4em;>
		<table height="20em;"><tr><td style="width:16em;text-align:center;vertical-align:middle;color:white">
		<img src="'.$echip[logo].'" style="max-width:12em;max-height:12em;">
		<p style="margin-left:2em;text-align:center;"><b style="text-shadow:0 3px 5px #000;font-size:2em;color:white">'.language($id,$lang).'</b></p>
		'.language('stadium',$lang).': '.language('stadium '.$id,$lang).', '.language('city '.$id,$lang).'<br>'.language('found',$lang).': '.$echip[found].'</td><td align=center style="color:white;vertical-align:middle" >';
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
			echo '<p style="font-size:2em;margin:0">'.language('hero',$lang).'</p>';
			echo'<table width="auto" style="width:80%;">
  <tbody><tr>
    <td align="center" valign="top"><img src="'.$the_best[img].'" style="max-width:7em;max-height:7em"><br><a onclick=open_("'.$home_url.'player/'.$the_best[player].'") style="color:white;cursor:pointer">'.language('name '.$the_best[player],$lang).' '.language('sname '.$the_best[player],$lang).'</a></td>
    <td valign="top"><font class="info_small"><strong class="strong_big">'.$the_best[goal_leag].'</strong><br>'.language('goal_leag',$lang).'</font><font class="info_small"><strong class="strong_big">';
	if($the_best[goal_eu]>0) echo $the_best[goal_eu]; else echo $the_best[pas_leag];
	echo'</strong><br>';
	if ($the_best[goal_eu]>0) echo language('goal_eu',$lang); else echo language('pas_leag',$lang);
	echo'</font><br><br><font class="info_small"><strong class="strong_big">'.$g_liga.'</strong><br>'.language('games_leag',$lang).'</font><font class="info_small"><strong class="strong_big">'.$time_.'</strong><br>'.language('minutes played',$lang).'</font></td>
  </tr>
</tbody></table>';
		}
		echo'</td></tr></table>
		
		</font>
		';
		if ($deviceType=='computer'){$em_ = '1em';}else{$em_='0.7em';}
		echo'
		<div id="ll" style="position:absolute;top:25em;width:calc(75% - 8em);">
		<div class="mobile_c" style="margin-bottom:5em;width:100%;text-align:center;font-size:'.$em_.'"><font class="active" id="the_best_f" onclick=ln("news")>'.language('news',$lang).'</font> <font id="team_f" onclick=ln("team")>'.language('team',$lang).'</font> <font id="statistic_f" onclick=ln("statistic")>'.language('statistic',$lang).'</font> <font id="statistic_f" onclick=ln("calendar")>'.language('calendar',$lang).'</font> <font id="statistic_f" onclick=ln("table")>'.language('online table',$lang).'</font></div>';
			
			$sql_news = mysql_query("SELECT * FROM news WHERE (SELECT FIND_IN_SET('$echip[category]',category)) or (SELECT FIND_IN_SET('$echip[cup]',category)) or (SELECT FIND_IN_SET('$echip[eu_ch_l]',category)) or (SELECT FIND_IN_SET('$id',tags)) or (SELECT FIND_IN_SET('$echip[eu_ch_l]',tags)) or (SELECT FIND_IN_SET('$echip[cup]',tags))   order by val desc LIMIT 15");
			if (mysql_num_rows($sql_news)>0)
			{
				include("news.php");
			}
			echo'</div>';
			$nv='';
			$sql_goals = mysql_query("SELECT * FROM players WHERE echip='$id' and goal_leag>'0' ORDER BY goal_leag DESC");
			if (mysql_num_rows($sql_goals)>0)
			{
				$goals_ = mysql_fetch_array($sql_goals);
				do
				{
					$ex_games = explode("/",$goals_[games]);
						$nv.='<table><tr><td style="vertical-align:middle;max-width:4em;max-height:4em;width:4em;"><img src="'.$goals_[img].'" style="max-width:4em;max-height:4em"></td><td style="vertical-align:middle;text-align:left;padding-left:1em"><a href="'.$home_url.'player/'.$goals_[player].'">'.language('name '.$goals_[player],$lang).' '.language('sname '.$goals_[player],$lang).'</a><span style="position:absolute;right:3%;display:inline-block;color:orange;font-weight:bold">'.$goals_[rating].'</span><div>'.language('goals',$lang).': '.$goals_[goal_leag].' <font style="margin-left:3%;display:inline-block">'.language('games',$lang).': '.$ex_games[0].'</font></div></td></tr></table><br>';
					
				}
				while($goals_ = mysql_fetch_array($sql_goals));
			}
			?>
			
			<script>
			$("#lg").html('<? echo $nv; ?>');
			view_team='y';team();
			</script>
			<?
		}
		else
		{
			
			$sql_echip = mysql_query("SELECT * FROM comands WHERE echip_id='$id'");
		$echip = mysql_fetch_array($sql_echip);
		include("verific.php");
		if ($deviceType=='computer'){$em_ = '1em';}else{$em_='0.7em';}
			
			echo '<p style="margin-left:2em;text-align:center;"><img src="'.$echip[logo].'" style="max-width:12em;max-height:12em;"><br><b style="font-size:2em;color:#666">'.language($id,$lang).'</b></p>
		'.language('stadium',$lang).': '.language('stadium '.$id,$lang).', '.language('city '.$id,$lang).'<br>'.language('found',$lang).': '.$echip[found];
		echo'<div id="ll">
			<div class="mobile_c" style="margin-bottom:5em;width:100%;text-align:center;font-size:'.$em_.'"><font id="the_best_f" onclick=ln("hero")>'.language('hero',$lang).'</font> <font id="the_best_f" onclick=ln("news")>'.language('news',$lang).'</font> <font id="team_f" onclick=ln("team")>'.language('team',$lang).'</font> <font id="statistic_f" onclick=ln("statistic")>'.language('statistic',$lang).'</font> <font id="statistic_f" onclick=ln("calendar")>'.language('calendar',$lang).'</font> <font id="statistic_f" onclick=ln("table")>'.language('online table',$lang).'</font> <font id="statistic_f" onclick=ln("goal")>'.language('bombard',$lang).'</font></div>';
		
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
			//echo '<p style="font-size:2em;margin:0">'.language('hero',$lang).'</p>';
			?>
			<script>$("#lg").html('');</script>
			<?
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
		echo '</div>';
	}
	
	
}

if ($page=='player'&&!empty($id))
{
	$sql_player = mysql_query("SELECT * FROM players WHERE player='$id'");
	if(mysql_num_rows($sql_player)>0)
	{
		$player = mysql_fetch_array($sql_player);
	}
}

if ($page=='login')
{
	?>
	
<?if ($deviceType=='computer'){?>	<mtitle><? echo language('autorize',$lang);?><close onclick=close_modal_window()></close></mtitle>
	<span class="input input--madoka" style="padding-left:0px;margin-left:1%;margin-bottom:20px;">
					<input class="input__field input__field--madoka" type="text" id="email" style="outline: none;width:100%" onkeypress="runScript(event,'l')"/>
					<label class="input__label input__label--madoka" for="email">
						<svg class="graphic graphic--madoka" width="100%" height="100%" viewBox="0 0 404 77" preserveAspectRatio="none">
							<path d="m0,0l404,0l0,77l-404,0l0,-77z"/>
						</svg>
						<span class="input__label-content input__label-content--madoka">E-mail</span>
					</label>
				</span>
	<span class="input input--madoka" style="padding-left:0px;margin-left:1%;margin-bottom:20px;">
					<input class="input__field input__field--madoka" type="password" id="password" style="outline: none;" onkeypress="runScript(event,'l')"/>
					<label class="input__label input__label--madoka" for="password">
						<svg class="graphic graphic--madoka" width="100%" height="100%" viewBox="0 0 404 77" preserveAspectRatio="none">
							<path d="m0,0l404,0l0,77l-404,0l0,-77z"/>
						</svg>
						<span class="input__label-content input__label-content--madoka"><? echo  language('password',$lang) ?></span>
					</label>
				</span>
				<div id="alerts"></div>
				<div style="text-align:center"><button class="mybutton" onclick=login()><? echo language('enter',$lang) ?></button> <button class="mybutton"  onclick=modal_window("register","350px","530px")><? echo language('register',$lang) ?></button></div>
<? }else{
	?>
	<input type="text" id="email" style="outline: none;width:100%" onkeypress="runScript(event,'l')" placeholder="E-mail"/>
	<input type="password" id="password" style="outline: none;" onkeypress="runScript(event,'l')" placeholder="<? echo  language('password',$lang) ?>"/>
	<div id="alerts"></div>
				<div style="text-align:center"><button class="mybutton" onclick=lg()><? echo language('enter',$lang) ?></button> <button class="mybutton"  onclick=open_("<? echo $home_url.'register';?>")><? echo language('register',$lang) ?></button></div>	
	<?
	
}?>
				<input id="translate" type="hidden" value="E-mail,<? echo language('password',$lang) ?>,<? echo language('is empty',$lang) ?>,<? echo language('less',$lang) ?>,<? echo language('symbols',$lang) ?>,<? echo language('invalid',$lang) ?>">
	
<? }?>