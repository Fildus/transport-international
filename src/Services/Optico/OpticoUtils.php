<?php

namespace App\Services\Optico;

class OpticoUtils
{
    public static function isMobile()
    {
      $isMobile = false;

      $op = @strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
      $no = @strtolower($_SERVER['HTTP_X_MOBILE_GATEWAY']);
      $ua = @strtolower($_SERVER['HTTP_USER_AGENT']);
      $ac = @strtolower($_SERVER['HTTP_ACCEPT']);

      $isMobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
          || $op != ''
          || $no != ''
          || strpos($ua, 'sony') !== false
          || strpos($ua, 'symbian') !== false
          || strpos($ua, 'nokia') !== false
          || strpos($ua, 'samsung') !== false
          || strpos($ua, 'mobile') !== false
          || strpos($ua, 'windows ce') !== false
          || strpos($ua, 'wm5 pie') !== false
          || strpos($ua, 'windows mobile') !== false
          || strpos($ua, 'windows phone') !== false
          || strpos($ua, 'iemobile') !== false
          || strpos($ua, 'midp-') !== false
          || strpos($ua, 'lge-') !== false
          || strpos($ua, 'lge ') !== false
          || strpos($ua, 'pda') !== false
          || strpos($ua, 'bolt/') !== false
          || strpos($ua, 'epoc') !== false
          || strpos($ua, 'opera mini') !== false
          || strpos($ua, 'opera mobi') !== false
          || strpos($ua, 'nitro') !== false
          || strpos($ua, 'j2me') !== false
          || strpos($ua, 'midp-') !== false
          || strpos($ua, 'cldc-') !== false
          || strpos($ua, 'netfront') !== false
          || strpos($ua, 'mot') !== false
          || strpos($ua, 'up.browser') !== false
          || strpos($ua, 'up.link') !== false
          || strpos($ua, 'audiovox') !== false
          || strpos($ua, 'blackberry') !== false
          || strpos($ua, 'android') !== false
          || strpos($ua, 'iphone') !== false
          || strpos($ua, 'ericsson,') !== false
          || strpos($ua, 'panasonic') !== false
          || strpos($ua, 'philips') !== false
          || strpos($ua, 'sanyo') !== false
          || strpos($ua, 'sharp') !== false
          || strpos($ua, 'kindle') !== false
          || strpos($ua, 'sie-') !== false
          || strpos($ua, 'portalmmm') !== false
          || strpos($ua, 'blazer') !== false
          || strpos($ua, 'avantgo') !== false
          || strpos($ua, 'danger') !== false
          || strpos($ua, 'palm') !== false
          || strpos($ua, 'series60') !== false
          || strpos($ua, 'palmsource') !== false
          || strpos($ua, 'pocketpc') !== false
          || strpos($ua, 'smartphone') !== false
          || strpos($ua, 'rover') !== false
          || strpos($ua, 'ipaq') !== false
          || strpos($ua, 'au-mic,') !== false
          || strpos($ua, 'alcatel') !== false
          || strpos($ua, 'ericy') !== false
          || strpos($ua, 'up.link') !== false
          || strpos($ua, 'vodafone/') !== false
          || strpos($ua, 'wap1.') !== false
          || strpos($ua, 'wap2.') !== false;

        return $isMobile;
    }
}
