<?php
/**
 * JiShare域名解析API - 修改解析记录示例代码
 */

require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

// 初始化SDK
$sdk = new JiShareDomainSDK(
    '421b1743b4ec001751a43985e7419126', // API Key
    '4b082ef85a1db7d0da8926a4e0e7292f', // API SafePassKey（SPK）
    'http://127.0.0.2:81/api' // API地址(示例使用的是本地API地址，请为空或替换实际API地址)
);

echo "=== 修改域名解析记录 ===<br><br>\n";

// 修改记录示例
$response = $sdk->updateRecord(
    1363,        // record_id: 记录ID
    'A',        // type: 记录类型
    '2.3.4.5'   // value: 记录值
);

// 处理返回结果
if($response[0] === 0) {
    echo "✓ 修改成功<br><br>\n\n";
    echo "记录ID: " . $response[2]['record_id'] . "<br>\n";
    echo "记录类型: " . $response[2]['type'] . "<br>\n";
    echo "记录值: " . $response[2]['value'] . "<br><br>\n\n";
} else {
    echo "✗ 修改失败：" . $response[1] . "<br><br>\n\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "详细信息：<br>\n";
        echo "<pre>";
        print_r($response[2]);
        echo "</pre><br>\n";
    }
}
