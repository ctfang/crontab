<?php
/**
 * Created by PhpStorm.
 * User: selden1992
 * Date: 2016/10/30
 * Time: 15:30
 */

namespace crontab\src;


class Http
{

    /**
     * 当前完整url
     * @return string
     */
    static public function url(){
        $pageURL = 'http';
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80"){
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        }else{
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
    /**
     * @param $strUrl
     * @param string $value
     * @return bool|string
     */
    static public function sockOpen($strUrl, $value=''){
        $url= parse_url( $strUrl );
        if(is_array($value)) $value = http_build_query($value);
        $fp = fsockopen($url['host'], 80);
        if (!$fp) return "Failed to open socket to localhost";
        fputs($fp, sprintf("POST %s%s%s HTTP/1.0\n", $url['path'], "",""));
        fputs($fp, "Host: $url[host]\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Content-length: " . strlen($value) . "\n");
        fputs($fp, "Connection: close\n\n");
        fputs($fp, "$value\n");
        fclose($fp);
        return true;
    }
}