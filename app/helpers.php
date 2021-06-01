<?php

use App\Exceptions\ApiException;
use Illuminate\Support\Facades\Cache;

function changeToQuotes($str) {
    return sprintf("'%s'", $str);
}

function getCache($key)
{
    return json_decode(Cache::get($key), true);
}

function setRedis($key, $value, $expire = null)
{
    return Cache::set($key, json_encode($value), $expire);
}

/**
 * 获取当前用户uuid
 * @return mixed|string
 */
function getCurrentUserId()
{
    return config('userInfo')['member_uuid'] ?? '';
}

/**
 * 获取当前用户名
 * @return mixed|string
 */
function getCurrentUser()
{
    return config('userInfo')['member_name'] ?? '';
}

//json转array
function jsonToArray($json)
{
    if (is_array($json)) {
        foreach ($json as &$arr) {
            $arr = json_decode($arr, true);
        }
    } else {
        $json = json_decode($json, true);
    }

    return $json;
}

function getSpuCode($prefix = 'SP') {
    $key = 'system:spu_incr:code:'.$prefix;
    if (Cache::has($key)) {
        Cache::increment($key, 1);
    } else {
        Cache::set($key, 100, 3600*24);
    }
    $count = Cache::get($key);
    return $prefix.time().$count;
}

function getCode($prefix = 'OR') {
    $key = 'system:code_incr:code:'.$prefix;
    if (Cache::has($key)) {
        Cache::increment($key, 1);
    } else {
        Cache::set($key, 100, 3600*24);
    }
    $count = Cache::get($key);
    return $prefix.time().$count;
}

function throwError($error = ApiException::ERROR_DATA_NOT_EXISTS)
{
    throw new ApiException($error);
}

/**
 * 获取每天自增的编号
 * @param string $prefix
 * @param bool   $suffix
 * @return string
 */
function get_increase_code($prefix = 'PR', $suffix = false)
{
    $tail = $suffix ? rand(10,99) : '';
    if (Cache::has('system:auto_increment:code:' . $prefix)) {
        $code = $prefix.Cache::get('system:auto_increment:code:' . $prefix);
        Cache::increment('system:auto_increment:code:' . $prefix);
    } else {
        $expired_time = strtotime(date('Y-m-d 23:59:59')) - time();
        $origin = date('ymd').'00001';
        Cache::add('system:auto_increment:code:' . $prefix, $origin, $expired_time);
        Cache::increment('system:auto_increment:code:' . $prefix);
        $code =  $prefix . $origin . $tail;
    }
    return $suffix ? $code . $tail : $code;
}

/**
 * 检查是否真实存在且不为空值或为与空值相等的值
 * @param $needle
 * @return bool
 */
function is_real_exists($needle)
{
    return isset($needle) && !empty($needle);
}

/**
 * 发起一个http 请求
 * @param        $requestData
 * @param        $url
 * @param string $method
 * @param array  $header
 * @return mixed
 * @throws \GuzzleHttp\Exception\GuzzleException
 */
function send_http_request($requestData, $url, $method = 'GET', $header = [])
{
    $client = new \GuzzleHttp\Client([
        'timeout'  => 10
    ]);
    strtoupper($method) === 'GET' && $url .= '?'.http_build_query($requestData);
    $response = $client->request($method,$url,[
        'form_params' => strtoupper($method) === 'GET' ? [] : $requestData,
        'headers' => $header,
//        'proxy' => '127.0.0.1:8888'
    ]);
    return json_decode((string)$response->getBody(), true);
}
function curl_get_html($url, $req = [], $header = []){
    $client = new \GuzzleHttp\Client([
        'timeout'  => 10
    ]);
    $url .= !empty($req) ? (stripos($url, '?') !== false ? '&' : '?') . http_build_query($req) : '';
    $header += [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36',
    ];
    $response = $client->request('GET', $url, [
        'headers' => $header,
        'allow_redirects' => false
//        'proxy' => '127.0.0.1:8888'
    ]);
    return (string)$response->getBody();
}

/**
 * 过滤数组中的空值字段
 * @param      $array
 * @param bool $force_delete
 * @return mixed
 */
function handle_null(&$array, $force_delete = false)
{

    foreach ($array as $k => $v) {
        if (is_array($v)) {
            $array[$k] = handle_null($v, $force_delete);
        }
        if ($v === 'null' || $v === 'NULL' || $v === 'Null' || $v === null) {
            if ($force_delete === true) {
                unset($array[$k]);
            } else {
                $array[$k] = '';
            }

        }
    }
    return $array;
}


/*
 * 字符下划线转驼峰
 */
function convert_to_hump($str, $lcfirst = true)
{
    $str = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
        return strtoupper($matches[2]);
    },$str);
    return $lcfirst ? lcfirst($str) : $str;
}

/*
 * 字符驼峰转下划线
 */
function convert_to_line($str)
{
    $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
        return '_'.strtolower($matches[0]);
    },$str);
    return $str;
}

/**
 * 转化数组为驼峰形式
 * @param array $data
 * @param bool $lcfirst
 * @return array
 */
function convert_hump(array $data, $lcfirst = true)
{
    $result = [];
    foreach ($data as $key => $item) {
        if (is_array($item) || is_object($item)) {
            $key = $lcfirst ? lcfirst(convert_to_hump($key)) : ucfirst(convert_to_hump($key));
            $result[$key] = convert_hump((array)$item,$lcfirst);
        } else {
            $key = $lcfirst ? lcfirst(convert_to_hump($key)) : ucfirst(convert_to_hump($key));
            $result[$key] = $item;
        }
    }
    return $result;
}
/**
 * 转化数组为小写下划线形式
 * @param array $data
 * @return array
 */
function convert_underline(array $data)
{
    $result = [];
    foreach ($data as $key => $item) {
        if (is_array($item) || is_object($item)) {
            $key = strtolower(convert_to_line($key));
            $result[$key] = convert_underline((array)$item);
        } else {
            $key = strtolower(convert_to_line($key));
            $result[$key] = $item;
        }
    }
    return $result;
}

/**
 * 获取阿里巴巴offer id
 * @param $url
 * @return bool
 */
function get_alibaba_offer_id($url)
{
    $reg = '/\w+\.1688\.com\/offer\/(\d+)\.html/i';
    if (preg_match($reg, $url, $match)) {
        return $match[1];
    }
    return is_numeric($url) ? $url : false;
}

function get_alibaba_url($offerId)
{
    $pattern = 'https://detail.1688.com/offer/%s.html';
    return sprintf($pattern, $offerId);
}

function get_date_time_for_alibaba($value)
{
    $value = substr($value, 0, 14);
    return $value ? date('Y-m-d H:i:s', strtotime($value)) : '';
}
/**
 * @param  string|int $from 时间开始值
 * @param  string|int $to 时间结束值
 * @param int $unit 结算结果的单位值（默认一天即为86400秒）
 * @return int|string
 */
function time_diff($from, $to, $unit = 86400)
{
    $from = strtotime($from);
    $to = strtotime($to);
    if ($from === false || $to === false)
        return 0;
    if ($to - $from <= 0)
        return '';
    $result = ($to - $from) / $unit;
    return number_format($result, 2, '.', '');
}

function decimal($value, $precision = 2, $point = '.', $separator = '')
{
    return number_format($value, $precision, $point, $separator);
}

function double($value, $precision = 2, $point = '.', $separator = '')
{
    return decimal($value, $precision, $point, $separator);
}

function between_for_clickhouse($stringCondition, $targetType = 'intval')
{
    $arrCondition = explode(',', $stringCondition);
    foreach ($arrCondition as &$value) {
        $value = $targetType($value);
    }
    return $arrCondition;
}

function maopao()
{
    $arr = [99,88,77,55,44,66,22,32,31,35,6,9,7,5,1,2,3];
    $len = count($arr);
    $n = count($arr) - 1;
    for ($i = 0; $i < $len; $i++) {
        for ($j = 0; $j < $n - $i; $j++) {
            if ($arr[$j] > $arr[$j + 1]) {
                $tmp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $tmp;
            }
        }
    }
    return $arr;
}

function quick_sort($array) {

    if (count($array) <= 1)
        return $array;

    $key = $array[0];
    $left_arr = [];
    $right_arr = [];
    for ($i=1; $i<count($array); $i++){
        if ($array[$i] <= $key)
            $left_arr[] = $array[$i];
        else
            $right_arr[] = $array[$i];
    }
    $left_arr = quick_sort($left_arr);
    $right_arr = quick_sort($right_arr);
    return array_merge($left_arr, array($key), $right_arr);

}
