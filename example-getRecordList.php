<?php
/**
 * JiShare域名解析API - 获取解析记录列表示例代码
 * 本示例演示如何获取当前用户的所有域名解析记录
 */

require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

// 初始化SDK
// 请将以下参数替换为您的实际配置
$sdk = new JiShareDomainSDK(
    '421b1743b4ec001751a43985e7419126',    // API Key
    '4b082ef85a1db7d0da8926a4e0e7292f',    // API SafePassKey（SPK）
    'http://127.0.0.2:81/api'                  // API地址(示例使用的是本地API地址，请为空或替换实际API地址)
);

echo "=== 获取域名解析记录列表 ===<br><br>\n\n";

// 获取记录列表
$response = $sdk->getRecordList();

// 处理返回结果
if($response[0] === 0) {
    echo "✓ 获取成功，共" . $response[2]['total'] . "条记录<br><br>\n\n";
    
    // 使用HTML表格显示记录
    echo "<table border='1' cellpadding='5' cellspacing='0'>\n";
    echo "<tr><th>记录ID</th><th>域名</th><th>类型</th><th>主机记录</th><th>记录值</th><th>线路</th><th>更新时间</th></tr>\n";
    
    foreach($response[2]['list'] as $record) {
        echo "<tr>";
        echo "<td>{$record['record_id']}</td>";
        echo "<td>{$record['domain']}</td>";
        echo "<td>{$record['type']}</td>";
        echo "<td>{$record['name']}</td>";
        echo "<td>{$record['value']}</td>";
        echo "<td>{$record['line']}</td>";
        echo "<td>{$record['updated_at']}</td>";
        echo "</tr>\n";
    }
    echo "</table><br><br>\n\n";
} else {
    echo "✗ 获取失败：" . $response[1] . "<br><br>\n\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "详细信息：<br>\n";
        echo "HTTP状态码: " . $response[2]['http_code'] . "<br>\n";
        echo "服务器响应: " . $response[2]['response'] . "<br>\n";
        echo "请求URL: " . $response[2]['url'] . "<br>\n";
        echo "请求参数: <br>\n";
        echo "<pre>";
        print_r($response[2]['params']);
        echo "</pre>";
        echo "签名: " . $response[2]['sign'] . "<br><br>\n\n";
    }
}

echo "提示：<br>\n";
echo "1. 此API返回当前用户的所有解析记录<br>\n";
echo "2. 记录ID可用于后续修改或删除操作<br>\n";
echo "3. 更新时间显示记录最后一次修改的时间<br>\n";
echo "4. 待检查状态的记录可能需要几分钟才能生效<br><br>\n\n";