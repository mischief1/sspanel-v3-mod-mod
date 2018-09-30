<?php

namespace App\Controllers;

use App\Models\InviteCode;
use App\Models\User;
use App\Models\Code;
use App\Models\Node;
use App\Models\Payback;
use App\Models\Paylist;
use App\Models\Relay;
use App\Services\Auth;
use App\Services\Config;
use App\Utils\Tools;
use App\Utils\Telegram;

/**
 *  HomeController
 */
class HomeController extends BaseController
{

    public function index()
    {
        return $this->view()->display('index.tpl');
    }
    
    public function code()
    {
        $codes = InviteCode::where('user_id', '=', '0')->take(10)->get();
        return $this->view()->assign('codes', $codes)->display('code.tpl');
    }

    public function down()
    {

    }

    public function tos()
    {
        return $this->view()->display('tos.tpl');
    }
	
	public function staff()
    {
        return $this->view()->display('staff.tpl');
    }
	
	
	public function telegram()
    {
		try {
			$bot = new \TelegramBot\Api\Client(Config::get('telegram_token'));
			// or initialize with botan.io tracker api key
			// $bot = new \TelegramBot\Api\Client('YOUR_BOT_API_TOKEN', 'YOUR_BOTAN_TRACKER_API_KEY');

			$bot->command('ping', function ($message) use ($bot) {
				$bot->sendMessage($message->getChat()->getId(), 'Pong!这个群组的 ID 是 '.$message->getChat()->getId().'!');
			});

			$bot->run();

		} catch (\TelegramBot\Api\Exception $e) {
			$e->getMessage();
		}
    }
	
	public function page404($request, $response, $args)
    {
		$pics=scandir(BASE_PATH."/public/theme/".(Auth::getUser()->isLogin==false?Config::get("theme"):Auth::getUser()->theme)."/images/error/404/");
		
		if(count($pics)>2)
		{
			$pic=$pics[rand(2,count($pics)-1)];
		}
		else
		{
			$pic="4041.png";
		}
		
		$newResponse = $response->withStatus(404);
		$newResponse->getBody()->write($this->view()->assign("pic","/theme/".(Auth::getUser()->isLogin==false?Config::get("theme"):Auth::getUser()->theme)."/images/error/404/".$pic)->display('404.tpl'));
        return $newResponse;
    }
	
	public function page405($request, $response, $args)
    {
        $pics=scandir(BASE_PATH."/public/theme/".(Auth::getUser()->isLogin==false?Config::get("theme"):Auth::getUser()->theme)."/images/error/405/");
		if(count($pics)>2)
		{
			$pic=$pics[rand(2,count($pics)-1)];
		}
		else
		{
			$pic="4051.png";
		}
		
		$newResponse = $response->withStatus(405);
		$newResponse->getBody()->write($this->view()->assign("pic","/theme/".(Auth::getUser()->isLogin==false?Config::get("theme"):Auth::getUser()->theme)."/images/error/405/".$pic)->display('405.tpl'));
        return $newResponse;
    }
	
	public function page500($request, $response, $args)
    {
        $pics=scandir(BASE_PATH."/public/theme/".(Auth::getUser()->isLogin==false?Config::get("theme"):Auth::getUser()->theme)."/images/error/500/");
		if(count($pics)>2)
		{
			$pic=$pics[rand(2,count($pics)-1)];
		}
		else
		{
			$pic="5001.png";
		}
		
		$newResponse = $response->withStatus(500);
		$newResponse->getBody()->write($this->view()->assign("pic","/theme/".(Auth::getUser()->isLogin==false?Config::get("theme"):Auth::getUser()->theme)."/images/error/500/".$pic)->display('500.tpl'));
        return $newResponse;
    }
	
	public function pmw_pingback($request, $response, $args)
    {
		
		if(Config::get('pmw_publickey')!="")
		{
			\Paymentwall_Config::getInstance()->set(array(
				'api_type' => \Paymentwall_Config::API_VC,
				'public_key' => Config::get('pmw_publickey'),
				'private_key' => Config::get('pmw_privatekey')
			));
			
			
			
			$pingback = new \Paymentwall_Pingback($_GET, $_SERVER['REMOTE_ADDR']);
			if ($pingback->validate()) {
				$virtualCurrency = $pingback->getVirtualCurrencyAmount();
				if ($pingback->isDeliverable()) {
				// deliver the virtual currency
				} else if ($pingback->isCancelable()) {
				// withdraw the virual currency
				} 
				
				$user=User::find($pingback->getUserId());
				$user->money=$user->money+$pingback->getVirtualCurrencyAmount();
				$user->save();
				
				$codeq=new Code();
				$codeq->code="Payment Wall 充值";
				$codeq->isused=1;
				$codeq->type=-1;
				$codeq->number=$pingback->getVirtualCurrencyAmount();
				$codeq->usedatetime=date("Y-m-d H:i:s");
				$codeq->userid=$user->id;
				$codeq->save();
			  
			  
				
				
				if($user->ref_by!=""&&$user->ref_by!=0&&$user->ref_by!=NULL)
				{
					$gift_user=User::where("id","=",$user->ref_by)->first();
					$gift_user->money=($gift_user->money+($codeq->number*(Config::get('code_payback')/100)));
					$gift_user->save();
					
					$Payback=new Payback();
					$Payback->total=$pingback->getVirtualCurrencyAmount();
					$Payback->userid=$user->id;
					$Payback->ref_by=$user->ref_by;
					$Payback->ref_get=$codeq->number*(Config::get('code_payback')/100);
					$Payback->datetime=time();
					$Payback->save();
					
				}
			  
			  
			  
				echo 'OK'; // Paymentwall expects response to be OK, otherwise the pingback will be resent
				
				
				if(Config::get('enable_donate') == 'true')
				{
					if($user->is_hide == 1)
					{
						Telegram::Send("姐姐姐姐，一位不愿透露姓名的大老爷给我们捐了 ".$codeq->number." 元呢~");
					}
					else
					{
						Telegram::Send("姐姐姐姐，".$user->user_name." 大老爷给我们捐了 ".$codeq->number." 元呢~");
					}
				}
			
			
			} else {
				echo $pingback->getErrorSummary();
			}
		}
		else
		{
			echo 'error';
		}
    }



    public function SubscribeConf($request, $response, $args){
    	$token = '';
    	if (isset($request->getQueryParams()["token"])) {
            $token = $request->getQueryParams()["token"];
        }else{
        	return null;
        }
    	$user = User::where('ga_token', $token)->first();
    	if ($user == null) {
    		return null;
    	}
    	$this->user = $user;
    	$nodes=Node::where('sort', 0)->where(
			function ($query) {
				$query->where("node_group","=",$this->user->node_group)
					->orWhere("node_group","=",0);
			}
		)->where("type","1")->where("node_class","<=", $this->user->class)->get();
		$android_add="";
    	
    	
    	$mu_nodes = Node::where('sort',9)->where('node_class','<=',$user->class)->where("type","1")->where(
			function ($query) use ($user) {
				$query->where("node_group","=",$user->node_group)
					->orWhere("node_group","=",0);
			}
		)->get();
		
		$relay_nodes = Node::where(
			function ($query) use ($user){
				$query->Where("node_group","=",$user->node_group)
					->orWhere("node_group","=",0);
			}
		)->where('type', 1)->where('sort', 10)->where("node_class","<=",$user->class)->orderBy('name')->get();
		
		$relay_rules = Relay::where('user_id', $this->user->id)->orderBy('id', 'asc')->get();
		
		foreach($nodes as $node)
		{
			$ary['server'] = $node->server;
			$ary['server_port'] = $user->port;
			$ary['password'] = $user->passwd;
			$ary['method'] = $node->method;
			if ($node->custom_method) {
				$ary['method'] = $this->user->method;
			}
			

				if($node->mu_only == 0)
				{

						$ssurl = $ary['server']. ":" . $ary['server_port'].":".str_replace("_compatible","",$user->protocol).":".$ary['method'].":".str_replace("_compatible","",$user->obfs).":".Tools::base64_url_encode($ary['password'])."/?obfsparam=".Tools::base64_url_encode($user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name) . "&group=" . Tools::base64_url_encode(Config::get('appName'));
						$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
						$android_add .= $ssqr_s_new." ";

					foreach($relay_rules as $relay_rule)
					{
						if(!($relay_rule->dist_node_id == $node->id && $relay_rule->port == $user->port))
						{
							continue;
						}
						
						if($relay_rule->source_node_id == 0)
						{
							foreach($relay_nodes as $relay_node)
							{
								if(!Tools::is_relay_rule_avaliable($relay_rule, $relay_rules, $relay_node->id))
								{
									continue;
								}


									$ssurl = $relay_node->server. ":" . $user->port . ":".str_replace("_compatible","",$user->protocol).":".($node->custom_method==1?$user->method:$node->method).":".str_replace("_compatible","",$user->obfs).":".Tools::base64_url_encode($user->passwd)."/?obfsparam=".Tools::base64_url_encode($user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name." - ".$relay_node->name) . "&group=" . Tools::base64_url_encode(Config::get('appName'));
									$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
									$android_add .= $ssqr_s_new." ";

							}
						}
						else
						{
							$relay_node = $relay_rule->Source_Node();
							
							if($relay_node != NULL)
							{

								if(!Tools::is_relay_rule_avaliable($relay_rule, $relay_rules, $relay_node->id))
								{
									continue;
								}

									$ssurl = $relay_node->server. ":" . $user->port . ":".str_replace("_compatible","",$user->protocol).":".($node->custom_method==1?$user->method:$node->method).":".str_replace("_compatible","",$user->obfs).":".Tools::base64_url_encode($user->passwd)."/?obfsparam=".Tools::base64_url_encode($user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name." - ".$relay_node->name) . "&group=" . Tools::base64_url_encode(Config::get('appName'));
									$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
									$android_add .= $ssqr_s_new." ";
							}
						}
					}
				}

			
			
			if($node->custom_rss == 1 && Config::get('enable_rss')=='true')
			{
				foreach($mu_nodes as $mu_node)
				{
					$mu_user = User::where('port','=',$mu_node->server)->first();
					$mu_user->obfs_param = $user->getMuMd5();
					
					$ary['server_port'] = $mu_user->port;
					$ary['password'] = $mu_user->passwd;
					$ary['method'] = $node->method;
					if ($node->custom_method) {
						$ary['method'] = $mu_user->method;
					}
					
					$ssurl = $ary['server']. ":" . $ary['server_port'].":".str_replace("_compatible","",$mu_user->protocol).":".$ary['method'].":".str_replace("_compatible","",$mu_user->obfs).":".Tools::base64_url_encode($ary['password'])."/?obfsparam=".Tools::base64_url_encode($mu_user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name." - ".$mu_node->server." 端口单端口多用户") . "&group=" . Tools::base64_url_encode(Config::get('appName'));
					$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
					$android_add .= $ssqr_s_new." ";
					
					foreach($relay_rules as $relay_rule)
					{
						if(!($relay_rule->dist_node_id == $node->id && $relay_rule->port == $mu_user->port))
						{
							continue;
						}
						
						
						if($relay_rule->source_node_id == 0)
						{
							foreach($relay_nodes as $relay_node)
							{

								if(!Tools::is_relay_rule_avaliable($relay_rule, $relay_rules, $relay_node->id))
								{
									continue;
								}
								
								if($node->custom_rss == 1)
								{
									$ssurl = $relay_node->server. ":" . $mu_user->port . ":".str_replace("_compatible","",$mu_user->protocol).":".($node->custom_method==1?$mu_user->method:$node->method).":".str_replace("_compatible","",$mu_user->obfs).":".Tools::base64_url_encode($mu_user->passwd)."/?obfsparam=".Tools::base64_url_encode($mu_user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name."- ".$mu_node->server." 端口单端口多用户 - ".$relay_node->name) . "&group=" . Tools::base64_url_encode(Config::get('appName'));
									$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
									$android_add .= $ssqr_s_new." ";
								}
							}
						}
						else
						{
							$relay_node = $relay_rule->Source_Node();
							if($relay_node != NULL)
							{
								
								if(!Tools::is_relay_rule_avaliable($relay_rule, $relay_rules, $relay_node->id))
								{
									continue;
								}								

								if($node->custom_rss == 1)
								{
									$ssurl = $relay_node->server. ":" . $mu_user->port . ":".str_replace("_compatible","",$mu_user->protocol).":".($node->custom_method==1?$mu_user->method:$node->method).":".str_replace("_compatible","",$mu_user->obfs).":".Tools::base64_url_encode($mu_user->passwd)."/?obfsparam=".Tools::base64_url_encode($mu_user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name."- ".$mu_node->server." 端口单端口多用户 - ".$relay_node->name) . "&group=" . Tools::base64_url_encode(Config::get('appName'));
									$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
									$android_add .= $ssqr_s_new." ";
								}
							}
						}
					}
					
				}
			}
		}
		
		foreach($relay_nodes as $node)
		{
			$rules = Relay::where(
				function ($query) use ($node){
					$query->Where("source_node_id","=",$node->id)
						->orWhere("source_node_id","=",0);
				}
			)->where('port', $user->port)->where('user_id', $user->id)->first();
			if($rules == NULL)
			{

					$ssurl = $node->server. ":" . $user->port . ":".str_replace("_compatible","",$user->protocol).":".($node->custom_method==1?$user->method:$node->method).":".str_replace("_compatible","",$user->obfs).":".Tools::base64_url_encode($user->passwd)."/?obfsparam=".Tools::base64_url_encode($user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name) . "&group=" . Tools::base64_url_encode(Config::get('appName'));
					$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
					$android_add .= $ssqr_s_new." ";

			}
			
			if($node->custom_rss == 1)
			{
				foreach($mu_nodes as $mu_node)
				{
					$mu_user = User::where('port','=',$mu_node->server)->first();
					$mu_user->obfs_param = $user->getMuMd5();
					
					$rules = Relay::where(
						function ($query) use ($node){
							$query->Where("source_node_id","=",$node->id)
								->orWhere("source_node_id","=",0);
						}
					)->where('port', $mu_user->port)->where('user_id', $user->id)->first();
					if($rules == NULL)
					{

							$ssurl = $node->server. ":" . $mu_user->port . ":".str_replace("_compatible","",$mu_user->protocol).":".($node->custom_method==1?$mu_user->method:$node->method).":".str_replace("_compatible","",$mu_user->obfs).":".Tools::base64_url_encode($mu_user->passwd)."/?obfsparam=".Tools::base64_url_encode($mu_user->obfs_param)."&remarks=".Tools::base64_url_encode($node->name."- ".$mu_node->server." 端口单端口多用户") . "&group=" . Tools::base64_url_encode(Config::get('appName'));
							$ssqr_s_new = "ssr://" . Tools::base64_url_encode($ssurl);
							$android_add .= $ssqr_s_new." ";
						}

					}
					
				}
		}
    	
    	return $response->getBody()->write(Tools::base64_url_encode($android_add));
    }

	public function alipay_callback($request, $response, $args)
    {
        require_once(BASE_PATH."/alipay/alipay.config.php");
		require_once(BASE_PATH."/alipay/lib/alipay_notify.class.php");
		
		
		if(Config::get('enable_alipay')!='false')
		{
			//计算得出通知验证结果
			$alipayNotify = new \SPAYNotify($alipay_config);
			$verify_result = $alipayNotify->verifyNotify();

			if($verify_result) {//验证成功
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					//请在这里加上商户的业务逻辑程序代

					
					//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
					
					//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
					
					//商户订单号

					$out_trade_no = $_POST['out_trade_no'];

					//支付宝交易号

					$trade_no = $_POST['trade_no'];

					//交易状态
					$trade_status = $_POST['trade_status'];
					
					$trade = Paylist::where("id",'=',$out_trade_no)->where('status',0)->where('total',$_POST['total_fee'])->first();
			
					if($trade == NULL)
					{
						exit("success");
					}
					
					$trade->status = 1;
					$trade->save();

					//status
					$trade_status = $_POST['trade_status'];


					if($_POST['trade_status'] == 'TRADE_FINISHED') {
						//判断该笔订单是否在商户网站中已经做过处理
							//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
							//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
							//如果有做过处理，不执行商户的业务程序
								
						//注意：
						//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知

						//调试用，写文本函数记录程序运行情况是否正常
						//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
						
						
						
						$user=User::find($trade->userid);
						$user->money=$user->money+$_POST['total_fee'];
						$user->save();
						
						$codeq=new Code();
						$codeq->code="支付宝 充值";
						$codeq->isused=1;
						$codeq->type=-1;
						$codeq->number=$_POST['total_fee'];
						$codeq->usedatetime=date("Y-m-d H:i:s");
						$codeq->userid=$user->id;
						$codeq->save();
					  
					  
						
						
						if($user->ref_by!=""&&$user->ref_by!=0&&$user->ref_by!=NULL)
						{
							$gift_user=User::where("id","=",$user->ref_by)->first();
							$gift_user->money=($gift_user->money+($codeq->number*(Config::get('code_payback')/100)));
							$gift_user->save();
							
							$Payback=new Payback();
							$Payback->total=$_POST['total_fee'];
							$Payback->userid=$user->id;
							$Payback->ref_by=$user->ref_by;
							$Payback->ref_get=$codeq->number*(Config::get('code_payback')/100);
							$Payback->datetime=time();
							$Payback->save();
							
						}
						
					}
					else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
						//判断该笔订单是否在商户网站中已经做过处理
							//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
							//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
							//如果有做过处理，不执行商户的业务程序
								
						//注意：
						//付款完成后，支付宝系统发送该交易状态通知

						//调试用，写文本函数记录程序运行情况是否正常
						//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
						
						$user=User::find($trade->userid);
						$user->money=$user->money+$_POST['total_fee'];
						$user->save();
						
						$codeq=new Code();
						$codeq->code="支付宝 充值";
						$codeq->isused=1;
						$codeq->type=-1;
						$codeq->number=$_POST['total_fee'];
						$codeq->usedatetime=date("Y-m-d H:i:s");
						$codeq->userid=$user->id;
						$codeq->save();
					  
					  
						
						
						if($user->ref_by!=""&&$user->ref_by!=0&&$user->ref_by!=NULL)
						{
							$gift_user=User::where("id","=",$user->ref_by)->first();
							$gift_user->money=($gift_user->money+($codeq->number*(Config::get('code_payback')/100)));
							$gift_user->save();
							
							$Payback=new Payback();
							$Payback->total=$_POST['total_fee'];
							$Payback->userid=$user->id;
							$Payback->ref_by=$user->ref_by;
							$Payback->ref_get=$codeq->number*(Config::get('code_payback')/100);
							$Payback->datetime=time();
							$Payback->save();
							
						}
						
					}

					//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
						
					echo "success";		//请不要修改或删除
					
					if(Config::get('enable_donate') == 'true')
					{
						if($user->is_hide == 1)
						{
							Telegram::Send("姐姐姐姐，一位不愿透露姓名的大老爷给我们捐了 ".$codeq->number." 元呢~");
						}
						else
						{
							Telegram::Send("姐姐姐姐，".$user->user_name." 大老爷给我们捐了 ".$codeq->number." 元呢~");
						}
					}
					
					/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			}
			else {
				//验证失败
				echo "fail";

				//调试用，写文本函数记录程序运行情况是否正常
				//logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
			}
		}
	}
}
