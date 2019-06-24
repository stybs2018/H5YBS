<?php

namespace App\Lib;

// Call Request
// 调用请求

class Crq 
{
    // GET 请求
    public static function get($url) 
    {
        $headers = array('User-Agent:Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36');
        $curl = curl_init();
        if(stripos($url,"https://") !== FALSE){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($curl, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
        $Content = curl_exec($curl);
        $Status = curl_getinfo($curl);
        curl_close($curl);
        if(intval($Status["http_code"]) == 200){
            return json_decode($Content, true);
        }else{
            return false;
        }
    }
    
    // POST 请求
    public static function post($url, $data) 
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);    
        curl_setopt($curl, CURLOPT_NOBODY, TRUE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }
}