{include file='user/main.tpl'}
	<main class="content">
		<div class="content-header ui-content-header">
			<div class="container">
				<h1 class="content-heading">邀请好友</h1>
			</div>
		</div>
		<div class="container">
			<section class="content-inner margin-top-no">
				<div class="row">
				
					<div class="col-lg-12 col-md-12">
						<div class="card margin-bottom-no">
							<div class="card-main">
								<div class="card-inner">
									<div class="card-inner">
										<p class="card-heading">邀请规则</p>
										<p>当有人使用你的邀请链接注册成功的时候，他每次购买套餐都会为你续上该套餐<code>{$invite_back} %</code>的时间和流量，上不封顶，且续费同样生效</p>
										<p>比如你邀请的人购买了年费套餐，则你的使用时间将续上<code>{365*(int){$invite_back}/100}</code>天，流量增加以此类推</p>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-lg-12 col-md-12">
						<div class="card margin-bottom-no">
							<div class="card-main">
								<div class="card-inner">
									<div class="card-inner">
										<p class="card-heading">我的邀请链接</p>

										<a href="{$inviteurl}">{$inviteurl}</a>

										<!-- <a class="btn btn-flat waves-attach waves-effect" href="#" id="copyurl">点击复制</a> -->

									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					
					<div class="col-lg-12 col-md-12">
						<div class="card margin-bottom-no">
							<div class="card-main">
								
									<div class="card-inner">
									
										<div class="card-table">
											<div class="table-responsive">
											{$invites->render()}
												<table class="table">
													<thead>
													<tr>
														<th>###</th>
														<th>被邀请者ID</th>
														<th>为你续了/天</th>
														<th>为你续了/GB</th>
														<th>时间</th>
													</tr>
													</thead>
													<tbody>
													{foreach $invites as $invite}
														<tr>
															<td><b>{$invite->id}</b></td>
															<td>{$invite->invited_user_id}</td>
															<td>{$invite->plus_time}</td>
															<td>{$invite->plus_bandwidth}</td>
															<td>{$invite->plus_date}</td>
														</tr>
													{/foreach}
													</tbody>
												</table>
											{$invites->render()}
											</div>
										</div>
									
								</div>
							</div>
						</div>
					</div>
					{include file='dialog.tpl'}
				</div>
			</section>
		</div>
	</main>
{include file='user/footer.tpl'}


