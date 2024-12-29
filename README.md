# JiShareDomain SDK for PHP

## 简介

JiShareDomain SDK for PHP 是一个用于与 JiShareDomain API (或使用 JSD DNS 开源项目搭建的二级域名平台) 进行交互的 PHP SDK。

## 使用

```bash
git clone https://github.com/JiShareDomain/SDK-for-PHP.git
```

- 仅保留 JiShareDomainSDK.php 文件，并将其放置在您的项目目录中。
- 如需要，可参考 HowUseSDK.md 文件中的示例代码（或以example-***.php为参考）。

## 注意事项

- 不要硬编码 API Key、API SafePassKey（SPK）和 API 地址，请使用环境变量或配置文件来存储这些敏感信息！
- 请确保您的 API 地址是正确的，否则可能会导致 API 调用失败。
- 请确保您的 API Key 和 API SafePassKey（SPK）是正确的，否则可能会导致 API 调用失败。
- 默认API地址为：https://nic.lsdt.top/api

## 感谢

- 感谢 [KL DMS](https://github.com/klsf/kldns) 这是JSD DNS与JiShareDomain SDK的根基（因为Fork关系，所以感谢）
