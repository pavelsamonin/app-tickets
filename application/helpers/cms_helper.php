<?php

/**
 * @param $message
 * @param bool $url
 */
function flash_success($message, $url=false){

    $CI =& get_instance();

    $CI->session->set_flashdata('success', $message);

    if($url) redirect($url);

}

/**
 * @param $message
 * @param bool $url
 */
function flash_error($message, $url = false)
{

    $CI =& get_instance();

    $CI->session->set_flashdata('error', $message);

    if ($url) redirect($url);

}

function lang($line)
{
    $line = get_instance()->lang->line($line);
    return $line;
}

function meta_header($header = 'title')
{
    $CI =& get_instance();
    $segs = $CI->uri->segment_array();

    $pagetitle = '';
    if ($segs[2] == 'open'){
        $pagetitle = lang($segs[3]);
        if(($pagetitle)){
            $pagetitle .= ' - ';
        }
    }
    $title = (($pagetitle ) ? ($pagetitle)  : '') . lang($segs[2] . '._'.$header);



    return $title;
}

function meta_og_image($page = 'main')
{
    $CI =& get_instance();
    $segs = $CI->uri->segment_array();

    $pagetitle = $page;
    if ($segs[2] != '') {
        $pagetitle = ($segs[2]);
    }
    if ($segs[2] == 'open') {
        $pagetitle = ($segs[3]);
    }

    return $pagetitle;
}


function lang_currency($balance)
{
    if (lang('var.lang') == 'ru') {
        return round($balance *  USDRUB, 2);
    } else {
        return $balance;
    }
}

function file_get_contents_curl($url) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function json_get_contents_curl($url) {

    $content = false;

    $data = file_get_contents_curl($url);

    if($data)
        $content = json_decode($data, true);

    return $content;
}


function contains($haystack, $needle)
{
    if (strpos($haystack, $needle) === false)
        return false;
    else
        return true;
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

function langpath()
{
    global $URI;
    global $CFG;
    $CFG->item('languages');
    $exploded = explode('/', $URI->uri_string);
    if (in_array($exploded[0], array_keys($CFG->item('languages'))))
    {
        return $exploded[1];
    } else {
        return $exploded[0];
    }
}




function mb_pathinfo($filepath) {
    if($filepath == '') return false;

    preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im',$filepath,$m);

    if(!$m) return false;
    if($m[1]) $ret['dirname']=$m[1];
    if($m[2]) $ret['basename']=$m[2];
    if($m[5]) $ret['extension']=$m[5];
    if($m[3]) $ret['filename']=$m[3];

    return $ret;
}

function clear_string($string){
    $string = preg_replace("/[^A-Za-z0-9 ]/", '', $string);

    return $string;
}

function get_country_by_ip($ip){


    $content = json_get_contents_curl("http://api.sypexgeo.net/json/$ip");

    if(isset($content['country'])){
        return array('country' => $content['country']['name_ru'], 'city' => $content['city']['name_ru']);
    }

    return array('country' => '', 'city' => '');
}


function number_format_drop_zero_decimals($n, $n_decimals = 2)
{
    return ((floor($n) == round($n, $n_decimals)) ? number_format($n) : number_format($n, $n_decimals));
}

/*
  Проверяем трейд ссылку, получаем стим ид и токен
*/
function check_tradelink($url)
{
    preg_match('/partner=(\d+)&token=(.{8}$)/s', $url, $match);

    if (count($match) == 3) {
        return array('short_steamid' => $match[1], 'steamid' => STEAM_PROFILE_INCREMENT + $match[1], 'trade_token' => $match[2]);
    }
}


/**
 * @desc  Purge Cache on CF
 * @link https://api.cloudflare.com/#zone-purge-individual-files-by-url-and-cache-tags
 * @param   string $path path that goes after the URL fx. "/user/login"
 * @param   array $json If you need to send some json with your request. For me delete requests are always blank
 * @return  Obj    $result HTTP response from REST interface in JSON decoded.
 */
function curl_purge_cache($zone = FALSE, $json = array('files' => []))
{
    if (!define(CLOUDFLATE_ZONE) || !define(CLOUDFLATE_AUTH_EMAIL) || !define(CLOUDFLATE_AUTH_KEY)) {
        return FALSE;
    }

    if (!$zone) $zone = CLOUDFLATE_ZONE;

    $url = 'https://api.cloudflare.com/client/v4/zones/' . $zone . '/purge_cache';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-Auth-Email: " . CLOUDFLATE_AUTH_EMAIL,
        "X-Auth-Key: " . CLOUDFLATE_AUTH_KEY
    ]);

    $result = curl_exec($ch);
    $result = json_decode($result);
    curl_close($ch);

    return $result;
}