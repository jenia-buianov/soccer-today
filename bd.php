<?php
function error($type,$error_id)
{
	if ($type=='fatal')
	{
		if ($error_id=='edv') echo '<system_error>Empty database parameters for connecting to database</system_error>';
		if ($error_id=='connectdb') echo '<system_error>The system cannot connect to database</system_error>';
		if ($error_id=='404') echo '<system_error>The system cannot find this page or you not allowed</system_error>';
		exit;
	}
}
$db = mysql_connect ("localhost","root","");

if (!$db) error('fatal','conncetdb');
$bd = mysql_select_db ("soccer",$db);
if (!$bd) error('fatal','conncetdb');

mysql_query("SET NAMES UTF-8");
mysql_query("SET CHARACTER SET 'UTF8'");

$sezon = '2015-2016';

$web_page = $_SERVER['REQUEST_URI'];
$home_url = 'http://'.$_SERVER['HTTP_HOST'].'/sc/';
$site_url = 'Soccer-Today';
$site_name = 'Soccer-Today';
$page_now = $web_page;
$t_now = date('H:i:s');
$date = date('Y-m-d');
$hour = date('H');
$day = date('d');
$ip = $_SERVER['REMOTE_ADDR'];
$ya = date('Y');
$minutes = date('i');
$month = date('m');
$visit = date('s')+date('i')*60+(date('H')*3600)+($day*3600*24)+($month*30*3600*24)+($ya*12*30*3600*24);
$datetime = date('d').'.'.$month.'.'.$ya.' '.$t_now;
if($web_page=='/riddle/'){$web_page='';}else{$web_page = substr($web_page,8,strlen($web_page)-8);}
$page_now = $web_page;

$mymail = 'jenia.don.bosco@gmail.com';
$mypassword = 'Qq4541201096';
$title_mail = 'Буянов Евгений';
$mail_host = 'smtp.gmail.com';
$mail_port = '587';
$mail_type = 'tls';

$lang = 'ru';
function language($id,$ln)
{
	
	$sql_text = mysql_query("SELECT text FROM translation WHERE title='$id' and lang='$ln'");
	if (mysql_num_rows($sql_text)>0)
	{
		$text = mysql_fetch_array($sql_text);
		return $text['text'];
	}
	else{
   $sql_1 = mysql_query("SELECT text FROM translation WHERE title='$id'");
   $tt = mysql_fetch_array($sql_1);
   $curl = curl_init();
   $tt1 = ''; $i=0;
   $e1 = explode(" ",$tt[text]);
   if (count($e1)>0)
   {
	   while($i<count($e1))
	   {
		   $tt1.=$e1[$i].'%20';
		   $i++;
	   }
	   $tt[text] = $tt1;
   }
   
   curl_setopt($curl, CURLOPT_URL, 'https://translate.google.ru/translate_a/single?client=t&sl=auto&tl='.$ln.'&hl=ru&dt=bd&dt=ex&dt=ld&dt=md&dt=qca&dt=rw&dt=rm&dt=ss&dt=t&dt=at&ie=UTF-8&oe=UTF-8&otf=2&srcrom=0&ssel=0&tsel=4&kc=1&tk=896930|757455&q='.$tt[text]);
   curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
   $data = curl_exec($curl);
   curl_close($curl);
   $spl = explode('",',$data);
   $spl = explode('"',$spl[0]);
   $data = trim(strtoupper($spl[1][0]).substr($spl[1],1,strlen($spl[1])));
  
  $ins = mysql_query("INSERT INTO translation(title,text,lang)VALUES('$id','$data','$ln')");
   return $data;
	}
}


function send_mail($mail_to,$mail_from,$name_to,$name_from,$mail_host,$mail_type,$mail_port,$mail_password,$subject,$message)
{
	require_once 'PHPMailer.php';
$mail = new PHPMailer;
$mail->isSMTP();

$mail->SMTPDebug = 0;
$mail->Debugoutput = 'html';
$mail->Host = $mail_host;
$mail->Port = $mail_port;
$mail->SMTPSecure = $mail_type;
$mail->SMTPAuth = true;
$mail->Username = $mail_from;
$mail->Password = $mail_password;
$mail->setFrom($mail_from, $name_from);
$mail->addReplyTo($mail_from, $name_from);
$mail->addAddress($mail_to, $name_to);
$mail->CharSet = "UTF-8";
$mail->Subject = $subject;
$body =  $message;
$body = mb_convert_encoding($body, mb_detect_encoding($body), 'UTF-8');
$mail->msgHTML($body);
$mail->AltBody = 'This is a plain-text message body';
if (!$mail->send()) {
    return "Mailer Error: " . $mail->ErrorInfo;
}else{return 'ok';}
}
function time_elapsed_string($datetime,$lang, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => language('hours',$lang),
        'i' => language('minutes',$lang),
        's' => language('secundes',$lang),
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v;
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) : 'just now';
}




if (empty($_SESSION['user']))
{
	
	$sql_lng = mysql_query("SELECT lang FROM quest WHERE ip='$ip' ORDER BY id DESC LIMIT 1");
	if (mysql_num_rows($sql_lng)>0){
		
		$lng = mysql_fetch_array($sql_lng);
		$lang = $lng['lang'];
	}
	$sql_quest = mysql_query("INSERT INTO quest(page,ip,val,lang,datetime)VALUES('$page_now','$ip','$visit','$lang','$datetime')");
}
else
{
	$sql_lng = mysql_query("SELECT lang FROM session WHERE uid='$_SESSION[user]' ORDER BY id DESC LIMIT 1");
	if (mysql_num_rows($sql_lng)>0){
		
		$lng = mysql_fetch_array($sql_lng);
		$lang = $lng['lang'];
	}
	$sql_quest = mysql_query("INSERT INTO session(page,ip,val,lang,uid,datetime)VALUES('$page_now','$ip','$visit','$lang','$_SESSION[user]','$datetime')");
	
}
?>