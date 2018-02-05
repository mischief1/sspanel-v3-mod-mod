{include file='user/main.tpl'}
	<main class="content">
		<div class="content-header ui-content-header">
			<div class="container">
				<h1 class="content-heading"></h1>
			</div>
		</div>

		<div class="container">
			<section class="content-inner margin-top-no">
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="card margin-bottom-no">
							<div class="card-main">
								<div class="card-inner">
									<div class="card-action"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		
	</main>
{include file='user/footer.tpl'}
<script>
			function GetQueryString(name){
	     	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	     	var r = window.location.search.substr(1).match(reg);
	    	if(r!=null)return  unescape(r[2]); return null;
			}
			$(function(){
				var name = GetQueryString("name");
				var url = GetQueryString("url");
				var goUrl = "window.location.href=\'http://" + url + "\'";
				$("h1").append("正在前往" + name);
				$(".card-action").append("<p>若长时间没有跳转请点击<a href=\"http://" + url + "\">" + "这里" + "</a></p>" );
				window.location.href="http://" + url
			})
		</script>