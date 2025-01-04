# Mintlify Starter Kit

Click on `Use this template` to copy the Mintlify starter kit. The starter kit contains examples including

- Guide pages
- Navigation
- Customizations
- API Reference pages
- Use of popular components

### Development

Install the [Mintlify CLI](https://www.npmjs.com/package/mintlify) to preview the documentation changes locally. To install, use the following command

```
npm i -g mintlify
```

Run the following command at the root of your documentation (where mint.json is)

```
mintlify dev
```

### Publishing Changes

Install our Github App to auto propagate changes from your repo to your deployment. Changes will be deployed to production automatically after pushing to the default branch. Find the link to install on your dashboard. 

#### Troubleshooting

- Mintlify dev isn't running - Run `mintlify install` it'll re-install dependencies.
- Page loads as a 404 - Make sure you are running in a folder with `mint.json`


[http://notify.pay247.io/notify/payment/V7PayPayment][34.92.212.58][khzPhd2isfCoIh6f] production.DEBUG: V7PayPayment>代收订单异步回调参数 {"{\"amount\":\"1001000_0000\",\"cid\":\"RCLJ7667\",\"orderContent\":\"\",\"orderName\":\"\",\"originalTran_amt\":\"1001000_0000\",\"payType\":\"19\",\"rockTradeNo\":\"241223234653R0019P0bf3\",\"sign\":\"2596fc0f300530779bdcc2ab0e91fc4b\",\"status\":\"1\",\"sysTime\":\"20241224000425\",\"tradeNo\":\"IN202412232346512283510365\"}":null} [][2024-12-24T00:04:25.551184+08:00][http://notify.pay247.io/notify/payment/V7PayPayment][34.92.212.58][khzPhd2isfCoIh6f] production.DEBUG: 代收处理回调>异常:Undefined array key "status" [] []