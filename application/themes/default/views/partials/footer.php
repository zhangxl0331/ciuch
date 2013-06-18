<?php if( ! $this->input->is_ajax_request()):?>

		
		</div>
		
		
		<div id="bottom"></div>
	</div>
	

	
	
	<div id="footer" title="{{debuginfo}}">
		
		
		<p class="r_option">
			<a href="javascript:;" onclick="window.scrollTo(0,0);" id="a_top" title="TOP"><img src="image/top.gif" alt="" style="padding: 5px 6px 6px;" /></a>
		</p>
		
		
		
		<p>
			<?=$config['sitename']?> - 
			<a href="mailto:<?=$config['adminemail']?>">联系我们</a>
			<?php if($config['miibeian']):?> - <a  href="http://www.miibeian.gov.cn" target="_blank"><?=$config['miibeian']?></a><?php endif;?>
		</p>
		<p>
			Powered by <a href="http://u.discuz.net" target="_blank"><strong>UCenter Home</strong></a> <span title="{{X_RELEASE;}}">X_VER</span>
			{{ if uch:sconfig:licensed }}<a  href="http://license.comsenz.com/?pid=7&host=$_SERVER[HTTP_HOST]" target="_blank">Licensed</a>{{ endif }}
			&copy; 2001-2009 <a  href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>
		</p>
	</div>
</div>



</body>
</html>

<?php endif;?>