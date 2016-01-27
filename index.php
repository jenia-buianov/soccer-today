<?php
session_start();

if(!empty($_GET['route'])) {
    $arr = explode( '/', trim($_GET['route'],'/') );
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


require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;

$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();

include("bd.php");
//echo $_SESSION[user];
//exit;
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
		if (count($arr)>2) {
			echo '<html><head>
	<link rel="stylesheet" href="'.$home_url.'/buianov.css"></head>
	<body>';
	echo error('fatal',404);
	echo'</body></html>';
	exit;
		}
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
			echo '<html><head>
	<link rel="stylesheet" href="'.$home_url.'/buianov.css"></head>
	<body>';
	echo error('fatal',404);
	echo'</body></html>';
	
	exit;
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
	
	if($arr[$k]=='team')
	{
		if (count($arr)!==2) {
			echo '<html><head>
	<link rel="stylesheet" href="'.$home_url.'/buianov.css"></head>
	<body>';
	echo error('fatal',404);
	echo'</body></html>';
	exit;
		}
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
	
	if($arr[$k]=='news')
	{
		if (count($arr)>2) {
			echo '<html><head>
	<link rel="stylesheet" href="'.$home_url.'/buianov.css"></head>
	<body>';
	echo error('fatal',404);
	echo'</body></html>';
	exit;
		}
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
			echo '<html><head>
	<link rel="stylesheet" href="'.$home_url.'/buianov.css"></head>
	<body>';
	echo error('fatal',404);
	echo'</body></html>';
	exit;
		}
		}
	}
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

?>
<!DOCTYPE HTML>
<!--
	Strata by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title><? echo $title; ?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
   <script>
   home_url = '<? echo $home_url;?>';
   page = '<? echo $page; ?>';
</script>   
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
		<link rel="stylesheet" href="<? echo $home_url; ?>buianov.css" />
		<link rel="stylesheet" href="<? echo $home_url; ?>assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body id="top">

		<!-- Header -->
			<header id="header">
			<a href="<? echo $home_url; ?>news/"><? echo language('news',$lang); ?></a>
			<a href="<? echo $home_url; ?>transfers/"><? echo language('transfers',$lang); ?></a>
			<a href="<? echo $home_url; ?>results/"><? echo language('results',$lang); ?></a>
			<a href="<? echo $home_url; ?>games/"><? echo language('matches',$lang); ?></a>
			<?
			if ($deviceType=='computer')
			{
				$sql_table = mysql_query("SELECT DISTINCT leaga FROM `table`");
				if (mysql_num_rows($sql_table)>0)
				{
					$lg =  ''; $k=0;
					echo '<select id="table" onchange=leaga() style="margin-top:2em">';
					$table = mysql_fetch_array($sql_table);
					do
					{
						if ($k==0) $lg=$table[leaga]; $k++;
						echo '<option value="'.$table[leaga].'">'.language($table[leaga],$lang).'</option>';
					}
					while($table = mysql_fetch_array($sql_table));
					echo '</select>';
				}
				echo '<div id="lg" style="overflow-y:auto;height:70%;display:block;">';
				echo '<a onclick=open_("'.$home_url.'leaga/'.$lg.'")>'.language('full table',$lang).'</a>';
				echo'<table style="background-color:transparent">';
				$sql_tb = mysql_query("SELECT * FROM `table` WHERE leaga='$lg' and sezon='$sezon' ORDER BY bonus DESC, wins DESC, ball_w");
				if (mysql_num_rows($sql_tb)>0)
				{
					$table = mysql_fetch_array($sql_tb);$k=1;
					do
					{
						$sql_echip = mysql_query("SELECT logo FROM comands WHERE echip_id='$table[echip]'");
						$echip_ = mysql_fetch_array($sql_echip);
						echo '<tr><td style="background-color:transparent;vertical-align:middle;font-size:0.8em" width=10%>'.$k.'</td><td width="3em"><a href="'.$home_url.'team/'.$table[echip].'" style="border:none"><img src="'.$echip_[logo].'" style="max-width:2em;max-height:2em;"></a></td><td align=left style="vertical-align:middle;padding-left:1em;"><a href="'.$home_url.'team/'.$table[echip].'" style="border:none;">'.language($table[echip],$lang).'</a></td><td style="vertical-align:middle;font-weight:bold;color:white">'.$table[bonus].'</td></tr>';
						$k++;
					}
					while($table = mysql_fetch_array($sql_tb));
					echo '</table>';
				}
				echo'</div>';
			}else{echo'<div id="lg" style="overflow-y:auto;height:70%;display:block;"></div>';}
			?>			
			<? if (empty($_SESSION['user'])){ ?><font class="button" onclick=<?
			if ($deviceType=='computer'){echo 'modal_window("login","350px","400px")';}else{echo 'open_("'.$home_url.'login/")';}
?>			style="background-color:white;color:#000"><? echo language('autorize',$lang); ?></font>
			<? }
			else{
				
				if ($deviceType!=='computer')
				{
					echo '<a href="'.$home_url.'profile">'.language('profile',$lang).'</a> ';
					echo '<a href="'.$home_url.'fanzone">'.language('fanzone',$lang).'</a> ';
					echo '<a href="'.$home_url.'settings">'.language('settings',$lang).'</a>';
					echo '<a href="'.$home_url.'logout">'.language('sing out',$lang).'</a>';
				}
			}?>
			</header>

		<!-- Main -->
			
			<div id="main"<?if (!empty($_SESSION[user])and$deviceType=='computer') echo 'style="top:45px;margin-top:60px;"';?>>
			<? include("open_w.php") ?>
			
			</div>
<?
			if (!empty($_SESSION[user])and$deviceType=='computer')
			{
				echo '<top_menu>';
				
					echo '<a href="'.$home_url.'profile">'.language('profile',$lang).'</a> ';
					echo '<a href="'.$home_url.'fanzone">'.language('fanzone',$lang).'</a> ';
					echo '<a href="'.$home_url.'settings">'.language('settings',$lang).'</a>';
					echo '<a href="'.$home_url.'logout">'.language('sing out',$lang).'</a>';
				echo'</top_menu>';
			}
			?>
		<!-- Footer -->
			<footer id="footer">
				<ul class="copyright">
					<li>&copy; Soccer-Today</li><li>Made by: <a href="http://buianov.com">Platform "BUIANOV"</a></li>
				</ul>
			</footer>


		<!-- Scripts -->
			<script src="<? echo $home_url; ?>assets/js/jquery.min.js"></script>
			<script src="<? echo $home_url; ?>assets/js/jquery.poptrox.min.js"></script>
			<script src="<? echo $home_url; ?>assets/js/skel.min.js"></script>
			<script src="<? echo $home_url; ?>assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="<? echo $home_url; ?>assets/js/main.js"></script>
			<script src="<? echo $home_url; ?>assets/js/java.js"></script>
<script>
<?
if ($page=='news'and!empty($id)){ ?> $("#lg").html('<? echo $lst;?>'); update_comment='y'; upd_com(); <?}
if ($page=='team'and!empty($id)&&$deviceType=='computer'){ ?> $("#lg").html('<? echo $nv;?>'); view_team='y'; team();<?}
?>
        $(document).ready(function() {
			if(count(window.location.toString().split("ckattemp"))==2){spl = window.location.toString().split("ckattemp"); window.location.toString() = spl[0];}
			$('a').click(function() {
                var url = $(this).attr('href');
				if(count(window.location.toString().split("ckattemp"))==2){spl = window.location.toString().split("ckattemp"); window.location.toString() = spl[0];}
				if(window.location.toString()!==url)
				{
				$('#main').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
	

				$.post(home_url+'open_w.php', {url:url},function(data){
				if (data=='<system_error>The system cannot find this page or you not allowed</system_error>'){ $('body').html(data);} else $('#main').html(data);

	});
                

                if(url != window.location){
                    window.history.pushState(null, null, url);
                }

                return false;
				}
			});
			
            $(window).bind('popstate', function() {
				
				if (window.location.toString()!==location.path){
                $.post(home_url+'open_w.php', {url:location.path},function(data){
				if (data=='<system_error>The system cannot find this page or you not allowed</system_error>'){ $('body').html(data);} else $('#main').html(data);

	});
				}
            });
        });
    </script>
	</body>
</html>