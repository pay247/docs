## Deposit- Create Order

**Channels：**


| channal name  | channel code | remark               | Business Hours |
|:--------------|:-------------|:---------------------|:----------------|
| Alipay         | alipay       | Supports automatic redirection to the app and QR code payments on mobile | 00:00~23:59 |
| Wechat pay      | wechat       | QR Code Payment             | 00:00~23:59 |
| UnionPay       | unionpay     |  UnionPay（银联）               | 00:00~23:59 |
| Gcash         | gcash        | Gcash                | 00:00~23:59 |

![payment process](../images/payment_process.png)

**Request URL：**

- `API Domain /gateway/order/unified`

**Method：**
- POST

**Request Parameters：**

| Parameter Name                | Required  |Type| Description                               |
|:-------------------|:----|:----- |----------------------------------|
| mch_id             | Yes   |string | Merchant ID                              |
| out_order_no       | Yes   |string | Merchant self-generated order number                         |
| currency           | No   |string | Default CNY, supports [CNY, PHP]               |
| amount             | Yes   |string | 99.99 unit in yuan, supports up to two decimals             |
| channel_code       | Yes   |string | Channel code, see the channel description above               |
| notify_url         | Yes   |string | Asynchronous notification address                        |
| return_url         | No   |string | Synchronous redirection address                           |
| client_ip          | Yes   |string | User's IP address for placing an order (must be the user's real IP)           |
| user_id            | Yes   |string | User's unique identifier (if you want to hide the user ID plaintext, you can use MD5 encryption to pass it) |
| payee_id           | Optional  |string | [New] Payer's ID number - used for risk control               |
| payee_name         | Optional  |string | [Required for online banking] Payer's name - used for risk control      |
| payee_phone        | Optional  |string | [New] Payer's phone number - used for risk control-风控使用                  |
| payee_bank_account | Optional  |string | 【新增】 付款人银行卡号-[New] Payer's bank account number - used for risk control                |
| timestamp          | Yes   |string | Unix second-level timestamp                        |
| version            | Yes   |string | API Version: v2.0                       |
| sign               | Yes   |string | Signaturetoken                          |


**Request ParametersExample：**

```
{
  "mch_id": "Q0P5T8DOGN10000",
  "out_order_no": "405189",
  "amount": "100.00",
  "channel_code": "wechat",
  "client_ip": "8.8.8.8",
  "notify_url": "https://x.com/notify",
  "return_url": "https://x.com/return",
  "timestamp": "1693233334",
  "version": "v2.0",
  "sign": "81930c5a04d1c58fd1efe33b06e2ffa7"
}
```

| Response (data)  |Required|Type| Description                 |
|:-------------|:---|:----- |--------------------|
| order_no     |Yes  |string | System order number              |
| out_order_no |Yes  |string | Merchant Order Number              |
| amount       |Yes  |string | 99.00 unit in yuan, supports up to two decimals |
| channel_code |Yes  |string | Channel code, see the channel description above |
| pay_url      |Yes  |string | Payment link, redirect after obtaining         |
| bank_name    |Optional  |string | Bank name                |
| account_no   |Optional  |string | Account number           |
| account_name |Optional  |string | Account name         |

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
    "amount": "59",
    "pay_url": "https://pay.xxx.com/link/DyDpCjDfkr"
  }
}
```