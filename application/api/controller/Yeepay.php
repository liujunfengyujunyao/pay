<?php

namespace app\api\controller;

use think\Db;
use app\common\controller\Api;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

class Yeepay extends api{

    protected $noNeedLogin = ['*'];

    public function __construct(){
        parent::__construct();
        import('yeepay.YopClient3', EXTEND_PATH, '.php');

        $this->parentMerchantNo = config('yeepay.parentMerchantNo');
        $this->merchantNo = config('yeepay.merchantNo');
        $this->private_key = config('yeepay.private_key');
        $this->yop_public_key = config('yeepay.yop_public_key');
        $this->merHmacKey = config('yeepay.merHmacKey');
        $this->notifyUrl = 'http://47.104.30.132/callback.php';
    }

    //回调
    public function callback(){
        import('yeepay.Util.YopSignUtils', EXTEND_PATH, '.php');

        $source = $_REQUEST["response"];//加密数据
        $data = \YopSignUtils::decrypt($source,$this->private_key, $this->yop_public_key);

//        $l = 'time:'.date('Y-m-d H:i:s',time()).PHP_EOL.'data:'.$data.PHP_EOL.PHP_EOL;
//        file_put_contents('./callback.log', $l ,FILE_APPEND);
//        $data = json_decode($data,true);
//        if($data['status'] == 'SUCCESS'){
//            $res = Db::name('wk_order')->where(['order_id'=>$data['orderId'],'unique_order_id'=>$data['uniqueOrderNo'],'order_status'=>1])->setField('order_status',2);
//            // if($res !== false){
//            // 	return "SUCCESS";
//            // }
//        }
//        //return 'SUCCESS';
//        echo "SUCCESS";
    }

    public function getHmac($data){
        $hmacstr = hash_hmac('sha256', toString($data), $this->merHmacKey, true);
        $hmac = bin2hex($hmacstr);
        return $hmac;
    }

    //申请支付链接
    public function pay(){
        $type = input('post.type');//1支付宝2微信
        $user_id = input('post.userid')?:1;//wk_user表的id 1为不分账，钱放在收单账户（公司账户）
        $amount = input('post.amount');//订单金额
        $goods_name = input('post.goods_name')?:'goods';//商品名称    
        $sn = input('post.sn');//设备sn
        $notifyUrl = input('post.notify')?:$this->notifyUrl;//回调地址
        $goodsDesc = input('post.goodsDesc')?:'';//商品详情
        $payType = array('1'=>'ALIPAY','2'=>'WECHAT');
        $request = new \YopRequest("OPR:".$this->parentMerchantNo, $this->private_key,$this->yop_public_key,"https://open.yeepay.com/yop-center");
        $yeepay_in = round(bcmul($amount,0.003,10),2);
        $divide_rate = 0;
        $leader = 0;
        if($user_id == 1){
            $request->addParam("fundProcessType", 'REAL_TIME');
            $platform_in = round(bcsub($amount,$yeepay_in,10),2);
            $user_in = 0;
        }else{
            $user = Db::name('wk_user')->where(['id'=>$user_id])->field('user_name,merchant_no,divide_rate,leader_id')->find();
            if(!$user){
                $this->error('用户不存在');
            }else{
                $leader = $user['leader_id'];
                $divide_rate = $user['divide_rate'];
                $platform_in = round(bcsub($user['divide_rate'],0.003,10)*$amount,2);
                $user_in = bcsub($amount,bcadd($yeepay_in,$platform_in,10),2);
                $request->addParam("divideDetail",'[{"ledgerNo":"'.$user['merchant_no'].'","ledgerName":"'.$user['user_name'].'", "amount":"'.$user_in.'"}]');
                $request->addParam("fundProcessType", 'SPLIT_ACCOUNT_IN');
            }
        }
        if($platform_in < 0){
            $this->error('金额有误');
        }
        $data=array();
        $data['parentMerchantNo']=$this->parentMerchantNo;
        $data['merchantNo']=$this->merchantNo;
        $data['orderId']=getOrderId();
        $data['orderAmount']=$amount;
        $data['notifyUrl']=$notifyUrl;

        $goods = array(
            'goodsName'=>$goods_name,
            'goodsDesc'=>$goodsDesc,
        );

        $request->addParam("parentMerchantNo", $data['parentMerchantNo']);
        $request->addParam("merchantNo", $data['merchantNo']);
        $request->addParam("orderId", $data['orderId']);
        $request->addParam("orderAmount", $data['orderAmount']);
        $request->addParam("timeoutExpress", 7200);//过期时间 默认72小时， 最小1秒， 最大5年
        $request->addParam("timeoutExpressType", 'SECOND');//过期时间单位：SECOND("秒"), MINUTE("分"), HOUR("时"), DAY("天")
        $request->addParam("requestDate", getTime());
        $request->addParam("notifyUrl", $notifyUrl);
        $request->addParam("goodsParamExt", json_encode($goods));
        $request->addParam("hmac", $this->getHmac($data));
        $request->addParam("payTool", 'SCCANPAY');
        $request->addParam("payType", $payType[$type]);
        $request->addParam("userIp", $_SERVER['REMOTE_ADDR']);
        $request->addParam("extParamMap", '{"reportFee":"XIANXIA"}');
        $response = \YopClient3::post("/rest/v1.0/nccashierapi/api/orderpay", $request);

        $response = json_decode($response,true);

        $result = $response['result'];
        if($result['code'] == 'CAS00000'){
            $info = array(
                'user_id'=>$user_id,
                'order_id'=>$data['orderId'],
                'unique_order_id'=>$result['uniqueOrderNo'],
                'type'=>$type,
                'order_amount'=>$data['orderAmount'],
                'goods_title'=>$goods['goodsName'],
                'goods_detail'=>$goods['goodsDesc'],
                'residual_amount'=>$data['orderAmount'],
                'user_in'=>$user_in?:0,
                'platform_in'=>$platform_in,
                'yeepay_in'=>$yeepay_in,
                'leader_id'=>$leader,
                'divide_rate'=>$divide_rate,
                'add_time'=>time(),
                'update_time'=>time(),
                'sn'=>$sn
            );
            $WkOrder = model('WkOrder');
            $res = $WkOrder->save($info);
            if($res != false){
                $this->success('下单成功',['url'=>$result['resultData'],'unique_order_id'=>$result['uniqueOrderNo']]);
            }else{
                $this->error('写入错误');
            }
        }else{
            //var_dump($response);
            $this->error('下单失败');
        }
    }

    //支行信息查询
    public function bank_branch(){
        if($this->request->isPost()){
            $bank = input('post.bank');
            $province = input('post.province');
            $city = input('post.city');
            $request = new \YopRequest("OPR:".$this->parentMerchantNo, $this->private_key, "https://openapi.yeepay.com/yop-center",$this->yop_public_key);
            $request->addParam("requestNo", getOrderId());
            $request->addParam("headBankCode", $bank);
            $request->addParam("provinceCode", $province);
            $request->addParam("cityCode", $city);
            $response = \YopRsaClient::post("/rest/v1.0/sys/merchant/bankbranchinfo", $request);
            $response = json_decode($response,true);
            $result = $response['result'];
            if(!array_key_exists('result',$response)){
                $this->error($response['message'],$response);
            }
            if($result['returnCode'] != 'REG00000'){
                $this->error($result['returnMsg']);
            }else{
                $this->success($result['returnMsg'],$result['branchBankInfo']);
            }

            // return json((array)$response->result->branchBankInfo);
        }
    }

    //退款信息查询
    public function refund_query(){
        $id = input('get.id');//退款唯一订单号unique_refund_id
        $refund = Db::name('wk_refund')->where(['unique_refund_id'=>$id])->find();
        $data=array();
        $data['parentMerchantNo']=$this->parentMerchantNo;
        $data['merchantNo']=$this->merchantNo;
        $data['refundRequestId'] = $refund['refund_id'];
        $data['orderId']=$refund['order_id'];
        $data['uniqueRefundNo']=$id;
        //var_dump($data);
        $request = new \YopRequest("OPR:".$this->parentMerchantNo, $this->private_key,"https://openapi.yeepay.com/yop-center",$this->yop_public_key);
        $request->addParam("parentMerchantNo", $this->parentMerchantNo);
        $request->addParam("merchantNo", $this->merchantNo);
        $request->addParam("orderId", $data['orderId']);
        $request->addParam("uniqueRefundNo", $data['uniqueRefundNo']);
        $request->addParam("refundRequestId", $data['refundRequestId']);
        $request->addParam("hmac",$this->getHmac($data));
        //print_r($request);
        $response = \YopClient3::post("/rest/v1.0/sys/trade/refundquery", $request);
        $response = json_decode($response,true);
        $result = $response['result'];
        var_dump($result);

    }

    //订单信息查询
    public function order_query(){
        $id = input('get.id');//唯一订单号unique_order_id
        $order_id = Db::name('wk_order')->where(['unique_order_id'=>$id])->getField('order_id');
        $data=array();
        $data['parentMerchantNo']=$this->parentMerchantNo;
        $data['merchantNo']=$this->merchantNo;
        $data['orderId']=$order_id;
        $data['uniqueOrderNo']=$id;

        $request = new \YopRequest("OPR:".$this->parentMerchantNo, $this->private_key,$this->yop_public_key,"https://openapi.yeepay.com/yop-center");
        $request->addParam("parentMerchantNo", $this->parentMerchantNo);
        $request->addParam("merchantNo", $this->merchantNo);
        $request->addParam("orderId", $data['orderId']);
        $request->addParam("uniqueOrderNo", $data['uniqueOrderNo']);
        $request->addParam("hmac",$this->getHmac($data));
        //print_r($request);
        $response = \YopClient3::post("/rest/v1.0/sys/trade/orderquery", $request);
        var_dump($response);
    }


    //商户信息上传至yeepay审核
    public function reginfoadd(){
        if($this->request->isPost()) {
            $params = request()->param();
            $notifyUrl = $params['notifyUrl']?:$this->notifyUrl;

            $request = new \YopRequest("OPR:" . $this->parentMerchantNo, $this->private_key, $this->yop_public_key, "https://openapi.yeepay.com/yop-center");
            $request->addParam("parentMerchantNo", $this->parentMerchantNo);
            $request->addParam("requestNo", $params['requestNo']);
            $request->addParam("legalName", $params['legalName']);
            $request->addParam("legalIdCard", $params['legalIdCard']);
            $request->addParam("merProvince", $params['merProvince']);
            $request->addParam("merCity", $params['merCity']);
            $request->addParam("merDistrict", $params['merDistrict']);
            $request->addParam("merAddress", $params['merAddress']);
            $request->addParam("notifyUrl", $notifyUrl);
            $request->addParam("merAuthorizeType", 'WEB_AUTHORIZE');
            $request->addParam("businessFunction", '{}');
            $request->addParam("cardNo", $params['cardNo']);
            $request->addParam("headBankCode", $params['headBankCode']);
            $request->addParam("bankCode", $params['bankCode']);
            $request->addParam("bankProvince", $params['bankProvince']);
            $request->addParam("bankCity", $params['bankCity']);

            $img = array(
                array('quaType'=>'IDCARD_FRONT','quaUrl'=>$params['IDCARD_FRONT']),
                array('quaType'=>'IDCARD_BACK','quaUrl'=>$params['IDCARD_BACK']),
            );

            switch($params['type']){
                case '1'://个人
                    $url = "/rest/v1.0/sys/merchant/personreginfoadd";
                    $request->addParam("merLegalPhone", $params['merLegalPhone']);
                    $request->addParam("merLegalEmail", $params['merLegalEmail']);
                    $request->addParam("productInfo", '{"payProductMap":{"USER_SCAN_PAY": {"dsPayBankMap": {"WECHAT_ATIVE_SCAN_OFFLINE": {"rateType": "PERCENTAGE","rate": "0.5"},"ALIPAY_OFFLINE": {"rateType": "PERCENTAGE","rate": "0.5"}}}},"payScenarioMap": {"FACE_TO_FACE_ACCESS": { }}}');
                    array_push($img,array('quaType'=>'SETTLE_BANKCARD','quaUrl'=>$params['SETTLE_BANKCARD']));
                    array_push($img,array('quaType'=>'HAND_IDCARD','quaUrl'=>$params['HAND_IDCARD']));
                    array_push($img,array('quaType'=>'HAND_BANKCARD','quaUrl'=>$params['HAND_BANKCARD']));
                    break;
                case '2'://个体
                    $url = "/rest/v1.0/sys/merchant/individualreginfoadd";
                    $request->addParam("merLegalPhone", $params['merLegalPhone']);
                    $request->addParam("merLegalEmail", $params['merLegalEmail']);
                    $request->addParam("merFullName", $params['merFullName']);
                    $request->addParam("merShortName", $params['merShortName']);
                    $request->addParam("merCertNo", $params['merCertNo']);
                    $request->addParam("merLevel1No", $params['merLevel1No']);
                    $request->addParam("merLevel2No", $params['merLevel2No']);
                    $request->addParam("productInfo", '{"payProductMap":{"USER_SCAN_PAY": {"dsPayBankMap": {"WECHAT_ATIVE_SCAN_OFFLINE": {"rateType": "PERCENTAGE","rate": "0.3"},"ALIPAY_OFFLINE": {"rateType": "PERCENTAGE","rate": "0.3"}}}},"payScenarioMap": {"OFFLINE_ACCESS": { }}}');
                    array_push($img,array('quaType'=>'CORP_CODE','quaUrl'=>$params['CORP_CODE']));
                    array_push($img,array('quaType'=>'SETTLE_BANKCARD','quaUrl'=>$params['SETTLE_BANKCARD']));
                    array_push($img,array('quaType'=>'BUSINESS_PLACE','quaUrl'=>$params['BUSINESS_PLACE']));
                    array_push($img,array('quaType'=>'CASHIER_SCENE','quaUrl'=>$params['CASHIER_SCENE']));
                    break;

                case '3'://企业
                    $url = "/rest/v1.0/sys/merchant/enterprisereginfoadd";
                    $request->addParam("merContactName", $params['merContactName']);
                    $request->addParam("merContactPhone", $params['merLegalPhone']);
                    $request->addParam("merContactEmail", $params['merLegalEmail']);
                    $request->addParam("merFullName", $params['merFullName']);
                    $request->addParam("merShortName", $params['merShortName']);
                    $request->addParam("merCertType", $params['merCertType']);
                    $request->addParam("merCertNo", $params['merCertNo']);
                    $request->addParam("merLevel1No", $params['merLevel1No']);
                    $request->addParam("merLevel2No", $params['merLevel2No']);
                    $request->addParam("accountLicense", $params['accountLicense']);
                    $request->addParam("productInfo", '{"payProductMap":{"USER_SCAN_PAY": {"dsPayBankMap": {"WECHAT_ATIVE_SCAN_OFFLINE": {"rateType": "PERCENTAGE","rate": "0.3"},"ALIPAY_OFFLINE": {"rateType": "PERCENTAGE","rate": "0.3"}}}},"payScenarioMap": {"OFFLINE_ACCESS": { }}}');
                    array_push($img,array('quaType'=>'CORP_CODE','quaUrl'=>$params['CORP_CODE']));
                    array_push($img,array('quaType'=>'UNI_CREDIT_CODE','quaUrl'=>$params['UNI_CREDIT_CODE']));
                    array_push($img,array('quaType'=>'OP_BANK_CODE','quaUrl'=>$params['OP_BANK_CODE']));
                    array_push($img,array('quaType'=>'BUSINESS_PLACE','quaUrl'=>$params['BUSINESS_PLACE']));
                    array_push($img,array('quaType'=>'CASHIER_SCENE','quaUrl'=>$params['CASHIER_SCENE']));
                    break;
            }
            halt(json_encode($img));
            $request->addParam("fileInfo", json_encode($img));
            $response = \YopRsaClient::post($url, $request);

            $response = json_decode($response,true);
            //halt($response);
            if(!array_key_exists('result',$response)){
                $this->error($response['message'],$response);
            }
            $result = $response['result'];
            if($result['returnCode'] == 'REG00000'){
                $this->success($result['returnMsg'],$result);
            }else{
                $this->error($result['returnMsg'],$result);
            }
        }
    }


    //本地图片上传至yeepay
    public function send_upload(){

        $request = new \YopRequest("OPR:".$this->parentMerchantNo, $this->private_key,$this->yop_public_key);
        $request->addFile("merQual", request()->param('path'));//图片本地路径

        //提交Post请求
        $response = \YopRsaClient::upload("/yos/v1.0/sys/merchant/qual/upload", $request);

        $response = json_decode($response,true);
        if(!array_key_exists('result',$response)){
            $this->error($response['message'],$response);
        }

        $result = $response['result'];
        if($result['returnCode'] == 'REG00000'){
            $this->success($result['returnMsg'],$result['merQualUrl']);
        }else{
            $this->error($result['returnMsg']);
        }

    }


    //退款请求
    public function refund(){
        $order_id = input('post.id');//唯一订单号，unique_order_id
        $refund_amount = input('post.amount');//退款金额
        $notifyUrl = input('post.notifyUrl')?:$this->notifyUrl;
        $order = Db::name('wk_order')->where(['unique_order_id'=>$order_id])->find();

        if(!$order){
            $this->error('订单不存在');
        }else{
            if($order['order_status'] == 1 || $order['order_status'] == 3 || time()-$order['add_time'] > 3600){
                $this->error('该订单未支付或已退款或已超时');
            }else{
                $user = Db::name('wk_user')->where(['id'=>$order['user_id']])->field('user_name,merchant_no')->find();
                $data=array();
                $data['parentMerchantNo']=$this->parentMerchantNo;
                $data['merchantNo']=$this->merchantNo;
                $data['orderId']=$order['order_id'];//'DS190402_10511926';
                $data['uniqueOrderNo']=$order['unique_order_id'];//'1001201904020000000576339754';
                $data['refundRequestId']=getOrderId();
                $data['refundAmount']=$refund_amount?:$order['order_amount'];

                $request = new \YopRequest("OPR:".$this->parentMerchantNo, $this->private_key,"https://openapi.yeepay.com/yop-center",$this->yop_public_key);

                if($order['user_id'] != 1){//分账退款
                    $user_refund = round(bcmul(bcdiv($data['refundAmount'],$order['residual_amount'],10),$order['user_in'],10),2);
                    $platform_refund = bcsub($data['refundAmount'],$user_refund,2);

                    $accountDivided = array();
                    $user_a = array(
                        'ledgerNo'=>$user['merchant_no'],
                        'ledgerName'=>$user['user_name'],
                        'amount'=>$user_refund
                    );
                    if($platform_refund != 0){
                        $platform_a = array(
                            'ledgerNo'=>'10027419272',
                            'ledgerName'=>'北京快乐平方',
                            'amount'=>$platform_refund
                        );
                        array_push($accountDivided,$platform_a);
                    }
                    array_push($accountDivided,$user_a);
                    $request->addParam("accountDivided", json_encode($accountDivided,JSON_UNESCAPED_UNICODE));
                }else{//仅平台退款
                    $user_refund = 0;
                    $platform_refund = $data['refundAmount'];
                }

                $request->addParam("parentMerchantNo", $this->parentMerchantNo);
                $request->addParam("merchantNo", $this->merchantNo);
                $request->addParam("orderId", $data['orderId']);
                $request->addParam("uniqueOrderNo", $data['uniqueOrderNo']);
                $request->addParam("refundRequestId", $data['refundRequestId']);
                $request->addParam("refundAmount", $data['refundAmount']);

                //$request->addParam("description", $_REQUEST['description']);
                //$request->addParam("memo", $_REQUEST['memo']);
                $request->addParam("notifyUrl", $notifyUrl);
                $request->addParam("hmac",$this->getHmac($data));
                //halt($request);
                $response = \YopClient3::post("/rest/v1.0/sys/trade/refund", $request);
                $response = json_decode($response,true);
                if(!array_key_exists('result',$response)){
                    $this->error($response['message'],$response);
                }
                $result = $response['result'];
                if($result['code'] == 'OPR00000'){
                    $info = array(
                        'refund_id'=>$data['refundRequestId'],
                        'order_id'=>$data['orderId'],
                        'unique_order_id'=>$data['uniqueOrderNo'],
                        'unique_refund_id'=>$result['uniqueRefundNo'],
                        'user_id'=>$order['user_id'],
                        'refund_amount'=>$result['refundAmount'],
                        'residual_amount'=>$result['residualAmount'],
                        'user_refund'=>$user_refund,
                        'platform_refund'=>$platform_refund,
                        'status'=>$result['status'],
                        'add_time'=>time()
                    );
                    $wk_refund = model('WkRefund');
                    $a = $wk_refund->save($info);
                    $infoo = array(
                        'user_in'=>bcsub($order['user_in'],$user_refund,2),
                        'platform_in'=>bcsub($order['platform_in'],$platform_refund,2),
                        'order_status'=>3,
                        'residual_amount'=>$result['residualAmount'],
                        'update_time'=>time()
                    );
                    $wk_order = model('WkOrder');
                    $b = $wk_order->where(['unique_order_id'=>$order_id])->update($infoo);
                    if($a != false && $b != false){
                        $this->success('退款成功');
                    }
                }else{
                    $this->error($result['message']);
                }
            }
        }
    }



}