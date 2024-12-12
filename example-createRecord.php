<?php
require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

$sdk = new JiShareDomainSDK(
    'xxxxx',//API Key
    'xxxxx',//API SafePassKey（SPK）
    '可为空，默认：https://nic.lsdt.top/api' // API地址
);

// 创建A记录示例
$response = $sdk->createRecord(
    117,                      // 域名ID
    'Atest',                 // 主机记录
    'A',                    // 记录类型
    '1.2.3.4'              // 记录值
);

if($response[0] === 0) {
    echo "添加成功:\n";
    print_r($response[2]);
} else {
    echo "《1》添加失败: " . $response[1] . "\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "调试信息:\n";
        print_r($response[2]);
    }
}

// 创建CNAME记录示例
$response = $sdk->createRecord(
    117,                          // 域名ID  
    'cnametest',                      // 主机记录
    'CNAME',                    // 记录类型
    'example.com'              // 记录值
);

if($response[0] === 0) {
    echo "添加成功:\n";
    print_r($response[2]);
} else {
    echo "《2》添加失败: " . $response[1] . "\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "调试信息:\n";
        print_r($response[2]);
    }
}