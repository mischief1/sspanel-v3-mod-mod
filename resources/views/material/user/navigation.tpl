<link href="//cdn.bootcss.com/material-design-lite/1.2.1/material.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/material-design-lite/1.2.1/material.min.js"></script>
{include file='user/main.tpl'}
<style>
.demo-card-wide.mdl-card {
padding: 5% 0;
width: 100%;
}
.demo-card-wide > .mdl-card__title {
color: #fff;
height: 176px;
background: url('../image/web/Google.jpg') center / cover;
}
.demo-card-wide > .mdl-card__menu {
color: #fff;
}
.mdl-layout{
	padding: 0 10%;
	width: 100%;
}
.mdl-layout__header{
	padding: 0 10%;
	min-height: 0px;
}
</style>
<!-- 环境配置 -->
<main class="content">
<!-- 站点头部-->
<div class="content-header ui-content-header">
	<div class="container">
		<h1 class="content-heading">网站导航</h1>
	</div>
</div>
<!-- Simple header with fixed tabs. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header
	mdl-layout--fixed-tabs">
	<header class="mdl-layout__header">
		<!-- Tabs -->
		<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
			<a href="#fixed-tab-1" class="mdl-layout__tab is-active">学习</a>
			<a href="#fixed-tab-2" class="mdl-layout__tab">社交</a>
			<a href="#fixed-tab-3" class="mdl-layout__tab">影视</a>
			<a href="#fixed-tab-4" class="mdl-layout__tab">游戏</a>
			<a href="#fixed-tab-5" class="mdl-layout__tab">资源</a>
		</div>
	</header>
	<main class="mdl-layout__content">
	<section class="mdl-layout__tab-panel is-active" id="fixed-tab-1">
		<div class="page-content"><!-- Your content goes here -->

		<div class="demo-card-wide mdl-card mdl-shadow--2dp">
					<div class="mdl-card__title">
								<h2 class="mdl-card__title-text">Welcome</h2>
					</div>
					<div class="mdl-card__supporting-text">
								Lorem ipsum dolor sit amet, consectetur adipiscing elit.
								Mauris sagittis pellentesque lacus eleifend lacinia...
					</div>
					<div class="mdl-card__actions mdl-card--border">
								<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
											Get Started
								</a>
					</div>
					<div class="mdl-card__menu">
								<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
								<i class="material-icons">share</i>
								</button>
					</div>
		</div>
	</div>
</section>
<section class="mdl-layout__tab-panel" id="fixed-tab-2">
	<div class="page-content"><!-- Your content goes here --></div>
</section>
<section class="mdl-layout__tab-panel" id="fixed-tab-3">
	<div class="page-content"><!-- Your content goes here --></div>
</section>
<section class="mdl-layout__tab-panel" id="fixed-tab-4">
	<div class="page-content"><!-- Your content goes here --></div>
</section>
<section class="mdl-layout__tab-panel" id="fixed-tab-5">
	<div class="page-content"><!-- Your content goes here --></div>
</section>
</main>
</div>
</main>