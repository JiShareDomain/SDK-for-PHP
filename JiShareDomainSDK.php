<?php
namespace JiShareDomain;

class JiShareDomainSDK
{
    private $apiKey;
    private $apiSecret;
    private $apiUrl;
    
    public function __construct($apiKey, $apiSecret, $apiUrl)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->apiUrl = rtrim($apiUrl, '/');
    }
    
    /**
     * 获取可解析域名后缀列表
     * @param bool $rawResponse 是否返回原始JSON响应
     * @return array|string 
     */
    public function getSuffixList($rawResponse = false)
    {
        $params = [
            'timestamp' => time()
        ];
        
        $sign = $this->generateSign($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/domain/suffix/list');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->apiKey,
            'X-API-SIGN: ' . $sign
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if($response === false) {
            return $rawResponse ? '{"status":1,"message":"API请求失败"}' : [1, 'API请求失败', null];
        }
        
        if($rawResponse) {
            return $response;
        }
        
        $result = json_decode($response, true);
        if(!$result) {
            return [1, '解析响应失败', null];
        }
        
        return [
            $result['status'],
            $result['message'],
            isset($result['data']) ? $result['data'] : null
        ];
    }
    
    /**
     * 创建域名解析记录
     * @param int $domainId 域名ID
     * @param string $name 主机记录
     * @param string $type 记录类型(A/CNAME)
     * @param string $value 记录值
     * @return array [状态码, 消息, 数据]
     */
    public function createRecord($domainId, $name, $type, $value, $rawResponse = false)
    {
        $params = [
            'domain_id' => $domainId,
            'name' => $name,
            'type' => strtoupper($type),
            'value' => $value,
            'timestamp' => time()
        ];
        
        $sign = $this->generateSign($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/domain/record/create');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->apiKey,
            'X-API-SIGN: ' . $sign,
            'Content-Type: application/x-www-form-urlencoded'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if($rawResponse) {
            return $response;
        }
        
        if($response === false) {
            return [1, 'API请求失败', ['error' => $error]];
        }
        
        $result = json_decode($response, true);
        if(!$result) {
            return [1, '解析响应失败', [
                'response' => $response,
                'params' => $params,
                'sign' => $sign,
                'url' => $this->apiUrl . '/domain/record/create'
            ]];
        }
        
        return [
            $result['status'],
            $result['message'],
            isset($result['data']) ? $result['data'] : (
                isset($result['debug']) ? ['debug' => $result['debug']] : null
            )
        ];
    }
    
    private function generateSign($params)
    {
        ksort($params);
        $str = '';
        foreach($params as $key => $value) {
            $str .= $key . '=' . $value . '&';
        }
        $str = rtrim($str, '&') . $this->apiSecret;
        return md5($str);
    }
} 