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
								<h4>优惠码</h4>
									<p>这是一个用来公布可用优惠码的页面</p>
									<p>每年春节，国庆节，双十一都会发布优惠码，最大可到二五折(75off)。敬请期待</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="card">
						<div class="card-main">
							<div class="card-inner margin-bottom-no">
								<p class="card-heading">优惠码列表</p>
								<div class="card-table">
									<div class="table-responsive">
										<table class="table">
											<thead>
											<tr>
												<th>折扣大小</th>
												<th>优惠码</th>
												<th>剩余时间</th>
											</tr>
											</thead>
											<tbody>
<!-- 
											<tr>
											<th>八折</th>
											<th>kxvpn.cc</th>
											<th>永久</th>
											</tr> -->

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
					$("tbody").append("<tr><th>" + json[x].Name + "</th><th>" + json[x].Describe + "</th><th><a href=\"go?name="+ json[x].Name + "&url=" + json[x].Url + "\">点击进入</a>" + "</th></th>")
				}
			}
			})
		
	</script>
{include file='user/footer.tpl'}