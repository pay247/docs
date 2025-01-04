## Integration Instructions

* Method: POST

* Request Header: 
```
"Content-Type":"application/json;charset=UTF-8"
```

* Response Header:
```
"Content-Type":"application/json;charset=UTF-8"
```

* The API operates in the  ```UTC+8``` time zone.

## Common Parameters

* Common Request Parameters

| Parameter Name  | Type    | Required  | Description     | Example         |
|:----------|:------|:----|:-------|:-----------|
| mch_id    | string     | Yes   | Merchant ID | SVSPYS63XM10042 |
| timestamp | string     | Yes   | Timestamp | 1594047539 |
| version   | string     | Yes   | Version | v2.0 |
| sign      | string     | Yes   | Signature     |b05a16b1e92aa4948e164b747b5749b4 |

* Common Response Parameters

| Parameter Name  | Required  | Type      | Description            | Example               |
|:--------|-------|:--------|---------------|:-----------------|
| code    | Yes   | int     | Status Code, Reference Dictionary Status Codes   | 0                |
| message | Yes   | string  | Message/Error Message | 操作成功             |
| data    | Yes   | mixed   | Response Data |  Response data can be an object, an array, or null. |

## Dictionary
* Status Code

| Status Code  | Description   |
|:-----|:-----|
| 0    | Success   |
| 4000 | Parameter Error | 
| 4001 | Duplicate Order |
| 5000 | System Exception |