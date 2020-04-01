<?php

namespace app\admin\controller\pay;

use app\common\controller\Backend;
use app\admin\model\Admin;
use think\Db;
/**
 * 
 *
 * @icon fa fa-circle-o
 */
//用户端查账
class Account extends Backend
{
    
    /**
     * Account模型对象
     * @var \app\admin\model\pay\Account
     */
    protected $model = null;
    protected $dataLimitField = 'user_id';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\pay\Account;
        $this->view->assign("typeList", $this->model->getTypeList());
    }
    
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = false;
        $admin = Admin::get($this->auth->id);
        $wk_id = $admin['wk_id'];
        $rule['user_id'] = ['eq',$wk_id];
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax())
        {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField'))
            {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($rule)
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($rule)
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['id','order_id','order_status','unique_order_id','type','order_amount','update_time']);
                
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
    //退款
    public function refund()
    {
        $order_id = request()->param('ids');
        if ($this->request->isPost()) {

            $params = request()->param();


            if ($params) {

                $params = $this->preExcludeFields($params);//过滤
//                $result = false;
                Db::startTrans();
//                $api = ;
                $order = $this->model->where(['id'=>$params['ids']])->find();
//                halt($order['unique_order_id']);
                if($order['order_amount']<$params['refund_amount'] || $order['order_status'] !== 2){
                    $this->error("退款金额过大");
                }
                //调用接口
                $url = "http://" . $_SERVER['HTTP_HOST']."/index.php/api/yeepay/refund";
//                $url = "http://192.168.1.144:1161/api/yeepay/refund";
                $data = [
                    'id' => $order['unique_order_id'],
                    'amount' => $params['refund_amount'],
                ];
//                halt($data);
                $return = json_curl($url,$data);
                $return = json_decode($return,true);
//                halt($return);
                if($return['code'] == 1){
                    $result = $this->model->where(['id'=>$params['ids']])->update(['order_status'=>3,'update_time'=>time()]);
                }else{
                    $result = false;
                }






                if ($result !== false) {
                    $this->success('退款成功','admin/pay/account');
                } else {
                    $this->error($return['msg']);
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('order_id',$order_id);
        return $this->view->fetch();
    }

    //订单状态
    public function order_status()
    {
        $params = request()->param('id');
//halt($params);
        $status = DB::name('wk_order')->where(['unique_order_id'=>$params])->value('order_status');
        $result = ['status' => $status];
        return json($result);

    }

    //收款
    public function paymethod()
    {

        if($this->request->isAjax()){
            $user_id = $_SESSION['think']['admin']['id'];
            $user = DB::name('admin')->find($user_id);
            $params = request()->param();

            $data = [
                'type' => $params['type'],//1支付宝 2微信
//                'type' => 1,//1支付宝 2微信
                'user_id' => $user['wk_id'],
//                'user_id' => $user['wk_id'],
                'amount' => floatval($params['amount']),
//                'amount' => 1,
                'goods_name' => '收款',
                'sn' => $params['sn'],
                'notify' => "http://" . $_SERVER['HTTP_HOST'] . "/api/notify/notify",
//                'notify' => "http://8ce78153.ngrok.io/api/test/notify",
                'goodsDesc' => $params['goodsDesc'],
            ];
//            halt($data);
//            $url = "http://192.168.1.144:1161/api/yeepay/pay ";//存预订单入库
            $url = "http://" . $_SERVER['HTTP_HOST'] . "/api/yeepay/pay";

            $return = json_curl($url,$data);
//halt($return);
            $arr = json_decode($return,true)['data'];//返回结果

            $result = [
                'qrcode' => $this->qrcode($arr['url'])['data'],
                'unique_order_id' => $arr['unique_order_id'],
            ];


//            $result = $this->qrcode($arr['url']);

            return json($result);

        }
        return $this->view->fetch();
    }
    public function qrcode($url)
    {
        vendor('phpqrcode.phpqrcode');
        $object=new \QRcode();
        ob_start();

        $object->png($url,false,3,10,2);
        $imageString = base64_encode(ob_get_contents());
        ob_end_clean();
        $data = array(
            'code'=>200,
            'data'=>$imageString
        );
        return $data;
    }

    public function ce()
    {
        $data = action('api/pay/pay2',['type'=>1,'amount'=>0.01,'user_id'=>2,'goods_name'=>'goods','sn'=>1]);
        halt($data);
    }
//www.ananova.com/news
//www.sickarts.com
}
