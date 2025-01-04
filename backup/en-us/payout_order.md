## 代付-统一下单

**Channels：**


| 通道名称  | 通道编码   | 备注      | 营业时间 |
|:------|:-------|:--------|:------|
| 银行卡   | bank   | 银行渠道代付  | 00:00~23:59 |
| 支付宝   | alipay | 支付宝代付   | 00:00~23:59 |
| 微信    | wechat | 微信代付    | 00:00~23:59 |
| Gcash | gcash  | Gcash代付 | 00:00~23:59 |

**Request URL：**
- `API Domain /gateway/payment/create`

**Method：**
- POST

**Request Parameters：**

| 参数名          | 必选  |类型| 说明                         |
|:-------------|:----|:----- |----------------------------|
| mch_id       | 是   |string | Merchant ID                        |
| out_order_no | 是   |string | Merchant Order Number                       |
| channel_code | 是   |string | 通道编码                       |
| account_name | 是   |string | 收款人开户名                     |
| account_no   | 是   |string | 收款人账号：如gcash、支付宝、微信账号、银行账号 |
| amount       | 是   |string | 订单金额，单位元，两位小数              |
| notify_url   | 是   |string | 异步通知地址，也可以自行使用定时查询的方式      |
| province     | 否   |string | 【选填】【银行卡渠道】所在省(如无，则填北京)    |
| city         | 否   |string | 【选填】【银行卡渠道】所在市如无，则填北京市     |
| bank_name    | 否   |string | 【选填】【银行卡渠道】银行名称            |
| bank_branch  | 否   |string | 【选填】【银行卡渠道】支行信息            |
| currency     | 否   |string | 【选填】默认CNY,支持【CNY,PHP】      |
| timestamp    | Yes   |string | Unix Timestamp               |
| version      | Yes   |string | API Version: v2.0            |
| sign         | Yes   |string | Signature (Last Appended)               |


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
        "mch_id": "X3DSKDKII2343",
        "amount": "100.00",
        "fee": "3.6000",    // 本笔订单收取的手续费
        "order_no": "202007070427475133333",    // 平台订单号
        "out_order_no": "R898543254325432",     // Merchant Order Number
        "timestamp": 1594223433     // 平台订单创建时间
    }
}
```