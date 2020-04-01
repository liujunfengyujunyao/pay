<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Config;
use think\Db;
use app\admin\model\Admin;
/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend
{

    /**
     * 查看
     */
    public function index()
    {
        $seventtime = \fast\Date::unixtime('day', -6);//七天前的时间戳
        $admin = Admin::get($this->auth->id);
        $group_id = DB::name('auth_group_access')->where("uid",$admin['id'])->value('group_id');
        $wk_id = $admin['wk_id'];
        if($group_id > 2){
            //只能查看自己的数据
            $group_where['user_id'] = ['eq',$wk_id];
        }else{
            $group_where = "1=1";
        }

        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++)//运行7次 求出最近7天的数据
        {
            $day = date("Y-m-d", $seventtime + ($i * 86400));

            $start_time = $seventtime + ($i * 86400);
            $end_time = $seventtime + (($i+1) * 86400);
//            halt([$start_time,$end_time]);
//            $createlist[$day] = mt_rand(20, 200);//订单数
            $createlist[$day] = DB::name('wk_order')->where('add_time','between',[$start_time,$end_time])->where($group_where)->count();//订单数
            $paylist[$day] =  DB::name('wk_order')->where('add_time','between',[$start_time,$end_time])->where('order_status',2)->where($group_where)->count();//成交数量
//            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));//成交数量
        }

//        $hooks = config('addons.hooks');
//        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
//        $addonComposerCfg = ROOT_PATH . '/vendor/karsonzhang/fastadmin-addons/composer.json';
//        Config::parse($addonComposerCfg, "json", "composer");
//        $config = Config::get("composer");
//        $addonVersion = isset($config['version']) ? $config['version'] : __('Unknown');
        $time = strtotime(date("Y-m-d"),time());
        $where1['order_status'] = array('eq',3);
        $where1['add_time'] = array('egt',$time);
        $where2['order_status'] = array('eq',2);
        $where2['add_time'] = array('egt',$time);
        $this->view->assign([
            'totaluser'        => DB::name('admin')->count(),//会员总数
            'totalviews'       => DB::name('wk_order')->where("order_status","3")->where($group_where)->sum('order_amount'),//退款金额
            'totalorder'       => DB::name('wk_order')->where($group_where)->count(),//总订单数
            'totalorderamount' => DB::name('wk_order')->where("order_status","2")->where($group_where)->sum('order_amount'),//实收金额
//            'todayuserlogin'   => DB::name('admin')->where("update_time",">","$time")->count(),//今日登陆
            'todayusersignup'  => DB::name('admin')->where("createtime",">",$time)->count(),//今日注册
            'todayorder'       => DB::name('wk_order')->where("add_time",">",$time)->where($group_where)->count(),//今日订单
            'unsettleorder'    => DB::name('wk_user')->count(),//审批成功
            'sevendnu'         => DB::name('wk_order')->where($where1)->where($group_where)->sum('order_amount'),//今日退款
            'sevendau'         => DB::name('wk_order')->where($where2)->where($group_where)->sum('order_amount'),//今日实收
            'paylist'          => $paylist,//七天的成交数量
            'createlist'       => $createlist,//七天的订单数
            'group_id'         => $group_id,
//            'addonversion'       => $addonVersion,//插件版本
//            'uploadmode'       => $uploadmode//
        ]);

        return $this->view->fetch();
    }

}
