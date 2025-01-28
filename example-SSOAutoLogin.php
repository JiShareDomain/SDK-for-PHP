<?php
/**
 * JiShare域名解析API - SSO自动登录示例代码
 * 本示例演示如何使用SDK获取SSO自动登录URL，跳过授权页面直接登录
 */

require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

// 初始化SDK
$sdk = new JiShareDomainSDK(
    'cddbd28eb6c5a142509aa2757e43eaa3',//API Key
    'afc802199cd2c7402ae793ec177f964f',//API SafePassKey（SPK）
    'http://127.0.0.2:81/api' // API地址(示例使用的是本地API地址，请为空或替换实际API地址)
);

// 生成随机state参数用于防止CSRF攻击
$state = md5(uniqid(mt_rand(), true));

// 设置回调地址
$callback_url = 'http://kuayv.co.meep.cc:81/API/SDK/forPHP/example-SSOCallback.php';

echo "=== SSO自动登录示例 ===<br>\n";
echo "获取SSO自动登录URL...<br><br>\n\n";

// 获取SSO自动登录URL
$response = $sdk->getSSOAutoLoginUrl(
    $callback_url,    // 回调地址
    $state           // 状态参数
);

// 处理返回结果
if($response[0] === 0) {
    echo "✓ 获取成功<br><br>\n\n";
    
    // 将state保存到session中，以便在回调时验证
    session_start();
    $_SESSION['sso_state'] = $state;
    
    echo "自动登录URL: " . $response[2]['url'] . "<br>\n";
    echo "State: " . $state . "<br><br>\n\n";
    
    // 自动跳转到登录
    echo "正在自动登录...<br>\n";
    echo "<script>setTimeout(function() { window.location.href = '" . $response[2]['url'] . "'; }, 2000);</script>";
} else {
    echo "✗ 获取失败：" . $response[1] . "<br><br>\n\n";
    if(isset($response[2]) && !empty($response[2])) {
        echo "详细信息：<br>\n";
        echo "<pre>";
        print_r($response[2]);
        echo "</pre><br>\n";
    }
}

echo "<br>提示：<br>\n";
echo "1. callback_url 必须是一个有效的URL地址<br>\n";
echo "2. state 参数用于防止CSRF攻击，建议使用随机字符串<br>\n";
echo "3. 自动登录成功后会直接跳转到callback_url，并带上code和state参数<br>\n";
echo "4. 请确保callback_url可以正常访问<br>\n";
echo "5. 自动登录会跳过授权页面，直接进行登录操作<br>\n"; 