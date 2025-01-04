## 签名算法

```
一、组装参数:
    假设所有发送者接收到的数据为集合 M，将集合 M 内非空值的参数，按照参数名 ASCII 码从小到大排
序 ( 字典序 )，使用 URL 键值对的格式拼接成字符串 str："key1=value1&key2=value2&key3=value3..."
特别注意以下重要规则：
1. 参数名 ASCII 码从小到大排序 ( 字典序 )。
2. 参数名区分大小写。
3. 不参与签名的参数：
    空值的参数名
    传送的参数名 sign
二、拼接商户密钥:
    在 str 最后拼接上 商户密钥，并对str以商户密钥进行 md5运算得到sign值。

```


```
// PHP 代码示例
//示例 商户key
$signKey = 'HRvS6WUFtk0hy9CfHgG4YDrRxRlNuAVE';

//示例 请求参数
$data = [
    'mch_id'     => '817719999',
    'out_order_no'   => '20200707014941535010210053',
    'amount'     => '99.00',
    'channel_code'    => 'transferBankCard',
    'notify_url' => 'https://demo.test.com/notify',
    'timestamp'  => '1594047539',
    'version'    => 'v2.0',
];

//请求参数数字字典排序
ksort($data);

$param = [];
foreach ($data as $k => $v) {
    if ($v === '' || $v === null) {
        continue;
    }
    array_push($param, $k . '=' . $v);
}
$queryString = implode('&', $param);

$sign = md5($queryString . $signKey);

```