<?php

namespace App\Models;
use App\Services\Config;
use App\Models\User;
use App\Models\InviteUrl;

class Shop extends Model

{
	protected $connection = "default";
    protected $table = "shop";

	public function content()
    {
        $content = json_decode($this->attributes['content'],TRUE);
        $content_text="";
		$i = 0;
		foreach($content as $key=>$value)
		{
			switch ($key)
			{
				case "bandwidth":
					$content_text .= "添加流量 ".$value." G ";
					break;
				case "expire":
					$content_text .= "为账号的有效期添加 ".$value." 天 ";
					break;
				case "class":
					$content_text .= "为账号升级为等级 ".$value." ,有效期 ".$content["class_expire"]." 天";
					break;
				default:
			}
			
			if($i<count($content)&&$key!="class_expire")
			{
				$content_text .= ",";
			}
			
			$i++;
			
		}
		
		if(substr($content_text, -1, 1)==",")
		{
			$content_text=substr($content_text, 0, -1);
		}
		
		return $content_text;
    }
	
	public function bandwidth()
    {
        $content =  json_decode($this->attributes['content']);
		if(isset($content->bandwidth))
		{
			return $content->bandwidth;
		}
		else
		{
			return 0;
		}
    }
	
	public function expire()
    {
        $content =  json_decode($this->attributes['content']);
		if(isset($content->expire))
		{
			return $content->expire;
		}
		else
		{
			return 0;
		}
    }
	
	public function user_class()
    {
        $content =  json_decode($this->attributes['content']);
		if(isset($content->class))
		{
			return $content->class;
		}
		else
		{
			return 0;
		}
    }
	
	public function class_expire()
    {
        $content =  json_decode($this->attributes['content']);
		if(isset($content->class_expire))
		{
			return $content->class_expire;
		}
		else
		{
			return 0;
		}
    }
	
	public function buy($user, $is_renew = 0)
	{
		$content = json_decode($this->attributes['content'],TRUE);
        $content_text="";
        $inviter = "";
		if(Config::get('invite_url') == 'true'){
			if ($user->ref_by != 0) {
				$inviter = User::where("id",$user->ref_by)->first();
				$inviteUrl = new InviteUrl();
				$inviteUrl->user_id=$inviter->id;
				$inviteUrl->invited_user_id = $user->id;
				$inviteUrl->plus_date = date("Y-m-d H:i:s",time());
				$invite_back = (int)Config::get('invite_back');
			}
		}
		foreach($content as $key=>$value)
		{
			switch ($key)
			{
				case "bandwidth":
					if($is_renew == 0)
					{
						if(Config::get('enable_bought_reset') == 'true')
						{
							$user->transfer_enable=$value*1024*1024*1024;
							$user->u = 0;
							$user->d = 0;
							$user->last_day_t = 0;
						}
						else
						{
							$user->transfer_enable=$user->transfer_enable+$value*1024*1024*1024;
						}
					}
					else
					{
						if($this->attributes['auto_reset_bandwidth'] == 1)
						{
							$user->transfer_enable=$value*1024*1024*1024;
							$user->u = 0;
							$user->d = 0;
							$user->last_day_t = 0;
						}
						else
						{
							$user->transfer_enable=$user->transfer_enable+$value*1024*1024*1024;
						}
					}

					if ($inviter != "") {
						if($is_renew == 0)
						{
							$inviter->transfer_enable=$inviter->transfer_enable+$value*1024*1024*1024*$invite_back/100;
						}
						else
						{
							if($this->attributes['auto_reset_bandwidth'] == 1)
							{
								$inviter->transfer_enable=$value*1024*1024*1024*$invite_back/100;
								$inviter->u = 0;
								$inviter->d = 0;
								$inviter->last_day_t = 0;
							}
							else
							{
								$inviter->transfer_enable=$inviter->transfer_enable+$value*1024*1024*1024*$invite_back/100;
							}
						}
						$inviteUrl->plus_bandwidth = $value*$invite_back/100;
					}
					break;
				case "expire":
					if(time()>strtotime($user->expire_in))
					{
						$user->expire_in=date("Y-m-d H:i:s",time()+$value*86400);
					}
					else
					{
						$user->expire_in=date("Y-m-d H:i:s",strtotime($user->expire_in)+$value*86400);
					}

					if ($inviter != "") {
						if(time()>strtotime($inviter->expire_in))
						{
							$inviter->expire_in=date("Y-m-d H:i:s",time()+$value*86400*$invite_back/100);
						}
						else
						{
							$inviter->expire_in=date("Y-m-d H:i:s",strtotime($inviter->expire_in)+$value*86400*$invite_back/100);
						}
					}
					break;
				case "class":
					if($user->class==0||$user->class!=$value)
					{
						$user->class_expire=date("Y-m-d H:i:s",time());
					}
					$user->class_expire=date("Y-m-d H:i:s",strtotime($user->class_expire)+$content["class_expire"]*86400);
					$user->class=$value;

					if ($inviter != "") {
						if($inviter->class==0||$inviter->class!=$value)
						{
							$inviter->class_expire=date("Y-m-d H:i:s",time());
						}
						$inviter->class_expire=date("Y-m-d H:i:s",strtotime($inviter->class_expire)+$content["class_expire"]*86400*$invite_back/100);
						$inviter->class=$value;
						$inviteUrl->plus_time = $content["class_expire"]*$invite_back/100;

					}
					break;
				default:
			}
			
			
		}
		if ($inviter != "") {
			$inviteUrl->save();
			$inviter->save();
		}
		$user->save();
	}
}
