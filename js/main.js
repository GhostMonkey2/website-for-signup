// javascript document
var xmlhttp = false;
if (window.XMLHttpRequest) { 									//Mozilla、Safari等浏览器
	xmlhttp = new XMLHttpRequest();
} 
else if (window.ActiveXObject) { 								//IE浏览器
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	   } catch (e) {}
	}
}

function _(id) {
	return document.getElementById(id);
}

//xml传递get
function xmlsendget(url)
{
	xmlhttp.open('get', url, true);
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4)
		{
			if (xmlhttp.status == 200)
			{
				var msg = xmlhttp.responseText;
				// alert(msg);
				var dataObj = eval("("+msg+")");
				act = dataObj.act.act;
				//console.log(dataObj);
				if (act === 'addsave') reAddSave(dataObj);
				else if (act === 'delsave') reDelSave(dataObj);
				else if (act === 'login') reLogin(dataObj);
				else if (act === 'logout') reLogout(dataObj);
				else if (act === 'reply') reReply(dataObj);
			}
		}
	};
	xmlhttp.send(null);
}

//xml传递post
function xmlsendpost(url, post)
{
	xmlhttp.open('post', url, true);
	xmlhttp.onreadystatechange = function()
	{
		if (xmlhttp.readyState == 4)
		{
			if (xmlhttp.status == 200)
			{
				var msg = xmlhttp.responseText;
				// alert(msg);
				var dataObj = eval("("+msg+")");
				act = dataObj.act.act;
				//console.log(dataObj);
				if (act === 'reply') reReply(dataObj);
				else if (act === 'delsave') reDelSave(dataObj);
				else if (act === 'login') reLogin(dataObj);
				else if (act === 'logout') reLogout(dataObj);
			}
		}
	};
	xmlhttp.send(post);
}


// 添加我的收藏
function addSave(iid)
{
	var url = '/ajaxaction.php?act=addsave&iid=' + iid;
	xmlsendget(url);
}
// 添加我的收藏
function reAddSave(dataObj)
{
	if (dataObj.act.act == 'addsave')
	{
		if (dataObj.state.state == 'OK')
		{
			var obs = _('save' + dataObj.info.iid);
			if (obs.className==='ico_2'){
				obs.className = 'ico_1';
			}else{
				obs.className = 'card01_ico card01_ico8';
			}
			obs.getElementsByTagName('a')[0].title = '点击取消收藏';
		}
		else
		{
			//alert(dataObj.exception.ex);
			_("notice_mask").style.display='block';
			_("reNoticeInfo").innerHTML=dataObj.exception.ex;
			
		}
	}
}

// 删除我的收藏
function delSave(iid)
{
	var url = '/ajaxaction.php?act=delsave&iid=' + iid;
	xmlsendget(url);
}
// 删除我的收藏
function reDelSave(dataObj)
{
	if (dataObj.act.act == 'delsave')
	{
		if (dataObj.state.state == 'OK')
		{
			var obs = _('save' + dataObj.info.iid);
			if (obs.className === 'ico_1'){
				obs.className = 'ico_2';
			}else{
				obs.className = 'card01_ico card01_ico2';
			}
			obs.getElementsByTagName('a')[0].title = '点击收藏本条消息';
		}
	}
}


// 登录
function login()
{
	var url = '/login.php?act=login&uname=' + _('username').value + '&pw=' + _('userpass').value;
	xmlsendget(url);
}
// 登录
function reLogin(dataObj)
{
	if (dataObj.act.act == 'login')
	{
		if (dataObj.state.state == 'OK')
		{
			//_('reInfo').innerHTML = dataObj.userinfo.uname;
			window.location.href='/login.php?act=synLogin&url='+encodeURIComponent(document.location);
		}
		else
		{
			_('reInfo').innerHTML = dataObj.exception.ex;
		}
	}
}

// 注销
function logout()
{
	var url = '/login.php?act=logout';
	xmlsendget(url);
}
// 注销
function reLogout(dataObj)
{
	if (dataObj.act.act == 'logout')
	{
		if (dataObj.state.state == 'OK')
		{
			window.location.href='/login.php?act=synLogout&url='+encodeURIComponent(document.location);
		}
	}
}


// 回复留言
function reply()
{
	var tid = _('tid').value;
	var post = encodeURIComponent(_('messageText').value);
	var url = '/api/reply.php?tid=' + tid + '&message=' + post ;
	//alert(url);
	xmlsendget(url);
	
}
// 回复留言
function reReply(dataObj)
{
	if (dataObj.act.act == 'reply')
	{
		if (dataObj.state.state == 'OK')
		{
			_('reMessageInfo').innerHTML = dataObj.msg;
			_('message_mask').style.display = 'none';
			_('messageText').value = '';
			var src = _('bbsframe').src;
			_('bbsframe').src = src;
			_('reMessageInfo').innerHTML = '';
		}
		else
		{
			_('reMessageInfo').innerHTML = dataObj.exception.ex;
		}
	}
}

