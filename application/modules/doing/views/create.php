<script language="javascript" src="image/editor/editor_function.js"></script>
<script type="text/javascript">
	function validate(obj) {
	    var subject = $('subject');
	    if (subject) {
	    	var slen = strlen(subject.value);
	        if (slen < 1 || slen > 80) {
	            alert("标题长度(1~80字符)不符合要求");
	            subject.focus();
	            return false;
	        }
	    }

	    if($('seccode')) {
			var code = $('seccode').value;
			var x = new Ajax();
			x.get('cp.php?ac=common&op=seccode&code=' + code, function(s){
				s = trim(s);
				if(s.indexOf('succeed') == -1) {
					alert(s);
					$('seccode').focus();
	           		return false;
				} else {
					uploadEdit(obj);
					return true;
				}
			});
	    } else {
	    	uploadEdit(obj);
	    	return true;
	    }
	}
	function passwordShow(value) {
		if(value==4) {
			$('span_password').style.display = '';
			$('tb_selectgroup').style.display = 'none';
		} else if(value==2) {
			$('span_password').style.display = 'none';
			$('tb_selectgroup').style.display = '';
		} else {
			$('span_password').style.display = 'none';
			$('tb_selectgroup').style.display = 'none';
		}
	}
	function edit_album_show(id) {
		var obj = $('uchome-edit-'+id);
		if(id == 'album') {
			$('uchome-edit-pic').style.display = 'none';
		}
		if(id == 'pic') {
			$('uchome-edit-album').style.display = 'none';
		}
		if(obj.style.display == '') {
			obj.style.display = 'none';
		} else {
			obj.style.display = '';
		}
	}
	function getgroup(gid) {
		if(gid) {
			var x = new Ajax();
			x.get('cp.php?ac=privacy&op=getgroup&gid='+gid, function(s){
				$('target_names').innerHTML += s;
			});
		}
	}
</script>

<h2 class="title"><img src="image/app/blog.gif" />日志</h2>
<div class="tabs_header">
	<ul class="tabs">
		{{ if blog:blogid }}
		<li class="active"><a href="cp.php?ac=blog&blogid=$blog[blogid]"><span>编辑日志</span></a></li>
		{{ endif }}
		<li{{ if not blog:blogid }} class="active"{{ endif }}><a href="cp.php?ac=blog"><span>发表新日志</span></a></li>
		<li><a href="cp.php?ac=import"><span>日志导入</span></a></li>
		<li><a href="space.php?uid=$space[uid]&do=blog&view=me"><span>返回我的日志</span></a></li>
	</ul>
</div>


<style>
	.userData {behavior:url(#default#userdata);}
</style>

<div class="c_form">
	<form method="post" action="{{ url:base }}blog/create?id={{ blog:blogid }}" enctype="multipart/form-data">
		<table cellspacing="4" cellpadding="4" width="100%" class="infotable">
			<tr>
				<td>
					<input type="text" class="t_input" id="subject" name="subject" value="{{ blog:subject }}" size="60" onblur="relatekw();" />
				</td>
			</tr>
			<tr>
				<td>
				<textarea class="userData" name="message" id="uchome-ttHtmlEditor">{{ blog:message }}</textarea>
				</td>
			</tr>
		</table>
		<input type="hidden" name="blogsubmit" value="true" />
		<input type="submit" id="blogbutton" name="blogbutton" value="提交发布" />
		<input type="hidden" name="formhash" value="{{ global:formhash }}" />
	</form>
</div>