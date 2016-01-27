<?php
$news = mysql_fetch_array($sql_news);
				do
				{
					if (!empty($news[category])){
						$cat = language($news[category],$lang);
						$ex_ = explode(".",$cat);
						if (count($ex_)>1) $cat = $ex_[0];
					}else{$cat = language('footbal',$lang);}
					echo '<p onclick=open_("'.$home_url.'news/'.$news[abb].'") style="cursor:pointer"><img src="'.$news[image].'" style="width:100%"></p><header class="major" style="margin-bottom:3em"><h2 style="border-bottom:1px solid #ccc;"><a onclick=open_("'.$home_url.'news/'.$news[abb].'") style="cursor:pointer;border:none">'.language($news[title_lang],$lang).'</a><font style="position:absolute;display:inline-block;font-size:0.4em;right:2em;">'.$cat.'</font></h2></header>';
				}
				while($news = mysql_fetch_array($sql_news));
				?>