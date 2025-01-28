<?php
/**
 * JiShare域名解析API - 删除解析记录示例代码
 */

require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

// 初始化SDK
$sdk = new JiShareDomainSDK(
    '421b1743b4ec001751a43985e7419126', // API Key
    '4b082ef85a1db7d0da8926a4e0e7292f', // API SafePassKey（SPK）
    'http://127.0.0.2:81/api' // API地址(示例使用的是本地API地址，请为空或替换实际API地址)
);

echo "=== 删除域名解析记录 ===<br><br>\n";

// 删除记录示例
$response = $sdk->deleteRecord(
    1363    // record_id: 记录ID
);

// 处理返回结果
if($response[0] === 0) {
    echo "✓ 删除成功<br><br>\n\n";
} else {
    echo "✗ 删除失败：" . $response[1] . "<br><br>\n\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "详细信息：<br>\n";
        echo "<pre>";
        print_r($response[2]);
        echo "</pre><br>\n";
    }
}

echo "<br>提示：<br>\n";
echo "1. 记录ID可以通过之前创建记录时返回的数据获取<br>\n";
echo "2. 删除记录后无法恢复，请谨慎操作<br>\n";
echo "3. 删除记录会退还已扣除的积分（停站更新版本1.2支持）<br>\n";
