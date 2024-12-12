<?php
require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

$sdk = new JiShareDomainSDK(
    'xxxxx',//API Key
    'xxxxx',//API SafePassKey（SPK）
    '可为空，默认：https://nic.lsdt.top/api' // API地址
);

// 获取域名列表
$response = $sdk->getSuffixList();
if($response[0] === 0) { // 检查状态码是否为0(成功)
    $suffixList = $response[2]['list']; // 从返回数据中获取列表
    
    // HTML 表格形式输出(循环)
    echo "<table>";
    echo "<tr><th>域名</th><th>DomainID</th><th>所需积分</th></tr>";
    foreach($suffixList as $suffix) {
        echo "<tr><td>{$suffix['domain']}</td><td>{$suffix['id']}</td><td>{$suffix['point']}</td></tr>";
    }
    echo "</table>";
} else {
    echo "获取域名列表失败: " . $response[1]; // 输出错误信息
}
