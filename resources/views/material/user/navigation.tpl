{include file='user/main.tpl'}

	<main class="content">
		<div class="content-header ui-content-header">
			<div class="container">
				<h1 class="content-heading">站点导航</h1>
			</div>
		</div>

		<div class="container">
			<section class="content-inner margin-top-no">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="card margin-bottom-no">
							<div class="card-main">
								<div class="card-inner">
									<p>由于网络瞬息万变，可能某个域名内的东西第二天就变了。本地址导航不对链接内的可信度做任何保证，请自行分辨，尤其是购物网站！！！</p>
									<p>请理性看待站外内容</p>
									<p>站点导航会不定时完善，敬请期待</p>
									<div class="card-action">
										<div class="card-action-btn pull-left">
											<a href="/user/navigation?tag=1"><button class="btn btn-flat waves-attach"><span class="icon">check</span>&nbsp;学习工具</button></a>
											<a href="/user/navigation?tag=2"><button class="btn btn-flat waves-attach"><span class="icon">check</span>&nbsp;音频娱乐</button></a>
											<a href="/user/navigation?tag=3"><button class="btn btn-flat waves-attach"><span class="icon">check</span>&nbsp;社交网络</button>
											<a href="/user/navigation?tag=4"><button class="btn btn-flat waves-attach"><span class="icon">check</span>&nbsp;新闻资讯</button></a>
											<a href="/user/navigation?tag=5"><button class="btn btn-flat waves-attach"><span class="icon">check</span>&nbsp;线上购物</button></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
						<div class="card-main">
							<div class="card-inner margin-bottom-no">
								<p class="card-heading">网站列表</p>
								<div class="card-table">
									<div class="table-responsive">
										<table class="table">
											<thead>
											<tr>
												<th>网站名称</th>
												<th>网站描述</th>
												<th>链接</th>
											</tr>
											</thead>
											<tbody>

											

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

			</section>
		</div>



	</main>
	<script src="/web.json"></script>

	
{include file='user/footer.tpl'}
<script>
		function GetQueryString(name){
     	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
     	var r = window.location.search.substr(1).match(reg);
    	if(r!=null)return  unescape(r[2]); return null;
		}
		$(function(){
			var tag = GetQueryString("tag");
			for (var x in json){
				if (json[x].Tag == tag) {
					$("tbody").append("<tr><th>" + json[x].Name + "</th><th>" + json[x].Describe + "</th><th><a href=\"go?name="+ json[x].Name + "&url=" + json[x].Url + "\">点击进入</a>" + "</th></tr>")
				}
			}
			})
		
	</script>