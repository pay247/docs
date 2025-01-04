## Withdrawal Account Balance

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
