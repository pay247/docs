## 代付下单

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
| notify_url   |No  |string | 异步通知地址，如果不传则自行使用定时查询的方式 |
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
| receipt           |No  |string | 支付凭证  |
| status            |Yes  |int | 代付状态{0-待处理 1-处理中 2-处理成功 3-处理失败/驳回}  |
| resolved_timestamp|Yes  |int | Unix Timestamp 确认时间 |
| created_timestamp |Yes  |int | Unix Timestamp 创建时间 |
| remarks           |Yes  |string | 处理失败{3}的错误提示，其它状态为空字符串 |
| sign              |Yes  |string | Signature |

```

异步发送post form表单，直接 $_POST['mch_id'] 即可获取参数不用反序列化JSON
你这边需要判断status == 2 处理成功 或 status == 3 处理失败 才执行下一步操作，No则略过
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


**Reponse Parameters**

|Parameter Name|Required|Type|Description|
|:----|:---|:-----|-----|
|mch_id |Yes  |string |Merchant ID  |
|amount |Yes  |string |oerder amount |
|fee |Yes  |string |fee |
|status |Yes  |int |Status {0-Pending Review, 1-Processing, 2-Processed Successfully, 3-Processing Failed/Rejected} |
|order_no |Yes  |string |System order no. |
|out_order_no |Yes  |string |Merchant Order Number |
|receipt |No  |string |Payment Voucher|
|remarks |Yes  |string |Error message for status {3} (Processing Failed): Other statuses may be an empty string. |
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
    "status": 0, //Status {0-Pending Review, 1-Processing, 2-Processed Successfully, 3-Processing Failed/Rejected}
    "order_no": "202007070427475133333",
    "out_order_no": "202007070427475133333",
    "receipt": "", // Payment Voucher
    "remarks": "",
    "timestamp": 1594223433
  }
}
```
