<?php

namespace app\admin\controller\pay;

use app\common\controller\Backend;
use think\Db;
/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Merchants extends Backend
{
    
    /**
     * Merchants模型对象
     * @var \app\admin\model\pay\Merchants
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\pay\Merchants;
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
        $status['status'] = ['eq',2];//通过易宝审核
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
                    ->where($status)
                    ->where($where)
                    ->order($sort, $order)
                    ->count();

            $list = $this->model
                    ->where($status)
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            foreach ($list as $row) {
                $row->visible(['uid','name','mobile','email','id_card','type','update_time']);
                
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function detail()
    {
        $this->model = new \app\admin\model\Order;
        if ($this->request->isAjax()){

            $addtabs= request()->param('addtabs');

            $admin_id = substr($addtabs,strripos($addtabs,"=")+1);//截取等号后面的

            $admin = DB::name('admin')->where(['id'=>$admin_id])->find();

            $wk = DB::name('wk_user')->where(['id'=>$admin['wk_id']])->find();

            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $list = $this->model
                ->where(['user_id'=>$wk['id']])
                ->order($sort, $order)
                ->limit($offset,$limit)
                ->select();
            $total = $this->model
                ->where(['user_id'=>$wk['id']])
                ->order($sort, $order)
                ->count();


          foreach ($list as $row) {
            $row->visible(['id','order_id','unique_order_id','user_id','leader_id','type','order_amount','goods_detail','goods_title','user_in','agent_in','platform_in','yeepay_in','order_status','residual_amount','devide_rate','add_time','update_time','sn']);

        }
            $list = collection($list)->toArray();//$list是个对象  转化为数组
//            halt($list);
            $result = array("total" => $total,"rows" => $list);
            return json($result);
        }
        return $this->view->fetch();

    }
}
