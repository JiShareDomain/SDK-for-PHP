<?php
/**
 * JiShare域名解析API - SSO回调示例代码
 * 本示例演示如何处理SSO回调并验证授权
 */

require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

// 开启错误显示
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 初始化SDK
$sdk = new JiShareDomainSDK(
    'cddbd28eb6c5a142509aa2757e43eaa3',//API Key
    'afc802199cd2c7402ae793ec177f964f',//API SafePassKey（SPK）
    'http://127.0.0.2:81/api' // API地址(示例使用的是本地API地址，请为空或替换实际API地址)
);

echo "=== SSO回调处理示例 ===<br>\n";
echo "当前时间戳: " . time() . "<br>\n";
echo "当前URL: " . $_SERVER['REQUEST_URI'] . "<br><br>\n\n";

// 验证state参数，防止CSRF攻击
session_start();
$stored_state = isset($_SESSION['sso_state']) ? $_SESSION['sso_state'] : '';
$received_state = isset($_GET['state']) ? $_GET['state'] : '';

echo "State验证：<br>\n";
echo "- 存储的state: " . $stored_state . "<br>\n";
echo "- 接收的state: " . $received_state . "<br><br>\n\n";

if (empty($stored_state) || $stored_state !== $received_state) {
    die("✗ State验证失败：可能存在CSRF攻击风险<br>\n");
}

// 获取授权码
$code = isset($_GET['code']) ? $_GET['code'] : '';
echo "授权码：" . $code . "<br><br>\n\n";

if (empty($code)) {
    die("✗ 未收到授权码<br>\n");
}

echo "正在验证授权...<br>\n";
echo "API信息：<br>\n";
echo "- API Key: " . $sdk->getApiKey() . "<br>\n";
echo "- API URL: " . $sdk->getApiUrl() . "<br><br>\n\n";

// 验证SSO回调
$response = $sdk->verifySSOCallback(
    $code,           // 授权码
    $received_state  // 状态参数
);

// 处理返回结果
if($response[0] === 0) {
    echo "✓ 验证成功<br><br>\n\n";
    
    // 清除session中的state
    unset($_SESSION['sso_state']);
    
    echo "用户信息：<br>\n";
    echo "<pre>";
    print_r($response[2]['user']);
    echo "</pre><br>\n";
} else {
    echo "✗ 验证失败：" . $response[1] . "<br><br>\n\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "详细信息：<br>\n";
        echo "<pre>";
        print_r($response[2]);
        echo "</pre><br>\n";
    }
}

echo "<br>提示：<br>\n";
echo "1. 请确保state参数与发起SSO请求时的state参数一致<br>\n";
echo "2. 授权码(code)只能使用一次<br>\n";
echo "3. 验证成功后，请及时处理用户登录状态<br>\n";
echo "4. 建议在验证成功后清除session中的state<br>\n";
echo "5. 如果遇到API验证失败，请检查API Key和SPK是否正确<br>\n";
echo "6. 检查签名生成的参数是否完整且顺序正确<br>\n"; 