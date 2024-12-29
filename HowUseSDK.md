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

## 删除域名解析记录

```php
$response = $sdk->deleteRecord(
    1363    // 记录ID
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

// 删除记录
$response = $sdk->deleteRecord(1363); // 记录ID

if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "删除成功";
} else {
    echo "删除失败: " . $response[1];
}
```

#### 参数解释

- 记录ID：要删除的记录ID（可以通过创建记录时返回的数据获取）

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

- 然后，我们调用了deleteRecord()方法，并传入了记录ID。

```php
$response = $sdk->deleteRecord(1363); // 记录ID
```

- 接着，我们检查了API响应的状态码是否为0(成功)，如果成功，则输出删除成功的信息。

```php
if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "删除成功";
} else {
    echo "删除失败: " . $response[1];
}
```

#### 注意事项

1. 删除记录后无法恢复，请谨慎操作
2. 删除记录不会退还已扣除的积分
3. 确保你有权限删除该记录
4. 建议在删除前先确认记录信息

## 修改域名解析记录

```php
$response = $sdk->updateRecord(
    1363,        // 记录ID
    'A',        // 记录类型
    '2.3.4.5'   // 记录值
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

// 修改记录
$response = $sdk->updateRecord(
    1363,        // record_id: 记录ID
    'A',        // type: 记录类型
    '2.3.4.5'   // value: 记录值
);

if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "修改成功:\n";
    print_r($response[2]);
} else {
    echo "修改失败: " . $response[1];
}
```

#### 参数解释

- 记录ID：要修改的记录ID（可以通过创建记录时返回的数据获取）
- 记录类型：记录类型（仅限A与CNAME）
- 记录值：记录值（A记录填写IPv4地址，CNAME记录填写域名）

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

- 然后，我们调用了updateRecord()方法，并传入了记录ID、记录类型和记录值。

```php
$response = $sdk->updateRecord(
    1363,        // record_id: 记录ID
    'A',        // type: 记录类型
    '2.3.4.5'   // value: 记录值
);
```

- 接着，我们检查了API响应的状态码是否为0(成功)，如果成功，则输出修改成功的信息。

```php
if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "修改成功:\n";
    print_r($response[2]);
} else {
    echo "修改失败: " . $response[1];
}
```

#### 返回数据说明

成功时返回的数据格式：
```php
[
    'record_id' => 1363,     // 记录ID
    'type' => 'A',          // 记录类型
    'value' => '2.3.4.5'    // 记录值
]
```

#### 注意事项

1. 修改记录时，记录类型仅支持A记录和CNAME记录
2. A记录的记录值必须是合法的IPv4地址
3. CNAME记录的记录值必须是合法的域名
4. 修改记录不会产生额外的积分消耗
5. 建议在修改前先确认记录信息

## 获取域名解析记录列表

```php
$response = $sdk->getRecordList();
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

// 获取记录列表
$response = $sdk->getRecordList();

if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "获取成功，共" . $response[2]['total'] . "条记录\n";
    
    // 遍历记录列表
    foreach($response[2]['list'] as $record) {
        echo "记录ID: {$record['record_id']}\n";
        echo "域名: {$record['domain']}\n";
        echo "类型: {$record['type']}\n";
        echo "主机记录: {$record['name']}\n";
        echo "记录值: {$record['value']}\n";
        echo "线路: {$record['line']}\n";
        echo "更新时间: {$record['updated_at']}\n";
        echo "------------------------\n";
    }
} else {
    echo "获取失败: " . $response[1];
}
```

#### 返回数据说明

成功时返回的数据格式：
```php
[
    'total' => 10,           // 记录总数
    'list' => [             // 记录列表
        [
            'record_id' => 1363,     // 记录ID
            'domain' => 'example.com', // 域名
            'type' => 'A',           // 记录类型
            'name' => 'www',         // 主机记录
            'value' => '1.2.3.4',    // 记录值
            'line' => '默认',        // 解析线路
            'updated_at' => '2024-03-21 12:34:56' // 更新时间
        ],
        // ... 更多记录
    ]
]
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

- 然后，我们调用了getRecordList()方法获取记录列表。

```php
$response = $sdk->getRecordList();
```

- 接着，我们检查了API响应的状态码是否为0(成功)，如果成功，则遍历并显示记录列表。

```php
if($response[0] === 0) { // 检查状态码是否为0(成功)
    echo "获取成功，共" . $response[2]['total'] . "条记录\n";
    
    // 遍历记录列表
    foreach($response[2]['list'] as $record) {
        echo "记录ID: {$record['record_id']}\n";
        echo "域名: {$record['domain']}\n";
        echo "类型: {$record['type']}\n";
        echo "主机记录: {$record['name']}\n";
        echo "记录值: {$record['value']}\n";
        echo "线路: {$record['line']}\n";
        echo "更新时间: {$record['updated_at']}\n";
        echo "------------------------\n";
    }
} else {
    echo "获取失败: " . $response[1];
}
```

#### 注意事项

1. 此API返回当前用户的所有解析记录
2. 记录ID可用于后续修改或删除操作
3. 更新时间显示记录最后一次修改的时间
4. 待检查状态的记录可能需要几分钟才能生效
