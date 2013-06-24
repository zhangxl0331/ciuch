{{ if auth:uid==user:uid }}
<h2 class="title"><img src="{{ url:base }}image/app/doing.gif">记录</h2>
{{ else }}
	{{ theme:partial name="avatar" module="member" }}
	{{ theme:partial name="tabs" module="member" }}
{{ endif }}