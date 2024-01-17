# Express

快递鸟的快递查询PHP SDK，最低支持 PHP8.1

[![Latest Stable Version](https://poser.pugx.org/niefufeng/express/v/stable)](https://packagist.org/packages/niefufeng/express)
[![Total Downloads](https://poser.pugx.org/niefufeng/express/downloads)](https://packagist.org/packages/niefufeng/express)
[![License](https://poser.pugx.org/niefufeng/express/license)](https://packagist.org/packages/niefufeng/express)

## 安装

```shell
$ composer require niefufeng/express
```
## 配置

在使用本扩展之前，你需要去 [快递鸟](http://www.kdniao.com/reg) 注册申请，获取到 `app_id` 和 `app_key`。

## 使用

```php
use NieFufeng\Express\Providers\Bird;
use NieFufeng\Express\Enums\BirdShipperCodes;
use NieFufeng\Express\Enums\RequestTypes;

$trackingCode = '88888888';// 快递单号
$shippingCode = BirdShipperCodes::JD;// 快递编号
$phone = '';// 寄件人或者收件人的手机尾号后四位（BirdShipperCodes::SF（顺丰）必填）

$express = new Bird('you app id', 'you app key');
// 请求失败会抛出 ApiException，根据 RequestType 不同，响应格式也不同，请自行调用打印
$info = $express->track($trackingCode, $shippingCode, RequestTypes::StandardEdition);
```

## 参考

- [快递鸟普通版即时查询](https://www.yuque.com/kdnjishuzhichi/dfcrg1/tb8nyy)
- [快递鸟增值版即时查询](https://www.yuque.com/kdnjishuzhichi/dfcrg1/yv7zgv)
- [快递鸟地图版即时查询](https://www.yuque.com/kdnjishuzhichi/dfcrg1/ed5y64)
- [快递鸟快递公司编码](https://www.yuque.com/kdnjishuzhichi/dfcrg1/mza2ln)

## License

MIT