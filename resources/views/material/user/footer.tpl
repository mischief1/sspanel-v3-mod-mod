	<footer class="ui-footer">
		<div class="container">
			&copy; {$config["appName"]}  <a href="/staff">STAFF</a> {if $config["enable_analytics_code"] == 'true'}{include file='analytics.tpl'}{/if}
		</div>
		<script>
		(function() {
		  var _hmt = _hmt || [];
		  var hm = document.createElement("script");
		  hm.src = "https://hm.baidu.com/hm.js?cb5c9f6f85a152818d65a0656b9781c4";
		  var s = document.getElementsByTagName("script")[0]; 
		  s.parentNode.insertBefore(hm, s);
		})();
		</script>
	</footer>

	<!-- js -->
	<script src="/assets/public/js/jquery.min.js"></script>
	<script src="//static.geetest.com/static/tools/gt.js"></script>
	<script src="/theme/material/js/base.min.js"></script>
	<script src="/theme/material/js/project.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.16/clipboard.min.js"></script>
	

</body>
</html>