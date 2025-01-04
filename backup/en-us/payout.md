### 算签Description

```

//Example 商户key
$signKey = 'HRvS6WUFtk0hy9CfHgG4YDrRxRlNuAVE';

//Example Request Parameters
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
//Example 商户key
$signKey = 'HRvS6WUFtk0hy9CfHgG4YDrRxRlNuAVE';

//Example Request Parameters
$data = [
    'mch_id'     => '817719999',
    'out_order_no'   => '20200707014941535010210053',
    'amount'     => '99.00',
    'channel_code'    => 'transferBankCard',
    'notify_url' => 'https://demo.test.com/notify',
    'timestamp'  => '1594047539',
    'version'    => 'v2.0',
];

//Request Parameters数字字典排序
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

**Request URL：**

- `API Domain //gateway/order/unified`

**Method：**
- POST

**Request Parameters：**

| Parameter Name           | Required |Type| Description                      |
|:--------------|:---------|:----- |----------------------------------|
| mch_id        | Yes      |string | Merchant ID                      |
| out_order_no  | Yes      |string | 商户自生成订单号                         |
| amount        | Yes      |float | 99.00  单位元，支持到两位小数               |
| channel_code  | Yes      |string | 通道code，见文档上方通道描述部分               |
| notify_url    | no       |string | notify url                       |
| client_ip     | Yes      |string | 下单用户的IP地址（一定要是用户的真实IP）           |
| user_id       | Yes      |string | 用户的唯一标识（如果想隐藏用户id明文，可以使用md5加密传递） |
| payee_id      | option   |string | 【新增】付款人身份证号-风控使用                 |
| payee_name    | option   |string | 【新增】付款人姓名-风控使用                   |
| payee_phone   | option   |string | 【新增】付款人手机号-风控使用                  |
| payee_bank_account   | option   |string | 【新增】 付款人银行卡号-风控使用                |
| timestamp     | Yes      |string | unix秒级Timestamp                  |
| version       | Yes      |string | API Version: v2.0                |
| sign          | Yes      |string | Signaturetoken                   |


**Request ParametersExample：**

```
{
  "mch_id": "Q0P5T8DOGN10000",
  "out_order_no": "405189",
  "amount": "100.00",
  "channel_code": "wechat",
  "client_ip": "http://x.com/",
  "notify_url": "8.8.8.8",
  "return_url": "http://x.com/notify",
  "timestamp": "1693233334",
  "version": "v2.0",
  "sign": "81930c5a04d1c58fd1efe33b06e2ffa7"
}
```

| 响应参数名(data)  |Required|Type| Description                 |
|:-------------|:---|:----- |--------------------|
| order_no     |Yes  |string | 系统订单号              |
| out_order_no |Yes  |string | Merchant Order Number              |
| amount       |Yes  |float | 99.00  单位元，支持到两位小数 |
| channel_code |Yes  |string | 通道code，见文档上方通道描述部分 |
| bank_name    |可选  |string | 银行名                |
| account_no   |可选  |string | 账号                 |
| account_name |可选  |string | 账户名                |

**Response Example**

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
    "amount": "59"
  }
}
```


# 代收订单通知

**通知url**

 通知地址：`发起请求的notify_url`

**通知回调参数**

|Parameter Name|Required|Type|Description|
|:----    |:---|:----- |-----   |
|mch_id |Yes  |string |Merchant ID   |
|out_order_no |Yes  |string Order Number |
|pay_status |Yes  |int |支付状态  1 待支付  2 已支付/支付成功  3 订单超时未支付  4 冻结订单   |
|amount |Yes  |string | 订单金额，注意是 string Type  |
|pay_timestamp |Yes  |int | Unix Timestamp 支付时间 |
|sign |Yes  |string | Signature |

```

数据以post提交，为 form 表单格式，直接 $_POST['mch_id'] 即可获取参数不用反序列化JSON
需要判断 pay_status == 2 才执行下一步操作，当然 pay_status != 2 也不会发送通知
商户接收的数据对象样例
{
    "mch_id": "12345678", //Merchant ID
    "out_order_no": "20200708135806101549897485", // Merchant Order Number
    "pay_status": 2,
    "amount": "1.0000", // 订单金额，部分通道可能会带四位小数
    "pay_timestamp": 1594187989, // 支付Timestamp
    "sign": "1235e535956e6c288b6fdcae4522a13a" //签名
}


//Request Parameters数字字典排序
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

|返回值|Required|Type|Description|
|:----    |:---|:----- |-----   |
|SUCCESS |Yes  |string | 成功，不再继续通知  |
|fail/other |Yes  |string | 非成功，则会继续尝试通知5次，分次间隔为3/9/27/81/243秒 |


# 代收订单查询

**Request URL：**

- `API Domain /gateway/order/query`

**Method：**
- POST

**Request Parameters：**


|Parameter Name|Required|Type|Description|
|:----    |:---|:----- |-----   |
|mch_id |Yes  |string |Merchant ID   |
|out_order_no |Yes  |string | Merchant Order Number   |
|timestamp |Yes  |string | Unix Timestamp  |
|version |Yes  |string | API Version: v2.0 |
|sign |Yes  |string | Signature (Last Appended) |

**响应参数：**

|Parameter Name|Required|Type| Description                                 |
|:----    |:---|:----- |------------------------------------|
|out_order_no |Yes  |string | Merchant Order Number                              |
|pay_status |Yes  |string | 支付状态  1 未支付  2 已支付  3 订单超时  4 冻结订单 |
|amount |Yes  |string | 订单金额                               |
|timestamp |Yes  |string | Unix Timestamp                            |

**Response Example**

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

**Request URL：**
- `API Domain /gateway/payment/create`

**Method：**
- POST

**Request Parameters：**

| Parameter Name          |Required|Type| Description                     |
|:-------------|:---|:----- |------------------------|
| mch_id       |Yes  |string | Merchant ID                    |
| out_order_no |Yes  |string | Merchant Order Number                  |
| bank_name    |Yes  |string | 银行中文名称                 |
| bank_branch  |Yes  |string | 支行信息                   |
| account_name |Yes  |string | 开户名                    |
| account_no   |Yes  |string | 账号                     |
| province     |Yes  |string | 所在省(如无，则填北京)           |
| city         |Yes  |string | 所在市如无，则填北京市                    |
| amount       |Yes  |string | 订单金额，单位元，两位小数          |
| notify_url   |否  |string | 异步通知地址，如果不传则自行使用定时查询的方式 |
| timestamp    |Yes  |string | Unix Timestamp                |
| version      |Yes  |string | API Version: v2.0             |
| sign         |Yes  |string | Signature (Last Appended)                |

**Response dataDescription：**

| Parameter Name         |Required|Type|Description|
|:------------|:---|:----- |-----   |
| mch_id      |Yes  |string |Merchant ID   |
| amount      |Yes  |string |代付金额 |
| fee         |Yes  |string |手续费 |
| order_no    |Yes  |string |平台订单号 |
| out_order_no|Yes  |string |Merchant Order Number |
| timestamp   |Yes  |string |创建时间 |

**Response Example**

```
{
    "code": 0,  // int 0为成功，其它都为失败
    "message": "操作成功",  // 成功或失败信息，失败可弹出此消息提示
    "data": {
        "mch_id": "817710000",
        "amount": "100.00",
        "fee": "3.6000",    // 本笔订单收取的手续费
        "order_no": "202007070427475133333",    // 平台订单号
        "out_order_no": "R898543254325432",     // Merchant Order Number
        "timestamp": 1594223433     // 平台订单创建时间
    }
}
```
------

# 代付钱包余额

**Request URL：**
- `API Domain /gateway/payment/balance `

**Method：**
- POST

**Request Parameters：**

|Parameter Name|Required|Type|Description|
|:----    |:---|:----- |-----   |
|mch_id |Yes  |string |Merchant ID   |
|timestamp |Yes  |string | Unix Timestamp  |
|version |Yes  |string | API Version: v2.0 |
|sign |Yes  |string | Signature (Last Appended) |


**Response data**

|Parameter Name|Required|Type|Description|
|:----    |:---|:----- |-----   |
|mch_id |Yes  |string |Merchant ID  |
|balance |Yes  |string |Account Balance |
|timestamp |Yes  |string |Request Time (Unix Timestamp) |

**Response Example**

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

| Parameter Name               |Required|Type|Description|
|:------------------|:---|:----- |-----   |
| mch_id            |Yes  |string |Merchant ID   |
| order_no          |Yes  |string |系统订单号 |
| out_order_no      |Yes  |string |Merchant Order Number |
| bank_name         |Yes  |string |银行名称   |
| bank_branch       |Yes  |string |支行信息   |
| account_name      |Yes  |string |账号名称   |
| account_no        |Yes  |string | 卡号  |
| amount            |Yes  |string | 金额  |
| fee               |Yes  |string | 手续费  |
| receipt           |否  |string | 支付凭证  |
| status            |Yes  |int | 代付状态{0-待处理 1-处理中 2-处理成功 3-处理失败/驳回}  |
| resolved_timestamp|Yes  |int | Unix Timestamp 确认时间 |
| created_timestamp |Yes  |int | Unix Timestamp 创建时间 |
| remarks           |Yes  |string | 处理失败{3}的错误提示，其它状态为空字符串 |
| sign              |Yes  |string | Signature |

```

异步发送post form表单，直接 $_POST['mch_id'] 即可获取参数不用反序列化JSON
你这边需要判断status == 2 处理成功 或 status == 3 处理失败 才执行下一步操作，否则略过
{
"mch_id": "12345678", //Merchant ID
"order_no": "MP20200801170409575199514949", //系统订单号
"out_order_no": "47791232007162150200884444", //Merchant Order Number
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

|返回值|Required|Type|Description|
|:----    |:---|:----- |-----   |
|SUCCESS |Yes  |string | 成功，不再继续通知  |
|fail/other |Yes  |string | 非成功，则会继续尝试通知5次，分次间隔为3/9/27/81/243秒 |


- 查询订单

**Request URL：**
- `API Domain /gateway/payment/query `

**Method：**
- POST
**Request Parameters：**

| Parameter Name         |Required|Type|Description|
|:------------|:---|:----- |-----   |
| mch_id      |Yes  |string |Merchant ID   |
| out_order_no|Yes  |string | Merchant Order Number |
| timestamp   |Yes  |string | Unix Timestamp  |
| version     |Yes  |string | API Version: v2.0 |
| sign        |Yes  |string | Signature (Last Appended) |


**返回参数Descriptiondata**

|Parameter Name|Required|Type|Description|
|:----|:---|:-----|-----|
|mch_id |Yes  |string |Merchant ID  |
|amount |Yes  |string |代付金额 |
|fee |Yes  |string |手续费 |
|status |Yes  |int |状态{0-待审核 1-处理中 2-处理成功 3-处理失败/驳回} |
|order_no |Yes  |string |系统订单号 |
|out_order_no |Yes  |string |Merchant Order Number |
|receipt |否  |string |交易凭证|
|remarks |Yes  |string |处理失败{3}的错误提示，其它状态可能为空字符串 |
|timestamp |Yes  |string |创建Timestamp |
**Response Example**

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
