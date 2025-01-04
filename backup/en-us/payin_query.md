
## Deposit-Order Inquiry

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

**Response Parameters：**

|Parameter Name|Required|Type| Description                                 |
|:----    |:---|:----- |------------------------------------|
|out_order_no |Yes  |string | Merchant Order Number                              |
|pay_status |Yes  |string | Payment status: 1-Pending Payment, 2-Paid/Payment Successful, 3-Order Timeout, Not Paid, 4-Frozen Order |
|amount |Yes  |string | order amount                               |
|timestamp |Yes  |string | Unix Timestamp                            |

**Response Example**

```
{
    "code": 0,  // int, 0 indicates success, other values indicate failure
    "message": "操作成功",  // Success or failure message; for failures, this message can be displayed as a prompt
    "data": {
        "out_order_no": "Test20200707042747519753974848",
        "pay_status": 1, //Payment status: 1-Pending Payment, 2-Paid/Payment Successful, 3-Order Timeout, Not Paid, 4-Frozen Order
        "amount": "1.0000",
        "timestamp": 1594099906
    }
}
```