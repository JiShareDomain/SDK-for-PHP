<?php
/**
 * JiShare域名解析API - 添加解析记录示例代码
 * 本示例演示如何使用SDK添加A记录和CNAME记录
 */

require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

// 初始化SDK
// 请将以下参数替换为您的实际配置
$sdk = new JiShareDomainSDK(
    '421b1743b4ec001751a43985e7419126',//API Key
    '4b082ef85a1db7d0da8926a4e0e7292f',//API SafePassKey（SPK）
    'http://127.0.0.2:81/api' // API地址(示例使用的是本地API地址，请为空或替换实际API地址)
);

echo "=== 示例1：添加A记录 ===<br>\n";
echo "添加记录: Atest.example.com -> 1.2.3.4<br><br>\n\n";

// 创建A记录示例
$response = $sdk->createRecord(
    117,        // domain_id: 域名ID，可通过 getSuffixList() 获取
    'Atest',    // name: 主机记录，如：www, @, blog, *
    'A',        // type: 记录类型，A记录指向IPv4地址
    '1.2.3.4'   // value: 记录值，A记录填写IPv4地址
);

// 处理返回结果
if($response[0] === 0) {
    echo "✓ 添加成功<br><br>\n\n";
    echo "记录ID: " . $response[2]['record_id'] . "<br>\n";
    echo "完整域名: " . $response[2]['name'] . "." . $response[2]['domain'] . "<br>\n";
    echo "记录类型: " . $response[2]['type'] . "<br>\n";
    echo "记录值: " . $response[2]['value'] . "<br><br>\n\n";
} else {
    echo "✗ 添加失败：" . $response[1] . "<br><br>\n\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "详细信息：<br>\n";
        echo "<pre>";  // 使用pre标签保持格式
        print_r($response[2]);
        echo "</pre><br>\n";
    }
}

echo "<br>=== 示例2：添加CNAME记录 ===<br>\n";
echo "添加记录: cnametest.example.com -> example.com<br><br>\n\n";

// 创建CNAME记录示例
$response = $sdk->createRecord(
    117,            // domain_id: 域名ID
    'cnametest',    // name: 主机记录
    'CNAME',        // type: 记录类型，CNAME记录指向另一个域名
    'example.com'   // value: 记录值，CNAME记录填写目标域名
);

// 处理返回结果
if($response[0] === 0) {
    echo "✓ 添加成功<br><br>\n\n";
    echo "记录ID: " . $response[2]['record_id'] . "<br>\n";
    echo "完整域名: " . $response[2]['name'] . "." . $response[2]['domain'] . "<br>\n";
    echo "记录类型: " . $response[2]['type'] . "<br>\n";
    echo "记录值: " . $response[2]['value'] . "<br><br>\n\n";
} else {
    echo "✗ 添加失败：" . $response[1] . "<br><br>\n\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "详细信息：<br>\n";
        echo "<pre>";
        print_r($response[2]);
        echo "</pre><br>\n";
    }
}

echo "<br>提示：<br>\n";
echo "1. 域名ID可通过 getSuffixList() 方法获取<br>\n";
echo "2. 主机记录不能包含特殊字符，仅支持字母、数字和连字符(-)<br>\n";
echo "3. A记录值必须是合法的IPv4地址<br>\n";
echo "4. CNAME记录值必须是合法的域名<br>\n";
echo "5. 记录ID在后续修改或删除记录时需要使用<br>\n";