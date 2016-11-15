<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta content="IE=edge" http-equiv="X-UA-Compatible">
		<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
		<meta name="theme-color" content="#ff9800">
		<title>{$config["appName"]}</title>
		<script src="/theme/mdl/material.min.js"></script>
		<!-- css -->
		<link href="/theme/mdl/material.min.css" rel="stylesheet">
		<link href="/theme/material/css/base.min.css" rel="stylesheet">
		<link href="/theme/material/css/project.min.css" rel="stylesheet">
		<link href="https://fonts.lug.ustc.edu.cn/icon?family=Material+Icons" rel="stylesheet">
		
		
		<!-- favicon -->
		<!-- ... -->
		<style>
			.pagination {
				display:inline-block;
				padding-left:0;
				margin:20px 0;
				border-radius:4px
			}
			.pagination>li {
				display:inline
			}
			.pagination>li>a,.pagination>li>span {
				position:relative;
				float:left;
				padding:6px 12px;
				margin-left:-1px;
				line-height:1.42857143;
				color:#337ab7;
				text-decoration:none;
				background-color:#fff;
				border:1px solid #ddd
			}
			.pagination>li:first-child>a,.pagination>li:first-child>span {
				margin-left:0;
				border-top-left-radius:4px;
				border-bottom-left-radius:4px
			}
			.pagination>li:last-child>a,.pagination>li:last-child>span {
				border-top-right-radius:4px;
				border-bottom-right-radius:4px
			}
			.pagination>li>a:focus,.pagination>li>a:hover,.pagination>li>span:focus,.pagination>li>span:hover {
				color:#23527c;
				background-color:#eee;
				border-color:#ddd
			}
			.pagination>.active>a,.pagination>.active>a:focus,.pagination>.active>a:hover,.pagination>.active>span,.pagination>.active>span:focus,.pagination>.active>span:hover {
				z-index:2;
				color:#fff;
				cursor:default;
				background-color:#337ab7;
				border-color:#337ab7
			}
			.pagination>.disabled>a,.pagination>.disabled>a:focus,.pagination>.disabled>a:hover,.pagination>.disabled>span,.pagination>.disabled>span:focus,.pagination>.disabled>span:hover {
				color:#777;
				cursor:not-allowed;
				background-color:#fff;
				border-color:#ddd
			}
			.pagination-lg>li>a,.pagination-lg>li>span {
				padding:10px 16px;
				font-size:18px
			}
			.pagination-lg>li:first-child>a,.pagination-lg>li:first-child>span {
				border-top-left-radius:6px;
				border-bottom-left-radius:6px
			}
			.pagination-lg>li:last-child>a,.pagination-lg>li:last-child>span {
				border-top-right-radius:6px;
				border-bottom-right-radius:6px
			}
			.pagination-sm>li>a,.pagination-sm>li>span {
				padding:5px 10px;
				font-size:12px
			}
			.pagination-sm>li:first-child>a,.pagination-sm>li:first-child>span {
				border-top-left-radius:3px;
				border-bottom-left-radius:3px
			}
			.pagination-sm>li:last-child>a,.pagination-sm>li:last-child>span {
				border-top-right-radius:3px;
				border-bottom-right-radius:3px
			}
			.pager {
				padding-left:0;
				margin:20px 0;
				text-align:center;
				list-style:none
			}
			.pager li {
				display:inline
			}
			.pager li>a,.pager li>span {
				display:inline-block;
				padding:5px 14px;
				background-color:#fff;
				border:1px solid #ddd;
				border-radius:15px
			}
			.pager li>a:focus,.pager li>a:hover {
				text-decoration:none;
				background-color:#eee
			}
			.pager .next>a,.pager .next>span {
				float:right
			}
			.pager .previous>a,.pager .previous>span {
				float:left
			}
			.pager .disabled>a,.pager .disabled>a:focus,.pager .disabled>a:hover,.pager .disabled>span {
				color:#777;
				cursor:not-allowed;
				background-color:#fff
			}
			
			
			
			
			.pagination>li>a,
			.pagination>li>span {
			border: 1px solid white;
			}
			.pagination>li.active>a {
			background: #f50057;
			color: #fff;
			}
			
			.pagination>li>a {
			background: white;
			color: #000;
			}
			
			
			.pagination > .active > a, .pagination > .active > a:focus, .pagination > .active > a:hover, .pagination > .active > span, .pagination > .active > span:focus, .pagination > .active > span:hover {
				color: #fff;
				background-color: #000;
				border-color: #000;
			}
			.pagination>.active>span {
			background-color: #f50057;
			color: #fff;
			border-color: #fff;
			}
			
			
			
			.pagination > .disabled > span {
			border-color: #fff;
			}
			
			
			pre {
				white-space: pre-wrap;
				word-wrap: break-word;
			}
			.content-heading {
				font-weight: 300;
				color: #fff;
			}
			.main-layout-transparent {
				padding: 0px;
				background: url('../image/header/default.jpg') no-repeat  top;
				background-origin:padding-box;
				background-size:2000px 500px;
			}
			.main-layout-transparent .mdl-layout__header,
			.main-layout-transparent .mdl-layout__drawer-button {
			/* This background is dark, so we set text to white. Use 87% black instead if
			your background is light. */
			color: white;
			}
					
		</style>
		<!-- Uses a transparent header that draws on top of the layout's background -->
		
		
		<div class="main-layout-transparent mdl-layout mdl-js-layout">
			<header class="mdl-layout__header mdl-layout__header--transparent">
				<div class="mdl-layout__header-row">
					<!-- Title -->
					<span class="mdl-layout-title">科学</span>
					<!-- Add spacer, to align navigation to the right -->
					<div class="mdl-layout-spacer"></div>
					<!-- Navigation -->
					<nav class="mdl-navigation">
						{if $user->isLogin}
						<a class="mdl-navigation__link" href="/user/">欢迎你，{$user->user_name}</a>
						<a class="mdl-navigation__link" href="/user/">用户中心</a>
						<a class="mdl-navigation__link" href="/user/logout">退出</a>
						{else}
						<a class="mdl-navigation__link" href="/auth/login">登陆</a>
						<a class="mdl-navigation__link" href="/auth/register">注册</a>
						{/if}
					</nav>
				</div>
			</header>
			<div class="mdl-layout__drawer">
			<!-- 如果要新加功能，先去config\route.php改改,如果需要调用参数去app\Controllers\UserController,最后还要重新运行一下 php xcat-->
				<nav class="mdl-navigation">
					<a class="mdl-navigation__link" href="/user"><i class="icon icon-lg">recent_actors</i>&nbsp;首页</a>
					<a class="mdl-navigation__link" href="/user/profile"><i class="icon icon-lg">info</i>&nbsp;账户信息</a>
					<a class="mdl-navigation__link" href="/user/edit"><i class="icon icon-lg">sync_problem</i>&nbsp;资料编辑</a>
					<a class="mdl-navigation__link" href="/user/invite"><i class="icon icon-lg">loyalty</i>&nbsp;邀请码</a>
					<a class="mdl-navigation__link" href="/user/announcement"><i class="icon icon-lg">announcement</i>&nbsp;查看公告</a>
					{if $user->isAdmin()}
					<a class="mdl-navigation__link" href="/user/navigation"><i class="icon icon-lg">favorite</i>&nbsp;网站导航</a>
					{/if}
					<a class="mdl-navigation__link" href="/user/node"><i class="icon icon-lg">router</i>&nbsp;节点列表</a>
					<a class="mdl-navigation__link" href="/user/trafficlog"><i class="icon icon-lg">traffic</i>&nbsp;流量记录</a>
					<a class="mdl-navigation__link" href="/user/lookingglass"><i class="icon icon-lg">youtube_searched_for</i>&nbsp;观察窗</a>
					<a class="mdl-navigation__link" href="/user/shop"><i class="icon icon-lg">shop</i>&nbsp;商店</a>
					<a class="mdl-navigation__link" href="/user/bought"><i class="icon icon-lg">shopping_cart</i>&nbsp;购买记录</a>
					<a class="mdl-navigation__link" href="/user/code"><i class="icon icon-lg">code</i>&nbsp;充值</a>
					{if $user->isAdmin()}
					<a class="/admin"><i class="icon icon-lg">person_pin</i>&nbsp;管理面板</a>
					{/if}
				</nav>
			</div>
			<main class="mdl-layout__content">