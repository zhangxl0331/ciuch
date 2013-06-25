<?php if($auth && $auth['uid']==$user['uid']):?>
<?=form_open('doing/add', array('class'=>'post_doing'))?>
	<ul style="margin-bottom: 1em;">
		<li>
			<p><?=validation_errors()?></p>
			<p>		
			<table cellspacing="0" cellpadding="0" width="100%">
			<tr>
			<td><a href="###" id="face" onclick="showFace(this.id, 'message');"><img src="image/facelist.gif" align="absmiddle" /></a></td>
			<?php if(isset($usergroup[$auth['groupid']]['allowdoing']) AND ! empty($usergroup[$auth['groupid']]['allowdoing'])):?>
			<td>
				<?php if($config['questionmode']):?>
				请先回答提问：{{ spam:question }}{{ question }}<input type="hidden" value="{{ answer }}" id="verify" name="verify">{{ /spam:question }}
				<?php else:?>
				请先输入验证码：{{ spam:captcha }}{{ image }}<input type="hidden" value="{{ word }}" id="verify" name="verify">{{ /spam:captcha }} 
				<?php endif;?>
				<input type="text" id="seccode" name="seccode" value="" size="10" class="t_input">
			</td>
			<?php endif;?>
			<td align="right">还可输入 <strong id="maxlimit">200</strong> 个字符</td>
			</tr>
			</table>
			<textarea id="message" name="message" onkeyup="textCounter(this, 'maxlimit', 200)" onkeydown="ctrlEnter(event, 'add');" rows="4" style="width:438px; height: 72px;"></textarea>
			<button type="submit" id="add" name="add" class="post_button">发布</button>
			</p>
		</li>
	</ul>
<input type="hidden" name="refer" value="$theurl" />
<?=form_close()?>
<?php endif;?>
<script>
function textCounter(obj, showid, maxlimit) {
	var len = strLen(obj.value);
	var showobj = document.getElementById(showid);
	if(len > maxlimit) {
		obj.value = getStrbylen(obj.value, maxlimit);
		showobj.innerHTML = '0';
	} else {
		showobj.innerHTML = maxlimit - len;
	}
	if(maxlimit - len > 0) {
		showobj.parentNode.style.color = "";
	} else {
		showobj.parentNode.style.color = "red";
	}
	
}
function getStrbylen(str, len) {
	var num = 0;
	var strlen = 0;
	var newstr = "";
	var obj_value_arr = str.split("");
	for(var i = 0; i < obj_value_arr.length; i ++) {
		if(i < len && num + byteLength(obj_value_arr[i]) <= len) {
			num += byteLength(obj_value_arr[i]);
			strlen = i + 1;
		}
	}
	if(str.length > strlen) {
		newstr = str.substr(0, strlen);
	} else {
		newstr = str;
	}
	return newstr;
}
function byteLength (sStr) {
	aMatch = sStr.match(/[^\x00-\x80]/g);
	return (sStr.length + (! aMatch ? 0 : aMatch.length));
}
function strLen(str) {
	var charset = document.charset; 
	var len = 0;
	for(var i = 0; i < str.length; i++) {
		len += str.charCodeAt(i) < 0 || str.charCodeAt(i) > 255 ? (charset == "utf-8" ? 3 : 2) : 1;
	}
	return len;
}
</script>