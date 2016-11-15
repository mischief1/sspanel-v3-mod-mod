<?php

namespace App\Services;

/***
 * Mail Service
 */

use App\Services\Mail\Mailgun;
use App\Services\Mail\Ses;
use App\Services\Mail\Smtp;
use Smarty;


class Mail
{
    /**
     * @return Mailgun|Ses|Smtp|null
     */
    public static function getClient()
    {
        $driver = Config::get("mailDriver");
        switch ($driver) {
            case "ses":
                return new Ses();
            case "smtp":
                return new Smtp();
            default:
                // @TODO default action
        }
        return null;
    }

    /**
     * @param $template
     * @param $ary
     * @return mixed
     */
    public static function genHtml($template, $ary)
    {
        $smarty = new smarty();
        $smarty->settemplatedir(BASE_PATH . '/resources/email/');
        $smarty->setcompiledir(BASE_PATH . '/storage/framework/smarty/compile/');
        $smarty->setcachedir(BASE_PATH . '/storage/framework/smarty/cache/');
        // add config
        $smarty->assign('config', Config::getPublicConfig());
        foreach ($ary as $key => $value) {
            $smarty->assign($key, $value);
        }
        return $smarty->fetch($template);
    }

    /**
     * @param $to
     * @param $subject
     * @param $template
     * @param $ary
     * @param $file
     * @return bool|void
     */
    public static function send($to, $subject, $template, $ary = [], $file = [])
    {
        $text = self::genHtml($template, $ary);
        return self::getClient()->send($to, $subject, $text, $file);
    }
}