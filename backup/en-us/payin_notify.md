
## Deposit - Notify

**Notify URL：**

* Notify URL：`notify_url for the request`

**Notification Callback Parameters**

| Parameter Name |Required|Type| Description                                                                                             |
|:---------------|:---|:----- |---------------------------------------------------------------------------------------------------------|
| mch_id         |Yes  |string | Merchant ID                                                                                             |
| out_order_no   |Yes  |string | Order Number                                                                                            
| pay_status     |Yes  |int | Payment status: 1-Pending Payment, 2-Paid/Payment Successful, 3-Order Timeout, Not Paid, 4-Frozen Order |
| amount         |Yes  |string | Order Amount，string Type                                                                                |
| pay_timestamp  |Yes  |int | Unix Timestamp                                                                                          |
| error          |Yes  |string | error or fail message                                                                                   |
| sign           |Yes  |string | Signature                                                                                               |


**Method: Post**

**DEMO**

It is necessary to check if `pay_status == 2` before proceeding to the next step. Naturally, if `pay_status != 2`, no notification will be sent. Here is an example of the data object received by the merchant:

```PHP
{
    "mch_id": "12345678", //Merchant ID
    "out_order_no": "20200708135806101549897485", // Merchant Order Number
    "pay_status": 2,
    "amount": "1.0000", // Order Amount, some channels may have four decimal places.
    "pay_timestamp": 1594187989, // 支付Timestamp
    "sign": "1235e535956e6c288b6fdcae4522a13a" //sign
}


// Request Parameters
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

$sign = md5($queryString . $mch_key); // Append Merchant Key

// Verify if the Signature is Correct

// Verify if our signature matches yours

```

\`Note: If the logical processing is successful, respond with the string 'SUCCESS'; otherwise, return 'FAIL' or other relevant message.\`

**Notification Callback Response**

|Response|Required|Type|Description|
|:----    |:---|:----- |-----   |
|SUCCESS |Yes  |string | Success, no further notifications  |
|fail/other |Yes  |string | unsuccessful, it will continue attempting notifications 5 times, with intervals of 3/9/27/81/243 seconds. |
