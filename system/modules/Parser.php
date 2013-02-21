<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Parser
 *
 * @author Алексей
 */
class SMO_Parser {

    private $cookiefile = "cookie.txt";
    private $content = NULL;

    function Parser($url, $post, $cookie = true) {
        
    }

    function send($url, $post, $refer, $cookie) {
        if ($post == null)
            $post = false;
        if ($cookie)
            $coo = TempFi . $this->cookiefile;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.4) Gecko/2008102920 AdCentriaIM/1.7 Firefox/3.0.4");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_REFERER, $refer);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $coo);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $coo);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $this->content = curl_exec($ch);
        curl_close($ch);
    }

    function preg() {
        
    }

    function preg_all() {
        
    }

    public static function Image($imageurl, $surl) {
        if (@fopen(We_DIR . "media" . $imageurl, "r")) {
            return "/media" . $imageurl;
        } else {
            $icon = imagecreatefromjpeg($surl . $imageurl);

            $dir = explode("/", $imageurl);
            unset($dir[count($dir) - 1]);
            $dir = We_DIR . "media" . implode("/", $dir);
            if (!@opendir($dir))
                @mkdir($dir, 0700, TRUE);

            imagejpeg($icon, We_DIR . "media" . $imageurl, 100);
            @imagedestroy($icon);
            return "/media" . $imageurl;
        }
    }

}

?>
