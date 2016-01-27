
function count(mixed_var, mode) {
  var key, cnt = 0;
  if (mixed_var === null || typeof mixed_var === 'undefined') {
    return 0;
  } else if (mixed_var.constructor !== Array && mixed_var.constructor !== Object) {
    return 1;
  }
  if (mode === 'COUNT_RECURSIVE') {
    mode = 1;
  }
  if (mode != 1) {
    mode = 0;
  }

  for (key in mixed_var) {
    if (mixed_var.hasOwnProperty(key)) {
      cnt++;
      if (mode == 1 && mixed_var[key] && (mixed_var[key].constructor === Array || mixed_var[key].constructor ===
        Object)) {
        cnt += this.count(mixed_var[key], 1);
      }
    }
  }
  return cnt;
}

w = screen.width;
h = screen.height;

update_comment = 'n';
view_team = 'n';

function leaga()
{
	$('#lg').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
	$.post(home_url+'open.php', {lg:document.getElementById('table').value},function(data){
		$("#lg").html(data);
	});
}

function trm(row)
{
	i=0;
	while(i<count(row))
	{
		str = $("#"+row[i]).val();
		str = str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
		$("#"+row[i]).val(str);
	
		i++;
	}
}
min_pass_length = 6;
min_name_length = 6;
min_email_length = 8;
function register()
{
	alerts = '';
	row = ['email','name'];
	trm(row);
	$('modal #alerts').html('');
	email = document.getElementById('email').value;
	name = document.getElementById('name').value;
	pass1 = document.getElementById('pass').value;
	pass2 = document.getElementById('pass2').value;
	
	translate = document.getElementById('translate').value.split(',');
	
	if (email=='') alerts+=translate[0]+', ';
	if (name=='') alerts+=translate[1]+', ';
	if (pass1=='') alerts+=translate[2]+', ';
	if (pass2=='') alerts+=translate[3]+', ';
	
	if (alerts!=='')
	{
		$('modal #alerts').html(alerts.substr(0,alerts.length-2)+' '+translate[4]);
	}else
	{
		
		if (pass1!==pass2) $('modal #alerts').html(translate[5]);
		else
		{
			if (email.length<min_email_length) alerts+=translate[0]+' '+translate[6]+' '+min_email_length+' '+translate[7]+' ';
			if (name.length<min_name_length) alerts+=translate[1]+' '+translate[6]+' '+min_name_length+' '+translate[7]+' ';
			if (pass1.length<min_pass_length) alerts+=translate[2]+' '+translate[6]+' '+min_pass_length+' '+translate[7];
			
			if (alerts!=='') $('modal #alerts').html(alerts); else
			{
				email_is = email.split('@');
				if (parseInt(count(email_is))!==2||parseInt(count(email_is[1].split('.')))==1) {$('modal #alerts').html(translate[8]);}
				else
				{
				
				$('modal  #loading').css('display','block');
				$.post(home_url+'register.php', {name:name,email:email,password:pass1},function(data){
				$('modal  #loading').css('display','none');
				$('modal #alerts').html(data);
					
				});
				}
			}
		}
	}
	
}



function register()
{
	alerts = '';
	row = ['email','name'];
	trm(row);
	$('modal #alerts').html('');
	email = document.getElementById('email').value;
	name = document.getElementById('name').value;
	pass1 = document.getElementById('pass').value;
	pass2 = document.getElementById('pass2').value;
	
	translate = document.getElementById('translate').value.split(',');
	
	if (email=='') alerts+=translate[0]+', ';
	if (name=='') alerts+=translate[1]+', ';
	if (pass1=='') alerts+=translate[2]+', ';
	if (pass2=='') alerts+=translate[3]+', ';
	
	if (alerts!=='')
	{
		$('modal #alerts').html(alerts.substr(0,alerts.length-2)+' '+translate[4]);
	}else
	{
		
		if (pass1!==pass2) $('modal #alerts').html(translate[5]);
		else
		{
			if (email.length<min_email_length) alerts+=translate[0]+' '+translate[6]+' '+min_email_length+' '+translate[7]+' ';
			if (name.length<min_name_length) alerts+=translate[1]+' '+translate[6]+' '+min_name_length+' '+translate[7]+' ';
			if (pass1.length<min_pass_length) alerts+=translate[2]+' '+translate[6]+' '+min_pass_length+' '+translate[7];
			
			if (alerts!=='') $('modal #alerts').html(alerts); else
			{
				email_is = email.split('@');
				if (parseInt(count(email_is))!==2||parseInt(count(email_is[1].split('.')))==1) {$('modal #alerts').html(translate[8]);}
				else
				{
				
				$('modal  #loading').css('display','block');
				$.post(home_url+'register.php', {name:name,email:email,password:pass1},function(data){
				$('modal  #loading').css('display','none');
				$('modal #alerts').html(data);
					
				});
				}
			}
		}
	}
	
}

function login()
{
	alerts = '';
	row = ['email'];
	trm(row);
	$('modal #alerts').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
	email = document.getElementById('email').value;
	pass = document.getElementById('password').value;
	
	translate = document.getElementById('translate').value.split(',');
	
	if (email=='') alerts+=translate[0]+', ';
	if (pass=='') alerts+=translate[1]+', ';
	
	if (alerts!=='')
	{
		$('modal #alerts').html(alerts.substr(0,alerts.length-2)+' '+translate[2]);
	}else
	{
		if (email.length<min_email_length) alerts+=translate[0]+' '+translate[3]+' '+min_email_length+' '+translate[4]+' ';
			if (pass.length<min_pass_length) alerts+=translate[1]+' '+translate[3]+' '+min_pass_length+' '+translate[4];
			
			if (alerts!=='') $('modal #alerts').html(alerts); else
			{
				email_is = email.split('@');
				if (parseInt(count(email_is))!==2||parseInt(count(email_is[1].split('.')))==1) {$('modal #alerts').html(translate[5]);}
				else
				{
				
				$.post(home_url+'login.php', {email:email,password:pass},function(data){
				console.log(data);
				dt = data.split('>');
				ct = parseInt(count(dt))-1;
				if (dt[ct]=='ok'){window.location.reload(true);}else{$('modal #alerts').html(dt[ct]);}
					
				});
				}
			}
		
	}
	
}

function lg()
{
	alerts = '';
	row = ['email'];
	trm(row);
	$('#alerts').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
	email = document.getElementById('email').value;
	pass = document.getElementById('password').value;
	
	translate = document.getElementById('translate').value.split(',');
	
	if (email=='') alerts+=translate[0]+', ';
	if (pass=='') alerts+=translate[1]+', ';
	
	if (alerts!=='')
	{
		$('#alerts').html(alerts.substr(0,alerts.length-2)+' '+translate[2]);
	}else
	{
		if (email.length<min_email_length) alerts+=translate[0]+' '+translate[3]+' '+min_email_length+' '+translate[4]+' ';
			if (pass.length<min_pass_length) alerts+=translate[1]+' '+translate[3]+' '+min_pass_length+' '+translate[4];
			
			if (alerts!=='') $('#alerts').html(alerts); else
			{
				email_is = email.split('@');
				if (parseInt(count(email_is))!==2||parseInt(count(email_is[1].split('.')))==1) {$('#alerts').html(translate[5]);}
				else
				{
				
				$.post(home_url+'login.php', {email:email,password:pass},function(data){
				console.log(data);
				dt = data.split('>');
				ct = parseInt(count(dt))-1;
				if (dt[ct]=='ok'){window.location.replace(home_url);}else{$('#alerts').html(dt[ct]);}
					
				});
				}
			}
		
	}
	
}

count_modal=0;
function close_modal_window()
{
	$("background").hide();
	
}
function modal_window(lnk,width,height)
{
	lnk = home_url+lnk;
	
	if (count_modal==0) {$('html').append('<background><modal></modal></background>');count_modal++;}
	
	if (parseInt(width)<101) w_ = (screen.width*parseInt(width))/200; else w_ = (screen.width - parseInt(width))/2;
	if (parseInt(height)<101) h_ = (screen.height*parseInt(height))/200; else h_ = (screen.height - parseInt(height)-100)/2;
	
	$('modal').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
	$('modal').css('left',w_+'px');
	$('modal').css('width',width);
	$('modal').css('height',height);
	$('modal').css('top',h_+'px');
	$('background').css('display','block');
	$.post(home_url+'open_w.php', {url:lnk},function(data){
		 $('modal').css('background','linear-gradient(to top, #fff 0%, #49bf9d 36%, #0B6C90 100%)')
		 $('modal').html(data);
		
		$(document).keyup(function(e) {
		if (e.keyCode == 27) close_modal_window();   // esc
});
	});
}
min_com = 8;

function ln(id)
{
	$('#ll').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
	
	urls = window.location.toString();
	urls = urls.split('/');
	urls = urls[count(urls)-1];
	url = urls;
	$.post(home_url+'trm.php', {id:id,team:url},function(data){
		$("#ll").html(data);
	});
}
function upd_com()
{
	urls = window.location.toString();
	urls = urls.split('/');
	urls = urls[count(urls)-1];
	id = urls;
	if (update_comment=='y')
	{
	var timerId = setTimeout(function tick() {
	$.post(home_url+'comm.php', {id:id},function(data){
		 $("#comments").html(data);
	});
	timerId = setTimeout(tick, 10000);
}, 10000);
	}else{clearInterval(timerId);}
}

function team()
{
	if (view_team=='y')
	{
	var timerId = setTimeout(function tick() {
	urls = window.location.toString();
	urls = urls.split('/');
	urls = urls[count(urls)-1];
	id = urls;
	$.post(home_url+'team.php', {id:id},function(data){
		 $("#lg").html(data);
	});
	timerId = setTimeout(tick, 60000);
}, 60000);
	}else{clearInterval(timerId);}
}

function add_comment()
{
	text = document.getElementById('com').value;
	if (text.length<min_com) { alert("Minimum length must be "+min_com);}
	else
	{
		urls = window.location.toString();
		urls = urls.split('/');
		urls = urls[count(urls)-1];
		nid = urls;
		$('#ad_').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
		$.post(home_url+'add_com.php', {text:text,nid:nid},function(data){
		 $("#ad_").html(data);
		 document.getElementById('com').value = '';
		 $('#comments').animate({
            scrollTop: 0
        }, 600);
		});
	}
}

function runScript(e,func) {
	art = e.keyCode;
	if (art == 13) {
		if (func=='l') login();
    }
}

function open_(url)
{
	if(window.location.toString()!==url)
	{
			
	$('#main').html('<div style="width:100%;text-align:center"><div class=uil-ellipsis-css style=transform:scale(0.6);><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div><div class="circle"><div></div></div></div></div>');
	

				$.post(home_url+'open_w.php', {url:url},function(data){
				if (data=='<system_error>The system cannot find this page or you not allowed</system_error>'){ $('body').html(data);} else {$('#main').html(data);
				spl = url.split("/");
				//alert(spl[4]);
				if (parseInt(count(spl))==6&&spl[4]=='news'){upd_com();update_comment='y';}else{update_comment='n';}
				if (parseInt(count(spl))==6&&spl[4]=='team'){team();view_team='y';}else{view_team='n';}
				
				}
	});
                

                if(url != window.location){
                    window.history.pushState(null, null, url);
                }

               

            $(window).bind('popstate', function() {
                $.post(home_url+'open_w.php', {url:location.path},function(data){
				if (data=='<system_error>The system cannot find this page or you not allowed</system_error>'){ $('body').html(data);} else {$('#main').html(data);
				}
	});
            });
	}
}