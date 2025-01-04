<?php

//示例 代付-统一订单

$mch_id = "xxx";
$signKey = 'xxx';

$order_no = testPayment . phpdate("YmdHis") . time();
$account_name = "黄某人";
$account_no = "6217996100017782600";
$bank_name = "中国邮政储蓄银行";
$bank_code = "PSBC";
$province = "广西省";
$city = "桂林市";
$bank_branch_name = "邮政储蓄桂林分行";
$amount = 101;
//示例 请求参数
$data = [
    'mch_id'     => $mch_id,
    'mch_order_no'   => $order_no,
    'bank_name' => $bank_name,
    'bank_code' => $bank_code,
    'bank_branch' => $bank_branch_name,
    'account_name' => $account_name,
    'account_no' => $account_no,
    'amount'     => $amount,
    'province'   => $province,
    'city'   => $city,
    'notify_url' => 'https://notify.api.com/notify/xxx',
    'timestamp'  => time(),
    'version'    => 'v1.0',
];

function sign(array $params, $signKey){
    unset($params['sign']);
        $params2 = [];
        ksort($params);
        foreach ($params as $k => $v){
            if($v === null || $v === ''){
                continue;
            }
            $params2[] = $k.'='.$v;
        }
        $query_string = implode('&', $params2);
    return (md5($query_string . $signKey));
}
$data['sign'] = sign($data, $signKey);

$cURLConnection = curl_init('https://gateway.api.com/gateway/payment/create');
curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, $data);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$apiResponse = curl_exec($cURLConnection);
curl_close($cURLConnection);

// $apiResponse - available data from the API request
$json = json_decode($apiResponse);
var_dump($json);
