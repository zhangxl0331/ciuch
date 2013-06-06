<?=form_open('member/login', array('id'=>'loginform', 'name'=>'loginform', 'class'=>'c_form'));?>
<table cellpadding="0" cellspacing="0" class="formtable">
	<caption>
		<h2>请登录</h2>
		<p>如果您在本站已拥有帐号，请使用已有的帐号信息直接进行登录即可，不需重复注册。</p>
	</caption>
	<tbody>
	<tr><th width="100"><?=form_label('用户名', 'username');?></th><td><?=form_input(array('name'=>'username', 'id'=>'username', 'class'=>'t_input', 'value'=>$membername, 'tabindex'=>2));?></td></tr>
	<tr><th width="100"><?=form_label('密　码', 'password');?></th><td><?=form_password(array('name'=>'password', 'id'=>'password', 'class'=>'t_input', 'value'=>$password, 'tabindex'=>3));?></td></tr>
	<tr>
		<th width="100">&nbsp;</th>
		<td>
			<?=form_checkbox(array('id'=>'cookietime', 'name'=>'cookietime', 'value'=>315360000, 'checked'=>$cookiecheck, 'style'=>'margin-bottom: -2px'))?><?=form_label('下次自动登录', 'cookietime');?>
		</td>
	</tr>
	</tbody>
	<tr><th width="100">&nbsp;</th><td>
		<?=form_hidden('refer', $refer);?>
		<?=form_submit(array('id'=>'loginsubmit', 'name'=>'loginsubmit', 'value'=>'登录', 'class'=>'submit', 'tabindex'=>5));?>
		<a href="do.php?ac=lostpasswd">忘记密码?</a>
	</td></tr>
</table>
<?=form_hidden('formhash', 111);?></form>

<script type="text/javascript">
	var lastSecCode = '';
	function checkSeccode() {
		var seccodeVerify = $('seccode').value;
		if(seccodeVerify == lastSecCode) {
			return;
		} else {
			lastSecCode = seccodeVerify;
		}
		ajaxresponse('checkseccode', 'op=checkseccode&seccode=' + (is_ie && document.charset == 'utf-8' ? encodeURIComponent(seccodeVerify) : seccodeVerify));
	}
	function ajaxresponse(objname, data) {
		var x = new Ajax('XML', objname);
		x.get('do.php?ac=$_SCONFIG[register_action]&' + data, function(s){
			var obj = $(objname);
			s = trim(s);
			if(s.indexOf('succeed') > -1) {
				obj.style.display = '';
				obj.innerHTML = '<img src="image/check_right.gif" width="13" height="13">';
				obj.className = "warning";
			} else {
				warning(obj, s);
			}
		});
	}
	function warning(obj, msg) {
		if((ton = obj.id.substr(5, obj.id.length)) != 'password2') {
			$(ton).select();
		}
		obj.style.display = '';
		obj.innerHTML = '<img src="image/check_error.gif" width="13" height="13"> &nbsp; ' + msg;
		obj.className = "warning";
	}

</script>

<!--{if $_SGLOBAL['input_seccode']}-->
<script>
$('seccode').style.background = '#FFFFCC';
$('seccode').focus();
</script>
<!--{/if}-->


<div class="c_form">
<table cellpadding="0" cellspacing="0" class="formtable">
	<caption>
		<h2>还没有注册吗？</h2>
		<p>如果还没有本站的通行帐号，请先注册一个属于自己的帐号吧。</p>
	</caption>
	<tr>
		<td>
		<a href="do.php?ac=$_SCONFIG[register_action]" style="display: block; margin: 0 110px 2em; width: 100px; border: 1px solid #486B26; background: #76A14F; line-height: 30px; font-size: 14px; text-align: center; text-decoration: none;"><strong style="display: block; border-top: 1px solid #9EBC84; color: #FFF; padding: 0 0.5em;">立即注册</strong></a>
		</td>
	</tr>
</table>
</div>