# IP所属地查询
使用淘宝及sina公共API查询
## 安装

composer

```bash
$ php composer.phar require chenkby/ip:*
```

或者添加以下代码到`composer.json`文件的`require`块中：

```
"chenkby/ip": "*"
```

## 使用

```php
Ip::query('8.8.8.8');
```

## 返回值

```php

Array
(
    [country] => 中国
    [province] => 广东
    [city] => 广州
)

```