{{ if not input:inajax }}
	{{if not nosidebar }}
		{{ if ad:contentbottom }}<br style="line-height:0;clear:both;"/><div id="ad_contentbottom">{{ad/contentbottom}}</div>{{ endif }}
		</div>
		
		
		<div id="bottom"></div>
	</div>
	{{ endif }}
	

	
	
	<div id="footer" title="{{debuginfo}}">
		{{ if template:templates }}
		<div class="chostlp" title="切换风格"><img id="chostlp" src="{$_TPL['default_template']['icon']}" onmouseover="showMenu(this.id)" alt="{$_TPL['default_template']['name']}" /></div>
		<ul id="chostlp_menu" class="chostlp_drop" style="display: none">
		{{ template:templates }}
			<li><a href="cp.php?ac=common&op=changetpl&name=$value[name]" title="$value[name]"><img src="$value[icon]" alt="$value[name]" /></a></li>
		{{ /template:templates }}
		</ul>
		{{ endif }}
		
		<p class="r_option">
			<a href="javascript:;" onclick="window.scrollTo(0,0);" id="a_top" title="TOP"><img src="image/top.gif" alt="" style="padding: 5px 6px 6px;" /></a>
		</p>
		
		{{ if ad:footer }}
		<p style="padding:5px 0 10px 0;">{{ad/footer}}</p>
		{{ endif }}
		
		<p>
			{{ global:config:sitename }} - 
			<a href="mailto:{{ global:config:adminemail }}">联系我们</a>
			{{ if global:config:miibeian }} - <a  href="http://www.miibeian.gov.cn" target="_blank">{{ global:config:miibeian }}</a>{{ endif }}
		</p>
		<p>
			Powered by <a href="http://u.discuz.net" target="_blank"><strong>UCenter Home</strong></a> <span title="{{X_RELEASE;}}">X_VER</span>
			{{ if global:config:licensed }}<a  href="http://license.comsenz.com/?pid=7&host=$_SERVER[HTTP_HOST]" target="_blank">Licensed</a>{{ endif }}
			&copy; 2001-2009 <a  href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>
		</p>
	</div>
</div>

{{ if global:auth:uid }}
{{ if not cookie:checkpm }}
<script language="javascript"  type="text/javascript" src="cp.php?ac=pm&op=checknewpm&rand={{ date:timestamp }}"></script>
{{ endif }}
{{ if not cookie:synfriend }}
<script language="javascript"  type="text/javascript" src="cp.php?ac=friend&op=syn&rand={{ date:timestamp }}"></script>
{{ endif }}
{{ endif }}
{{ if not cookie:sendmail }}
<script language="javascript"  type="text/javascript" src="do.php?ac=sendmail&rand={{ date:timestamp }}"></script>
{{endif}}

{{ if ad:couplet }}
<script language="javascript" type="text/javascript" src="source/script_couplet.js"></script>
<div id="uch_couplet" style="z-index: 10; position: absolute; display:none">
	<div id="couplet_left" style="position: absolute; left: 2px; top: 60px; overflow: hidden;">
		<div style="position: relative; top: 25px; margin:0.5em;" onMouseOver="this.style.cursor='hand'" onClick="closeBanner('uch_couplet');"><img src="image/advclose.gif"></div>
		{{ad/couplet}}
	</div>
	<div id="couplet_rigth" style="position: absolute; right: 2px; top: 60px; overflow: hidden;">
		<div style="position: relative; top: 25px; margin:0.5em;" onMouseOver="this.style.cursor='hand'" onClick="closeBanner('uch_couplet');"><img src="image/advclose.gif"></div>
		{{ad/couplet}}
	</div>
	<script type="text/javascript">
		lsfloatdiv('uch_couplet', 0, 0, '', 0).floatIt();
	</script>
</div>
{{endif}}

</body>
</html>

{{endif}}