### 算签说明

```

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

//将对应的参数以字典(ksort)排序，去除空值，然后将所有参数以&字符连接，得到
$string = 'amount=99.00&channel_code=transferBankCard&mch_id=81771999&
notify_url=https://demo.test.com/notify&order_no=20200707014941535010210053&timestamp=1594047539&version=v2.0'

然后 md5($string+$mch_key)
得到sign：eaec9375f38da58afd4682b6c055a8f2

// 参数列表追加sign
$data['sign'] = 'eaec9375f38da58afd4682b6c055a8f2';

post发送整个data


// php 样列
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


# 代收下单

**通道描述：**


| 通道名称 | 通道编码    | 备注               | 营业时间 |
|:-----|:--------|:-----------------|:------|
| 支付宝  | alipay  | 支持手机自动跳转APP、扫码支付 | 00:00~23:59 |
| 微信   | wechat  | 扫码支付             | 00:00~23:59 |
| 网银支付 | unionpay | PC网银支付           | 00:00~23:59 |

**请求URL：**

- `接口域名//gateway/order/unified`

**请求方式：**
- POST

**请求参数：**

| 参数名           |必选|类型| 说明                               |
|:--------------|:--|:----- |----------------------------------|
| mch_id        |是 |string | 商户号                              |
| out_order_no  |是 |string | 商户自生成订单号                         |
| amount        |是 |float | 99.00  单位元，支持到两位小数               |
| channel_code  |是 |string | 通道code，见文档上方通道描述部分               |
| notify_url    |否  |string | 异步通知地址，若要尽快拿到支付结果，需要回调地址                           |
| client_ip     |是 |string | 下单用户的IP地址（一定要是用户的真实IP）           |
| user_id       |是 |string | 用户的唯一标识（如果想隐藏用户id明文，可以使用md5加密传递） |
| payee_id      |可选 |string | 【新增】付款人身份证号-风控使用                 |
| payee_name    |可选 |string | 【新增】付款人姓名-风控使用                       |
| payee_phone   |可选 |string | 【新增】付款人手机号-风控使用                      |
| payee_bank_account   |可选 |string |【新增】 付款人银行卡号-风控使用                     |
| timestamp     |是 |string | unix秒级时间戳                        |
| version       |是 |string | 接口版本: v2.0                       |
| sign          |是 |string | 签名token                          |


**请求参数示例：**

```
{
  "mch_id": "Q0P5T8DOGN10000",
  "out_order_no": "405189",
  "amount": "100.00",
  "channel_code": "wechat",
  "client_ip": "8.8.8.8",
  "notify_url": "http://x.com/notify",
  "timestamp": "1693233334",
  "version": "v2.0",
  "sign": "81930c5a04d1c58fd1efe33b06e2ffa7"
}
```

| 响应参数名(data)  |必选|类型| 说明                 |
|:-------------|:---|:----- |--------------------|
| order_no     |是  |string | 系统订单号              |
| out_order_no |是  |string | 商户订单号              |
| amount       |是  |float | 99.00  单位元，支持到两位小数 |
| channel_code |是  |string | 通道code，见文档上方通道描述部分 |
| bank_name    |可选  |string | 银行名                |
| account_no   |可选  |string | 账号                 |
| account_name |可选  |string | 账户名                |

**返回示例**

```
{
  "code": 0,
  "message": "success",
  "data": {
    "order_no": "MO202308282232593669984082",
    "out_order_no": "XX_5042373",
    "channel_code": "wechat",
    "bank_name": "", 
    "account_no": "",
    "account_name": "",
    "amount": "59",
    "pay_url": "https://pay.xxx.com/link/DyDpCjDfkr"
  }
}
```


# 代收订单通知

**通知url**

 通知地址：`发起请求的notify_url`

**通知回调参数**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|mch_id |是  |string |商户号   |
|out_order_no |是  |string |订单号 |
|pay_status |是  |int |支付状态  1 待支付  2 已支付/支付成功  3 订单超时未支付  4 冻结订单   |
|amount |是  |string | 订单金额，注意是 string 类型  |
|pay_timestamp |是  |int | unix时间戳 支付时间 |
|sign |是  |string | 签名 |

```

数据以post提交，为 form 表单格式，直接 $_POST['mch_id'] 即可获取参数不用反序列化JSON
需要判断 pay_status == 2 才执行下一步操作，当然 pay_status != 2 也不会发送通知
商户接收的数据对象样例
{
    "mch_id": "12345678", //商户号
    "out_order_no": "20200708135806101549897485", // 商户订单号
    "pay_status": 2,
    "amount": "1.0000", // 订单金额，部分通道可能会带四位小数
    "pay_timestamp": 1594187989, // 支付时间戳
    "sign": "1235e535956e6c288b6fdcae4522a13a" //签名
}


//请求参数数字字典排序
ksort($data);

unset($data['sign']);
$param = [];
foreach ($data as $k => $v) {
    if ($v === '' || $v === null) {
        continue;
    }
    array_push($param, $k . '=' . $v);
}
$queryString = implode('&', $param);

$sign = md5($queryString . $mch_key); // 追加商户key

//判断签名是否正确

判断我们的签名和你的签名是否一致

```

\`注：逻辑处理成功响应字符串 SUCCESS，否则返回 FAIL 或其它提示\`

**通知回调响应结果**

|返回值|必选|类型|说明|
|:----    |:---|:----- |-----   |
|SUCCESS |是  |string | 成功，不再继续通知  |
|fail/other |是  |string | 非成功，则会继续尝试通知5次，分次间隔为3/9/27/81/243秒 |


# 代收订单查询

**请求URL：**

- `接口域名/gateway/order/query`

**请求方式：**
- POST

**请求参数：**


|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|mch_id |是  |string |商户号   |
|out_order_no |是  |string | 商户订单号   |
|timestamp |是  |string | unix时间戳  |
|version |是  |string | 接口版本: v2.0 |
|sign |是  |string | 最后追加的签名 |

**响应参数：**

|参数名|必选|类型| 说明                                 |
|:----    |:---|:----- |------------------------------------|
|out_order_no |是  |string | 商户订单号                              |
|pay_status |是  |string | 支付状态  1 未支付  2 已支付  3 订单超时  4 冻结订单 |
|amount |是  |string | 订单金额                               |
|timestamp |是  |string | unix时间戳                            |

**返回示例**

```
{
    "code": 0,  // int 0为成功，其它都为失败
    "message": "操作成功",  // 成功或失败信息，失败可弹出此消息提示
    "data": {
        "out_order_no": "Test20200707042747519753974848",
        "pay_status": 1, //支付状态  1 未支付  2 已支付  3 订单超时  4 冻结订单
        "amount": "1.0000",
        "timestamp": 1594099906
    }
}
```

# 代付下单

**请求URL：**
- `接口域名/gateway/payment/create`

**请求方式：**
- POST

**请求参数：**

| 参数名          |必选|类型| 说明                     |
|:-------------|:---|:----- |------------------------|
| mch_id       |是  |string | 商户号                    |
| out_order_no |是  |string | 商户订单号                  |
| bank_name    |是  |string | 银行中文名称                 |
| bank_branch  |是  |string | 支行信息                   |
| account_name |是  |string | 开户名                    |
| account_no   |是  |string | 账号                     |
| province     |是  |string | 所在省(如无，则填北京)           |
| city         |是  |string | 所在市如无，则填北京市                    |
| amount       |是  |string | 订单金额，单位元，两位小数          |
| notify_url   |否  |string | 异步通知地址，如果不传则自行使用定时查询的方式 |
| timestamp    |是  |string | unix时间戳                |
| version      |是  |string | 接口版本: v2.0             |
| sign         |是  |string | 最后追加的签名                |

**返回参数data说明：**

| 参数名         |必选|类型|说明|
|:------------|:---|:----- |-----   |
| mch_id      |是  |string |商户号   |
| amount      |是  |string |代付金额 |
| fee         |是  |string |手续费 |
| order_no    |是  |string |平台订单号 |
| out_order_no|是  |string |商户订单号 |
| timestamp   |是  |string |创建时间 |

**返回示例**

```
{
    "code": 0,  // int 0为成功，其它都为失败
    "message": "操作成功",  // 成功或失败信息，失败可弹出此消息提示
    "data": {
        "mch_id": "817710000",
        "amount": "100.00",
        "fee": "3.6000",    // 本笔订单收取的手续费
        "order_no": "202007070427475133333",    // 平台订单号
        "out_order_no": "R898543254325432",     // 商户订单号
        "timestamp": 1594223433     // 平台订单创建时间
    }
}
```
------

# 代付钱包余额

**请求URL：**
- `接口域名/gateway/payment/balance `

**请求方式：**
- POST

**请求参数：**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|mch_id |是  |string |商户号   |
|timestamp |是  |string | unix时间戳  |
|version |是  |string | 接口版本: v2.0 |
|sign |是  |string | 最后追加的签名 |


**返回参数data**

|参数名|必选|类型|说明|
|:----    |:---|:----- |-----   |
|mch_id |是  |string |商户号   |
|balance |是  |string |账户余额 |
|timestamp |是  |string |请求时间 |

**返回示例**

```
{
    "code": 0,
    "message": "操作成功",
    "data": {
        "mch_id": "817710000",
        "balance": "9744.3700",
        "timestamp": 1594223433
    }
}
```



# 代付回调

**通知url**

通知地址：`发起请求的notify_url`

` 代付订单处理成功或失败都会发送异步通知，需要判断返回结果里的 status 参数为成功或失败做后续的处理`

**通知回调参数**

| 参数名               |必选|类型|说明|
|:------------------|:---|:----- |-----   |
| mch_id            |是  |string |商户号   |
| order_no          |是  |string |系统订单号 |
| out_order_no      |是  |string |商户订单号 |
| bank_name         |是  |string |银行名称   |
| bank_branch       |是  |string |支行信息   |
| account_name      |是  |string |账号名称   |
| account_no        |是  |string | 卡号  |
| amount            |是  |string | 金额  |
| fee               |是  |string | 手续费  |
| receipt           |否  |string | 支付凭证  |
| status            |是  |int | 代付状态{0-待处理 1-处理中 2-处理成功 3-处理失败/驳回}  |
| resolved_timestamp|是  |int | unix时间戳 确认时间 |
| created_timestamp |是  |int | unix时间戳 创建时间 |
| remarks           |是  |string | 处理失败{3}的错误提示，其它状态为空字符串 |
| sign              |是  |string | 签名 |

```

异步发送post form表单，直接 $_POST['mch_id'] 即可获取参数不用反序列化JSON
你这边需要判断status == 2 处理成功 或 status == 3 处理失败 才执行下一步操作，否则略过
{
"mch_id": "12345678", //商户号
"order_no": "MP20200801170409575199514949", //系统订单号
"out_order_no": "47791232007162150200884444", //商户订单号
"bank_name": "中国农业银行", //银行名称
"bank_branch": "深圳支行", //支行信息
"account_name": "胡歌", //户名
"account_no": "622848998832432432", //卡号
"amount": "100.0000", //代付金额
"fee": "2.00", //代付手续费
"receipt": "http://xx.com/ddd.jpg", //交易凭证
"status": 2, //代付状态{2-处理成功 3-处理失败}
"resolved_timestamp": 1597124856, // 处理时间
"created_timestamp": 1597124856, // 创建时间
"remarks": "", // 错误提示
"sign": "1235e535956e6c288b6fdcae4522a13a" //签名
}


```


`逻辑处理成功需响应字符串 success, 处理失败需返回 fail 或其它提示`

**通知回调响应结果**

|返回值|必选|类型|说明|
|:----    |:---|:----- |-----   |
|SUCCESS |是  |string | 成功，不再继续通知  |
|fail/other |是  |string | 非成功，则会继续尝试通知5次，分次间隔为3/9/27/81/243秒 |


- 查询订单

**请求URL：**
- `接口域名/gateway/payment/query `

**请求方式：**
- POST
**请求参数：**

| 参数名         |必选|类型|说明|
|:------------|:---|:----- |-----   |
| mch_id      |是  |string |商户号   |
| out_order_no|是  |string | 商户订单号 |
| timestamp   |是  |string | unix时间戳  |
| version     |是  |string | 接口版本: v2.0 |
| sign        |是  |string | 最后追加的签名 |


**返回参数说明data**

|参数名|必选|类型|说明|
|:----|:---|:-----|-----|
|mch_id |是  |string |商户号   |
|amount |是  |string |代付金额 |
|fee |是  |string |手续费 |
|status |是  |int |状态{0-待审核 1-处理中 2-处理成功 3-处理失败/驳回} |
|order_no |是  |string |系统订单号 |
|out_order_no |是  |string |商户订单号 |
|receipt |否  |string |交易凭证|
|remarks |是  |string |处理失败{3}的错误提示，其它状态可能为空字符串 |
|timestamp |是  |string |创建时间戳 |
**返回示例**

```
{
  "code": 0,
  "message": "操作成功",
  "data": {
    "mch_id": "817710000",
    "amount": "100.0000",
    "fee": "3.60",
    "status": 0, //{0-待审核 1-处理中 2-审核通过 3-审核拒绝}
    "order_no": "202007070427475133333",
    "out_order_no": "202007070427475133333",
    "receipt": "", //支付凭证
    "remarks": "",
    "timestamp": 1594223433
  }
}
```
