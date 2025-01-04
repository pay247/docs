## 代付下单

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
|mch_id |是  |string |银行名称   |
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
