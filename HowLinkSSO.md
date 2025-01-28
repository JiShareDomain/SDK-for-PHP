# 如何使用JiShareDomainSDK的SSO功能

## 引用SDK

```php
<?php
require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;
```

## 初始化SDK

```php
$sdk = new JiShareDomainSDK(
    '你的API Key',//API Key
    '你的API SafePassKey（SPK）',//API SafePassKey（SPK）
    '你的API地址' // API地址（默认：https://nic.lsdt.top/api）
);
```

- 注意：不要硬编码API Key、API SafePassKey（SPK）和API地址，请使用环境变量或配置文件来存储这些敏感信息!

### 解释配置项

- API Key：你的API Key
- API SafePassKey（SPK）：你的API SafePassKey（SPK）
- API地址：你的API地址（默认：https://nic.lsdt.top/api）

### 申请API Key和API SafePassKey（SPK）

- 登录[鸡享域](https://nic.lsdt.top)
- 点击[侧边栏>API](https://nic.lsdt.top/home/api)
- 在弹出的界面中，点击申请API按钮
- 页面将会刷新，并显示API Key和API SafePassKey（SPK）
- 请务必保存API Key和API SafePassKey（SPK），因为它们是后续使用API的凭证，并且不能泄露给他人。
- 若已泄露，请点击[侧边栏>API](https://nic.lsdt.top/home/api)，点击重置API按钮，然后重新申请API Key和API SafePassKey（SPK）。
- 或直接点击禁止，等到你完成善后工作后再启用。

## SSO登录流程

### 1. 获取SSO授权页面URL

使用`getSSOAuthorizationUrl`方法获取SSO授权页面URL：

```php
// 生成随机state参数用于防止CSRF攻击
$state = md5(uniqid(mt_rand(), true));

// 设置回调地址
$callback_url = '你的回调地址';

// 获取SSO授权页面URL
$response = $sdk->getSSOAuthorizationUrl(
    $callback_url,    // 回调地址
    $state           // 状态参数
);

if($response[0] === 0) {
    // 将state保存到session中，以便在回调时验证
    session_start();
    $_SESSION['sso_state'] = $state;
    
    // 跳转到授权页面
    header('Location: ' . $response[2]['url']);
}
```

#### 参数说明
- `callback_url`: 授权成功后的回调地址
- `state`: 用于防止CSRF攻击的随机字符串

### 2. 处理SSO回调

在回调页面中处理授权结果：

```php
// 验证state参数，防止CSRF攻击
session_start();
$stored_state = isset($_SESSION['sso_state']) ? $_SESSION['sso_state'] : '';
$received_state = isset($_GET['state']) ? $_GET['state'] : '';

if (empty($stored_state) || $stored_state !== $received_state) {
    die("State验证失败：可能存在CSRF攻击风险");
}

// 获取授权码
$code = isset($_GET['code']) ? $_GET['code'] : '';

if (empty($code)) {
    die("未收到授权码");
}

// 验证SSO回调
$response = $sdk->verifySSOCallback(
    $code,           // 授权码
    $received_state  // 状态参数
);

if($response[0] === 0) {
    // 验证成功，可以获取用户信息
    $user_info = $response[2]['user'];
    
    // 清除session中的state
    unset($_SESSION['sso_state']);
    
    // 处理登录逻辑...
}
```

### 3. SSO自动登录（可选）

如果需要跳过授权页面直接登录，可以使用`getSSOAutoLoginUrl`方法：

```php
// 生成随机state参数
$state = md5(uniqid(mt_rand(), true));

// 设置回调地址
$callback_url = '你的回调地址';

// 获取SSO自动登录URL
$response = $sdk->getSSOAutoLoginUrl(
    $callback_url,    // 回调地址
    $state           // 状态参数
);

if($response[0] === 0) {
    // 将state保存到session中
    session_start();
    $_SESSION['sso_state'] = $state;
    
    // 跳转到自动登录URL
    header('Location: ' . $response[2]['url']);
}
```

## 返回数据说明

### 用户信息格式
成功验证SSO回调后，返回的用户信息格式如下：
```php
[
    'user' => [
        'id' => 123,                    // 用户ID
        'username' => 'example',        // 用户名
        'email' => 'user@example.com',  // 邮箱
        'status' => 1,                  // 账号状态
        // ... 其他用户信息
    ]
]
```

## 注意事项

1. 安全性
   - 必须验证state参数，防止CSRF攻击
   - 授权码(code)只能使用一次
   - 不要在客户端暴露API Key和SPK
   - 建议使用HTTPS进行通信

2. 回调处理
   - callback_url必须是一个有效的URL地址
   - 确保callback_url可以正常访问
   - 验证成功后及时清除session中的state
   - 妥善处理错误情况

3. 自动登录
   - 自动登录会跳过授权页面，直接进行登录
   - 需要确保用户已经授权过该应用
   - 建议只在特定场景下使用自动登录

4. 错误处理
   - 检查API Key和SPK是否正确
   - 验证签名生成的参数是否完整且顺序正确
   - 妥善处理API返回的错误信息

## 完整示例代码

请参考以下示例文件：
- `example-SSOLogin.php`: SSO登录示例
- `example-SSOCallback.php`: SSO回调处理示例
- `example-SSOAutoLogin.php`: SSO自动登录示例