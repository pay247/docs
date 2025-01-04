## 代付-查询订单

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
