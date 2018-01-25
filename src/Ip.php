<?php
/**
 * @link http://chenkby.com
 * @copyright Copyright (c) 2018 ChenGuanQun
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chenkby\ip;

/**
 * IP所属地公共API查询
 *
 * @author Chen GuanQun <99912250@qq.com>
 */
class Ip
{
    /**
     * 查询
     * @param string $ip 要查询的IP地址
     * @param int $timeout 超时时间
     * @return bool|array
     */
    public static function query($ip, $timeout = 2)
    {
        if (!$result = static::queryByTaobao($ip, $timeout)) {
            if (!$result = static::queryBySina($ip, $timeout)) {
                return false;
            } else {
                return $result;
            }
        } else {
            return $result;
        }
    }

    /**
     * 新浪API
     * @param $ip
     * @param $timeout
     * @return bool|mixed
     */
    private static function queryBySina($ip, $timeout)
    {
        $url = 'http://int.dpool.sina.com.cn/iplookup/iplookup.php';
        $result = static::get($url, ['format' => 'json', 'ip' => $ip], $timeout);
        if (stripos($result, 'ret') !== false) {
            $result = json_decode($result, true);
            return [
                'country' => $result['country'],
                'province' => $result['province'],
                'city' => $result['city']
            ];
        } else {
            return false;
        }
    }

    /**
     * 淘宝API
     * @param $ip
     * @param $timeout
     * @return bool|mixed
     */
    private static function queryByTaobao($ip, $timeout)
    {
        $url = 'http://ip.taobao.com/service/getIpInfo.php';
        $result = static::get($url, ['ip' => $ip], $timeout);
        if (stripos($result, 'code') !== false) {
            $result = json_decode($result, true);
            if ($result['code'] == 0) {
                return [
                    'country' => $result['data']['country'],
                    'province' => str_replace('省', '', $result['data']['region']),
                    'city' => str_replace('市', '', $result['data']['city'])
                ];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * CURL GET
     * @param $url
     * @param array $data
     * @param int $timeout
     * @return mixed
     */
    private static function get($url, $data = [], $timeout = 2)
    {
        $ch = curl_init($url . '?' . http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}