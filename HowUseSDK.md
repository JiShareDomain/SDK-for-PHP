# 如何使用JiShareDomainSDK

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

## 获取域名列表

```php
$response = $sdk->getSuffixList();
```

### 来个Demo?

```php
<?php
require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

$sdk = new JiShareDomainSDK(
    '你的API Key',//API Key
    '你的API SafePassKey（SPK）',//API SafePassKey（SPK）
    '你的API地址' // API地址（默认：https://nic.lsdt.top/api）
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
```

#### Demo 解释

- 首先，我们创建了一个JiShareDomainSDK对象，并传入了API Key、API SafePassKey（SPK）和API地址。

```php
$sdk = new JiShareDomainSDK(
    '你的API Key',//API Key
    '你的API SafePassKey（SPK）',//API SafePassKey（SPK）
    '你的API地址' // API地址（默认：https://nic.lsdt.top/api）
);
```
- 注意：不要硬编码API Key、API SafePassKey（SPK）和API地址，请使用环境变量或配置文件来存储这些敏感信息!
- 然后，我们调用了getSuffixList()方法，并获取了返回的API响应。

```php
$response = $sdk->getSuffixList();
```

- 接着，我们检查了API响应的状态码是否为0(成功)，如果成功，则从返回数据中获取列表，并使用HTML表格形式输出。

```php
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
```

- 循环输出:

```php
    foreach($suffixList as $suffix) {
        echo "<tr><td>{$suffix['domain']}</td><td>{$suffix['id']}</td><td>{$suffix['point']}</td></tr>";
    }
```

## 创建域名解析记录

```php
$response = $sdk->createRecord(
    117,                          // 域名ID  
    'ATester',                      // 主机记录
    'A',                    // 记录类型
    '1.1.1.1'              // 记录值
);
```
### 来个Demo?

```php
<?php
require_once 'JiShareDomainSDK.php';

use JiShareDomain\JiShareDomainSDK;

$sdk = new JiShareDomainSDK(
    '你的API Key',//API Key
    '你的API SafePassKey（SPK）',//API SafePassKey（SPK）
    '你的API地址' // API地址（默认：https://nic.lsdt.top/api）
);
// 注意：不要硬编码API Key、API SafePassKey（SPK）和API地址，请使用环境变量或配置文件来存储这些敏感信息!
$response = $sdk->createRecord(
    117,                          // 域名ID  
    'ATester',                      // 主机记录
    'A',                    // 记录类型
    '1.1.1.1'              // 记录值
);

if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "添加成功:\n";
    print_r($response[2]);
} else {
    echo "添加失败: " . $response[1];
}
```
#### 参数解释

- 域名ID：域名ID（请使用getSuffixList()方法获取）
- 主机记录：主机记录
- 记录类型：记录类型（仅限A与CNAME）
- 记录值：记录值

#### Demo 解释

- 首先，我们创建了一个JiShareDomainSDK对象，并传入了API Key、API SafePassKey（SPK）和API地址。

```php
$sdk = new JiShareDomainSDK(
    '你的API Key',//API Key
    '你的API SafePassKey（SPK）',//API SafePassKey（SPK）
    '你的API地址' // API地址（默认：https://nic.lsdt.top/api）
);
```
- 注意：不要硬编码API Key、API SafePassKey（SPK）和API地址，请使用环境变量或配置文件来存储这些敏感信息!

- 然后，我们调用了createRecord()方法，并传入了域名ID、主机记录、记录类型和记录值。

```php
$response = $sdk->createRecord(
    117,                          // 域名ID  
    'ATester',                      // 主机记录
    'A',                    // 记录类型
    '1.1.1.1'              // 记录值
);
```

- 接着，我们检查了API响应的状态码是否为0(成功)，如果成功，则输出添加成功的信息。

```php
if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "添加成功:\n";
    print_r($response[2]);
} else {
    echo "添加失败: " . $response[1];
}
```
