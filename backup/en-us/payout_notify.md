## 代付-回调通知

**通知url**

通知地址：`发起请求的notify_url`

` 代付订单处理成功或失败都会发送异步通知，需要判断返回结果里的 status 参数为成功或失败做后续的处理`

**通知回调参数**

| Parameter Name                | Required  |Type|Description|
|:-------------------|:----|:----- |-----|
| mch_id             | Yes   |string |Merchant ID  |
| order_no           | Yes   |string |系统订单号 |
| out_order_no       | Yes   |string |Merchant Order Number |
| bank_name          | Yes   |string |银行名称 |
| bank_code          | Yes   |string |银行名称 |
| bank_branch        | 否   |string |支行信息 |
| account_name       | Yes   |string |账号名称 |
| account_no         | Yes   |string | 卡号  |
| amount             | Yes   |string | 金额  |
| fee                | Yes   |string | 手续费 |
| receipt            | 否   |string | 支付凭证 |
| status             | Yes   |int | 代付状态{0-待处理 1-处理中 2-处理成功 3-处理失败/驳回} |
| resolved_timestamp | Yes   |int | Unix Timestamp 确认时间 |
| created_timestamp  | Yes   |int | Unix Timestamp 创建时间 |
| remarks            | Yes   |string | 处理失败{3}的错误提示，其它状态为空字符串 |
| sign               | Yes   |string | Signature |


```

接口版本v1.0 异步发送post form表单
接口版本v2.0 异步发送post json请求

你这边需要判断status == 2 处理成功 或 status == 3 处理失败 才执行下一步操作，否则略过
{
    "mch_id": "12345678", //Merchant ID
    "order_no": "MP20200801170409575199514949", //系统订单号
    "out_order_no": "47791232007162150200884444", //Merchant Order Number
    "bank_name": "中国农业银行", //银行名称
    "bank_code": "ABC", //银行代码
    "bank_branch": "深圳支行", //支行信息
    "account_name": "胡歌", //户名
    "account_no": "622848998832432432", //卡号
    "amount": "100.0000", //代付金额
    "fee": "2.00", //代付手续费
    "receipt": "http://abc.com/abc.jpg", //交易凭证
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
