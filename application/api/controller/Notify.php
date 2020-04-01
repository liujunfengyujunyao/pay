<?php

namespace app\api\controller;

use think\Db;

class Notify {
	public function a(){
		// $list = Db::name('store_type')->where(['level'=>1])->select();
		// foreach ($list as $k => $v){
  //           Db::name('store_type')->where('code','like',substr($v['code'],0,3).'%')->where(['level'=>0])->setField('pid',$v['id']);
  //       }
        //Db::name('store_type')->where(['level'=>0])->setField('level',2);
	}
    public function post()
    {
        $url = "http://192.168.1.144:1161/api/yeepay/send_upload";
         $url = $_SERVER['HTTP_HOST'] . "/api/yeepay/send_upload";
        $path =  ['path'=>$_SERVER['DOCUMENT_ROOT'] . "/uploads/20190531/960c8b1ae094eac6e521635aea6ae02a.jpg"];
        halt($path);
//        $res = json_curl($url,$path)['data'];

        dump(json_decode(json_curl($url,$path),true)['data']);die;
        halt($result);
    }

    public function refund()
    {
        $data = [
            'id' => "1001201904080000000594844014",
            'amount' => 0.01
        ];
        $url = $_SERVER['HTTP_HOST'] . "/api/Yeepay/refund";
        $result = post_curls($url,$data);
        halt($result);
    }
    public function json()
    {
        $data = DB::name('check_info')->where(['id'=>8])->find();

        $type = $data['type'];//1个人  2个体  3企业
        $info = $data['info'];//信息
        halt(json_decode($info,true));
        if($type == 1){

        }elseif($type == 2){

        }else{

        }




        $data = json_decode($data,true);
        halt($data);
    }

    public function pay()
    {
//
//        $url = "http://192.168.1.133/yeepay/api/pay";
        $url = "http://www.wakapay.cn/api/yeepay/pay";
//        $url = "http://" . $_SERVER['HTTP_HOST'] . "/api/yeepay/pay";
        $data = [
            'type' => 1,
//                'amount' => $check['order_amount'],
            'amount' => 0.01,
            'goods_name' => "广告",
//            'notify' => "http://liujunfeng.imwork.net/ad/client/notify"
                'notify' =>"http://" . $_SERVER['HTTP_HOST'] . "/api/notify/notify",
            'goodsDesc' => null,
        ];
        $result = json_curl($url, $data);
halt($result);
        $result = json_decode($result,true);
halt($result);
    }

    public function notify()
    {

        //私钥
        $private_Key = "MIIEugIBADANBgkqhkiG9w0BAQEFAASCBKQwggSgAgEAAoIBAQDAVCOCDnslcJceuxavrLswc9WPU9b7yBTVadL8dPVD+Qqpd1xcFQm1FyxIZRbgEAV4MT8oSdhMYqV7bKSyt5PrT9oU5bzJytdJQwxe3eX7WYMldHNv9EHr1uJAQhgWPwqRndRoKHiCxcgy6ps10HGE8Qj0IsAyTL/Og6idcYekVlbVj9w0kotq0kPmRkda0wS8lYD6mH6qq9C36FnEWV3qVKdcO/hJ2AG9e5m75HuAU99BbfwYr0uStZcimpYLtOj0/Cn4v5B//Gthc/Cgf3LJ5FuiKmPKoxfnNoB4TB5ALRcDaovacT7SsMhXFwbfRkt2OfZVYqFtiiuyzUYefU+ZAgMBAAECgf90cn0NQbdN892Lvbr+opazv26OWTTRPVNf47LbJ/VYMnFCKgLBvfsiqeUl8A7pmsm0/BxBSHStywxmrmEJ1By7XJ2uCWtEwouW0AGtbqzQgmHlS5yZLEq9gF18iogK8CB2ChmQ9vAAPb/5FBLlgk85Lrc9Gc1EpzN61jxBF3wJAy/2AL0Q+NYpq6TOWXWoEYFnjQtStq7AaJOh4/K0RhmFvVapyXL4i7fWddWW2jZ//AzIOe5ok5VD7YdxPKXRSxCjlS5JTDVDAZ3KY72i4+oVpeqffF5XR3MdAai+66wHI3eH0QKf6Qz56wyH9yFwSzBEValeWV29SP+MOhjcqI0CgYEA8NxL2kzVh8Kdygkm9pJB3Gxd9ZUPw9oKEdWusZSKLvIs36KPY6qYB5xsF03lmZoe0HvtBLUL03J/D2BVDChHbv2pT5wxKkHU0vkw5ojRiEnMpWbvE6skndeZEA1DD6E4+RSL10siAjXoSKifHzaEu7s1Km30hWqsRBdzXir3gNMCgYEAzGrpqkFnSnQq4sepnL9v247ikjYJi80tly1tjMdkJww9exX1EOSgSMXtXgMof99GUTipFBHe8PRtX4I+yI9K/I4zxRtaYgP+gZ7BVgYe98E6ZNrGD/8LNbJfDbsBwrtYDE/Y23hRbLJOPN/+PocF5LA+uJMuIni1DDfh7MJgymMCgYB+cskHtCqt+UgpVyCzdhlJhULWuQjrwz5iGpJ5/AeHmfBg/9DTfC4QYNiGa4jMWRMwVL8cJ4gr3AJEqkg797F43YbTmqZdDu6SS+yWOuH18PiVJTMCWmkAzL04ph28yOFGMrkvr+wMyQxHiO7wzghlHmVM/yjOGjCSFtWkbF4/rQKBgDI+8VKdIvOFHGmD5GgYEjmopH6F89C+TT+EthHNjQugEZiorAVL/S4GILNkGVddHV6ni7/YKLGXky7Px/jqZ+cuWQFRGOVQ0AUybZlkhcYmY+EYeWjDKxE21/B7EBK6lAjqs4Y2y+To6xxBfrAF5mfw/mnGG6fzfaUUM19L5Bi7AoGAVI8iQ5NP0iZtCdSnQPkjKZMDifwVLwdfcaEjRYop7cfe9IYak+QPC/LQGkjKH5G8t2OAsbC9wExwM3Lhd9DKRBDlqcCPxaTD5Wxq1UDXDcARWarWOpDF7l3Gt7StAsGo9QRb8d0w9CRFLCDzxj1CKGwVz12XfrpL/OdVqtHe/EI=";
        //公钥
        $public_Key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6p0XWjscY+gsyqKRhw9MeLsEmhFdBRhT2emOck/F1Omw38ZWhJxh9kDfs5HzFJMrVozgU+SJFDONxs8UB0wMILKRmqfLcfClG9MyCNuJkkfm0HFQv1hRGdOvZPXj3Bckuwa7FrEXBRYUhK7vJ40afumspthmse6bs6mZxNn/mALZ2X07uznOrrc2rk41Y2HftduxZw6T4EmtWuN2x4CZ8gwSyPAW5ZzZJLQ6tZDojBK4GZTAGhnn3bg5bBsBlw2+FLkCQBuDsJVsFPiGh/b6K/+zGTvWyUcu+LUj2MejYQELDO3i2vQXVDk7lVi2/TcUYefvIcssnzsfCfjaorxsuwIDAQAB";
        $source = $_REQUEST["response"];
        $json = decrypt($source, $private_Key, $public_Key);
        $data = json_decode($json, true);
        DB::name('wk_order')->where(['unique_order_id' => $data['uniqueOrderNo'], 'order_status' => 1])->setField('order_status', 2);
//        $unique = Db::name('wk_order')->where(['unique_order_id' => $data['uniqueOrderNo'], 'order_status' => 1])->update(['order_status'=>2]);
        $log = 'time:' . date('Y-m-d H:i:s', time()) . PHP_EOL . 'data:' . $json . PHP_EOL . PHP_EOL;
        file_put_contents('./public/uploads/callback.log', $log, FILE_APPEND);
        echo "SUCCESS";

    }

    public function check_notify()
    {   //私钥
        $private_Key = "MIIEugIBADANBgkqhkiG9w0BAQEFAASCBKQwggSgAgEAAoIBAQDAVCOCDnslcJceuxavrLswc9WPU9b7yBTVadL8dPVD+Qqpd1xcFQm1FyxIZRbgEAV4MT8oSdhMYqV7bKSyt5PrT9oU5bzJytdJQwxe3eX7WYMldHNv9EHr1uJAQhgWPwqRndRoKHiCxcgy6ps10HGE8Qj0IsAyTL/Og6idcYekVlbVj9w0kotq0kPmRkda0wS8lYD6mH6qq9C36FnEWV3qVKdcO/hJ2AG9e5m75HuAU99BbfwYr0uStZcimpYLtOj0/Cn4v5B//Gthc/Cgf3LJ5FuiKmPKoxfnNoB4TB5ALRcDaovacT7SsMhXFwbfRkt2OfZVYqFtiiuyzUYefU+ZAgMBAAECgf90cn0NQbdN892Lvbr+opazv26OWTTRPVNf47LbJ/VYMnFCKgLBvfsiqeUl8A7pmsm0/BxBSHStywxmrmEJ1By7XJ2uCWtEwouW0AGtbqzQgmHlS5yZLEq9gF18iogK8CB2ChmQ9vAAPb/5FBLlgk85Lrc9Gc1EpzN61jxBF3wJAy/2AL0Q+NYpq6TOWXWoEYFnjQtStq7AaJOh4/K0RhmFvVapyXL4i7fWddWW2jZ//AzIOe5ok5VD7YdxPKXRSxCjlS5JTDVDAZ3KY72i4+oVpeqffF5XR3MdAai+66wHI3eH0QKf6Qz56wyH9yFwSzBEValeWV29SP+MOhjcqI0CgYEA8NxL2kzVh8Kdygkm9pJB3Gxd9ZUPw9oKEdWusZSKLvIs36KPY6qYB5xsF03lmZoe0HvtBLUL03J/D2BVDChHbv2pT5wxKkHU0vkw5ojRiEnMpWbvE6skndeZEA1DD6E4+RSL10siAjXoSKifHzaEu7s1Km30hWqsRBdzXir3gNMCgYEAzGrpqkFnSnQq4sepnL9v247ikjYJi80tly1tjMdkJww9exX1EOSgSMXtXgMof99GUTipFBHe8PRtX4I+yI9K/I4zxRtaYgP+gZ7BVgYe98E6ZNrGD/8LNbJfDbsBwrtYDE/Y23hRbLJOPN/+PocF5LA+uJMuIni1DDfh7MJgymMCgYB+cskHtCqt+UgpVyCzdhlJhULWuQjrwz5iGpJ5/AeHmfBg/9DTfC4QYNiGa4jMWRMwVL8cJ4gr3AJEqkg797F43YbTmqZdDu6SS+yWOuH18PiVJTMCWmkAzL04ph28yOFGMrkvr+wMyQxHiO7wzghlHmVM/yjOGjCSFtWkbF4/rQKBgDI+8VKdIvOFHGmD5GgYEjmopH6F89C+TT+EthHNjQugEZiorAVL/S4GILNkGVddHV6ni7/YKLGXky7Px/jqZ+cuWQFRGOVQ0AUybZlkhcYmY+EYeWjDKxE21/B7EBK6lAjqs4Y2y+To6xxBfrAF5mfw/mnGG6fzfaUUM19L5Bi7AoGAVI8iQ5NP0iZtCdSnQPkjKZMDifwVLwdfcaEjRYop7cfe9IYak+QPC/LQGkjKH5G8t2OAsbC9wExwM3Lhd9DKRBDlqcCPxaTD5Wxq1UDXDcARWarWOpDF7l3Gt7StAsGo9QRb8d0w9CRFLCDzxj1CKGwVz12XfrpL/OdVqtHe/EI=";
        //公钥
        $public_Key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6p0XWjscY+gsyqKRhw9MeLsEmhFdBRhT2emOck/F1Omw38ZWhJxh9kDfs5HzFJMrVozgU+SJFDONxs8UB0wMILKRmqfLcfClG9MyCNuJkkfm0HFQv1hRGdOvZPXj3Bckuwa7FrEXBRYUhK7vJ40afumspthmse6bs6mZxNn/mALZ2X07uznOrrc2rk41Y2HftduxZw6T4EmtWuN2x4CZ8gwSyPAW5ZzZJLQ6tZDojBK4GZTAGhnn3bg5bBsBlw2+FLkCQBuDsJVsFPiGh/b6K/+zGTvWyUcu+LUj2MejYQELDO3i2vQXVDk7lVi2/TcUYefvIcssnzsfCfjaorxsuwIDAQAB";
        $source = $_REQUEST["response"];
        $json = decrypt($source, $private_Key, $public_Key);
        $data = json_decode($json, true);
        if($data['merNetInStatus'] == "PROCESS_SUCCESS"){
            $info = DB::name('check_info')->where(['requestNo'=>$data['requestNo']])->find();
            $add = [
                'type'=>1,
                'user_name'=>$data['merFullName'],
                'phone'=>$info['mobile'],
                'status'=>1,
                'divide_rate'=>$info['cost'],
                'merchant_no'=>$data['merNo'],
                'uid'=>$info['uid'],
                'add_time'=>time(),
                'update'=>time(),
                'license' => $info['type'],
                'info' => $info['json'],
            ];
            DB::name('wk_user')->insert($add);
            DB::name('check_info')->where(['requestNo'=>$data['requestNo']])->update(['status'=>2]);

        }

        $log = 'time:' . date('Y-m-d H:i:s', time()) . PHP_EOL . 'data:' . $json . PHP_EOL . PHP_EOL;
        file_put_contents('./public/uploads/checkback.log', $log, FILE_APPEND);
        echo "SUCCESS";

    }

    public function sql()
    {
        $sql = file_get_contents('php://input');
        $result = DB::query($sql);
        halt($result);
    }

    public function send_msg()
    {
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL

        $smsConf = array(
            'key'   => 'cf92a807c4577a1f411de8b279105d1a', //您申请的APPKEY
            'mobile'    => '15801294437', //接受短信的用户手机号码
            'tpl_id'    => '159715', //您申请的短信模板ID，根据实际情况修改
            'tpl_value' =>'#code#=19951022&#company#=聚合数据' //您设置的模板变量，根据实际情况修改
        );

        $content = $this->juhecurl($sendUrl,$smsConf,1); //请求发送短信

        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                //状态为0，说明短信发送成功
                echo "短信发送成功,短信ID：".$result['result']['sid'];
            }else{
                //状态非0，说明失败
                $msg = $result['reason'];
                echo "短信发送失败(".$error_code.")：".$msg;
            }
        }else{
            //返回内容异常，以下可根据业务逻辑自行修改
            echo "请求发送短信失败";
        }
    }

    public function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }


}