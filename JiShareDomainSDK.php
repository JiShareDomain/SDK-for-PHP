<?php
namespace JiShareDomain;

class JiShareDomainSDK
{
    private $apiKey;
    private $apiSecret;
    private $apiUrl = 'https://nic.lsdt.top/api'; // 设置默认API地址
    private $lastResponse; // 用于存储最后的响应
    
    public function __construct($apiKey, $apiSecret, $apiUrl = null)
    {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        if ($apiUrl !== null) {
            $this->apiUrl = rtrim($apiUrl, '/');
        }
    }
    
    /**
     * 获取API Key
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * 获取API URL
     * @return string
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
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
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/domain/suffix/list?' . http_build_query($params));
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
                'http_code' => $httpCode,
                'params' => $params,
                'sign' => $sign,
                'url' => $this->apiUrl . '/domain/record/create'
            ]];
        }
        
        return [
            $result['status'],
            $result['message'],
            isset($result['data']) ? $result['data'] : null
        ];
    }
    
    /**
     * 处理日期字段，确保返回正确的格式
     * @param mixed $date 日期值
     * @return string|null 格式化的日期字符串或null
     */
    private function formatDate($date)
    {
        if (empty($date)) {
            return null;
        }
        
        // 如果已经是字符串格式，直接返回
        if (is_string($date)) {
            return $date;
        }
        
        // 如果是数字（时间戳），直接格式化
        if (is_numeric($date)) {
            return date('Y-m-d H:i:s', (int)$date);
        }
        
        // 如果是对象，尝试转换为字符串
        if (is_object($date)) {
            // 如果对象有 __toString 方法
            if (method_exists($date, '__toString')) {
                return (string)$date;
            }
            // 如果是 DateTime 或类似对象
            if (method_exists($date, 'format')) {
                return $date->format('Y-m-d H:i:s');
            }
        }
        
        // 尝试将任何其他格式转换为时间戳
        $timestamp = strtotime((string)$date);
        if ($timestamp === false) {
            return null;
        }
        
        return date('Y-m-d H:i:s', $timestamp);
    }
    
    /**
     * 获取用户的域名解析记录列表
     * @param bool $rawResponse 是否返回原始JSON响应
     * @return array|string 
     */
    public function getRecordList($rawResponse = false)
    {
        $params = [
            'timestamp' => time()
        ];
        
        $sign = $this->generateSign($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/domain/record/list?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->apiKey,
            'X-API-SIGN: ' . $sign
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if($response === false) {
            return $rawResponse ? '{"status":1,"message":"API请求失败","error":"'.$error.'"}' : 
                [1, 'API请求失败', ['error' => $error]];
        }
        
        if($rawResponse) {
            return $response;
        }
        
        $result = json_decode($response, true);
        if(!$result) {
            return [1, '解析响应失败', [
                'response' => $response,
                'http_code' => $httpCode,
                'params' => $params,
                'sign' => $sign,
                'url' => $this->apiUrl . '/domain/record/list'
            ]];
        }
        
        // 处理日期字段
        if(isset($result['data']['list']) && is_array($result['data']['list'])) {
            foreach($result['data']['list'] as &$record) {
                if(isset($record['created_at'])) {
                    $record['created_at'] = $this->formatDate($record['created_at']);
                }
                if(isset($record['updated_at'])) {
                    $record['updated_at'] = $this->formatDate($record['updated_at']);
                }
                if(isset($record['checked_at'])) {
                    $record['checked_at'] = $this->formatDate($record['checked_at']);
                }
            }
        }
        
        return [
            $result['status'],
            $result['message'],
            isset($result['data']) ? $result['data'] : null
        ];
    }
    
    private function generateSign($params)
    {
        // 按键名对参数进行排序
        ksort($params);
        
        // 构建签名字符串
        $str = '';
        foreach($params as $k => $v) {
            if($k != 'sign' && $k != 'spk' && $v !== '' && $v !== null) {
                $str .= $k . '=' . $v . '&';
            }
        }
        $str = rtrim($str, '&');
        
        // 使用SPK生成签名
        return md5($str . $this->apiSecret);
    }
    
    /**
     * 修改域名解析记录
     * @param int $recordId 记录ID
     * @param string $type 记录类型(A/CNAME)
     * @param string $value 记录值
     * @return array [状态码, 消息, 数据]
     */
    public function updateRecord($recordId, $type, $value, $rawResponse = false)
    {
        $params = [
            'record_id' => $recordId,
            'type' => strtoupper($type),
            'value' => $value,
            'timestamp' => time()
        ];

        $sign = $this->generateSign($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/domain/record/update');
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
        curl_close($ch);

        if($rawResponse) {
            return $response;
        }

        if($response === false) {
            return [1, 'API请求失败', ['error' => curl_error($ch)]];
        }

        $result = json_decode($response, true);
        if(!$result) {
            return [1, '解析响应失败', ['response' => $response]];
        }

        return [
            $result['status'],
            $result['message'],
            isset($result['data']) ? $result['data'] : null
        ];
    }
    
    public function getLastResponse()
    {
        return $this->lastResponse; // 返回最后的响应
    }
    
    /**
     * 删除域名解析记录
     * @param int $recordId 记录ID
     * @return array [状态码, 消息, 数据]
     */
    public function deleteRecord($recordId, $rawResponse = false)
    {
        $params = [
            'record_id' => $recordId,
            'timestamp' => time()
        ];

        $sign = $this->generateSign($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/domain/record/delete');
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
        curl_close($ch);

        if($rawResponse) {
            return $response;
        }

        if($response === false) {
            return [1, 'API请求失败', ['error' => curl_error($ch)]];
        }

        $result = json_decode($response, true);
        if(!$result) {
            return [1, '解析响应失败', ['response' => $response]];
        }

        return [
            $result['status'],
            $result['message'],
            isset($result['data']) ? $result['data'] : null
        ];
    }

    /**
     * 获取SSO授权页面URL
     * @param string $callback_url 回调地址
     * @param string $state 状态参数，用于防止CSRF攻击
     * @return array [状态码, 消息, 数据]
     */
    public function getSSOAuthorizationUrl($callback_url, $state = null, $rawResponse = false)
    {
        $params = [
            'callback_url' => $callback_url,
            'timestamp' => time()
        ];
        
        if ($state !== null) {
            $params['state'] = $state;
        }
        
        $sign = $this->generateSign($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/sso/authorization');
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
        curl_close($ch);
        
        if($rawResponse) {
            return $response;
        }
        
        if($response === false) {
            return [1, 'API请求失败', null];
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
     * 验证SSO回调
     * @param string $code 授权码
     * @param string $state 状态参数
     * @return array [状态码, 消息, 数据]
     */
    public function verifySSOCallback($code, $state = null, $rawResponse = false)
    {
        $params = [
            'code' => $code,
            'timestamp' => time()
        ];
        
        if ($state !== null) {
            $params['state'] = $state;
        }
        
        // 生成签名
        $sign = $this->generateSign($params);
        
        // 记录调试信息
        $debug = [
            'params' => $params,
            'sign_string' => http_build_query($params) . $this->apiSecret,
            'generated_sign' => $sign,
            'api_key' => $this->apiKey,
            'api_url' => $this->apiUrl
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/sso/verify');
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
            return [1, 'API请求失败', array_merge(['error' => $error], $debug)];
        }
        
        $result = json_decode($response, true);
        if(!$result) {
            return [1, '解析响应失败', array_merge([
                'response' => $response,
                'http_code' => $httpCode,
                'error' => $error
            ], $debug)];
        }
        
        // 如果验证失败，返回调试信息
        if($result['status'] !== 0) {
            return [
                $result['status'],
                $result['message'],
                array_merge($result['data'] ?? [], $debug)
            ];
        }
        
        return [
            $result['status'],
            $result['message'],
            isset($result['data']) ? $result['data'] : null
        ];
    }

    /**
     * 获取SSO自动登录URL
     * @param string $callback_url 回调地址
     * @param string $state 状态参数，用于防止CSRF攻击
     * @return array [状态码, 消息, 数据]
     */
    public function getSSOAutoLoginUrl($callback_url, $state = null, $rawResponse = false)
    {
        $params = [
            'callback_url' => $callback_url,
            'timestamp' => time()
        ];
        
        if ($state !== null) {
            $params['state'] = $state;
        }
        
        $sign = $this->generateSign($params);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . '/sso/authorization/auto');
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
        curl_close($ch);
        
        if($rawResponse) {
            return $response;
        }
        
        if($response === false) {
            return [1, 'API请求失败', null];
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
} 