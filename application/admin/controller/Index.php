<?php

namespace app\admin\controller;

use app\admin\model\AdminLog;
use app\admin\model\CheckInfo;
use app\admin\model\Admin;
use app\common\controller\Backend;
use think\Config;
use think\Hook;
use think\Validate;
use app\common\library\Sms;
use fast\Random;
use think\Db;


/**
 * 后台首页
 * @internal
 */
class Index extends Backend
{

    protected $noNeedLogin = ['login', 'register'];
    protected $noNeedRight = ['index', 'logout' , 'type' , 'info'];
    protected $layout = '';
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('admin');
    }

    /**
     * 后台首页
     */
    public function index()
    {
        //左侧菜单
        list($menulist, $navlist, $fixedmenu, $referermenu) = $this->auth->getSidebar([
            'dashboard' => 'hot',
            'addon'     => ['new', 'red', 'badge'],
            'auth/rule' => __('Menu'),
            'general'   => ['new', 'purple'],
        ], $this->view->site['fixedpage']);
        $action = $this->request->request('action');
        if ($this->request->isPost()) {
            if ($action == 'refreshmenu') {
                $this->success('', null, ['menulist' => $menulist, 'navlist' => $navlist]);
            }
        }
        $this->view->assign('menulist', $menulist);
        $this->view->assign('navlist', $navlist);
        $this->view->assign('fixedmenu', $fixedmenu);
        $this->view->assign('referermenu', $referermenu);
        $this->view->assign('title', __('Home'));
        return $this->view->fetch();
    }

    /**
     * 管理员登录
     */
    public function login()
    {
        $url = $this->request->get('url', 'index/type');
        if ($this->auth->isLogin()) {
            $this->success(__("You've logged in, do not login again"), $url);
        }
        if ($this->request->isPost()) {
            $username = $this->request->post('username');
            $password = $this->request->post('password');
            $keeplogin = $this->request->post('keeplogin');
            $token = $this->request->post('__token__');
            $rule = [
                'username'  => 'require|length:3,30',
                'password'  => 'require|length:3,30',
                '__token__' => 'token',
            ];
            $data = [
                'username'  => $username,
                'password'  => $password,
                '__token__' => $token,
            ];
            if (Config::get('fastadmin.login_captcha')) {
                $rule['captcha'] = 'require|captcha';
                $data['captcha'] = $this->request->post('captcha');
            }
            $validate = new Validate($rule, [], ['username' => __('Username'), 'password' => __('Password'), 'captcha' => __('Captcha')]);
            $result = $validate->check($data);
            if (!$result) {
                $this->error($validate->getError(), $url, ['token' => $this->request->token()]);
            }
            AdminLog::setTitle(__('Login'));
            $result = $this->auth->login($username, $password, $keeplogin ? 86400 : 0);
            if ($result === true) {
                Hook::listen("admin_login_after", $this->request);
                $admin = session('admin');
                $group_id = DB::name('auth_group_access')->where(['uid'=>$admin['id']])->value('group_id');
                if($admin['check_status'] == 4 || $group_id < 10){//审核已通过用户，跳转功能页
                    $url = 'index/index';
                }
                $this->success(__('Login successful'), $url, ['url' => $url, 'id' => $this->auth->id, 'username' => $username, 'avatar' => $this->auth->avatar]);
            } else {
                $msg = $this->auth->getError();
                $msg = $msg ? $msg : __('Username or password is incorrect');
                $this->error($msg, $url, ['token' => $this->request->token()]);
            }
        }

        // 根据客户端的cookie,判断是否可以自动登录
        if ($this->auth->autologin()) {
            $this->redirect($url);
        }
        $background = Config::get('fastadmin.login_background');
        $background = stripos($background, 'http') === 0 ? $background : config('site.cdnurl') . $background;
        $this->view->assign('background', $background);
        $this->view->assign('title', __('Login'));
        Hook::listen("admin_login_init", $this->request);
        return $this->view->fetch();
    }

    /**
     * 注销登录
     */
    public function logout()
    {
        $this->auth->logout();
        Hook::listen("admin_logout_after", $this->request);
        $this->success(__('Logout successful'), 'index/login');
    }


    //注册
    public  function register(){

        if ($this->request->isPost())
        {
            $params = $this->request->post();

            if ($params)
            {
                dump($params);
                $res = Sms::check($params['mobile'], $params['captcha1'], 'register');
                halt($res);
                if(!$res){
                    $this->error('验证码错误');
                }
                $data['salt'] = Random::alnum();
                $data['password'] = md5(md5($params['password']) . $data['salt']);
                $data['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。
                $data['username'] = $params['mobile'];

                $result = $this->model->validate('Admin.add')->save($data);
                //halt($result);
                if ($result === false)
                {
                    $this->error($this->model->getError());
                }
                // $group = $this->request->post("group/a");

                // //过滤不允许的组别,避免越权
                // $group = array_intersect($this->childrenGroupIds, $group);
                // $dataset = [];
                // foreach ($group as $value)
                // {
                //     $dataset[] = ['uid' => $this->model->id, 'group_id' => $value];
                // }
                model('AuthGroupAccess')->save(['uid' => $this->model->id, 'group_id' => 10]);
                $this->success();
            }
            $this->error();
        }
        return $this->view->fetch();

    }
    //注册商户类型选择
    public  function type(){
        $admin = session('admin');
        $admin = Admin::getById($admin['id']);

        $per_url = "";
        $gt_url = "";
        $com_url = "";
        switch ($admin->check_status){
            case '1'://初次提交
                $msg = '请根据您的个人情况选择一种类型进行信息补全';
                $per_url = "/admin/index/info?type=1";
                $gt_url = "/admin/index/info?type=2";
                $com_url = "/admin/index/info?type=3";
                break;
            case '2'://审核中
                $msg = '您提交的信息正在审核中，请耐心等待...';
                break;
            case '3'://审核失败
                $msg = '您提交的信息有误，请修改后再次提交';
                $per_url = "/admin/index/info?type=1";
                $gt_url = "/admin/index/info?type=2";
                $com_url = "/admin/index/info?type=3";
                break;
            case '4'://审核成功
                $this->success('您已通过审核，正在跳转','index/index');
                break;
            case '5';
                $msg = '您的提交已被驳回，无法再次提交';
                break;
        }
        $this->assign('msg',$msg);
        $this->assign('per',$per_url);
        $this->assign('gt',$gt_url);
        $this->assign('com',$com_url);
        return $this->view->fetch();
    }

    public function info(){
        $admin = session('admin');
        $admin = Admin::getById($admin['id']);
        if($this->request->isPost()){     
            if($admin->check_status == 1 || $admin->check_status == 3){
                $params = input('post.');

                $data = array();
                $data['uid'] = $admin['id'];
                $data['name'] = $params['legalName'];
                $data['mobile'] = $params['merLegalPhone'];
                $data['email'] = $params['merLegalEmail'];
                $data['id_card'] = $params['legalIdCard'];
                $data['type'] = $params['type'];
                //unset($params['type']);
                $data['info'] = json_encode($params,JSON_UNESCAPED_UNICODE);

                if(!CheckInfo::getByUid($admin['id'])){//第一次提交审核信息
                    $data['requestNo'] = getOrderId();
                    CheckInfo::create($data);
                }else{
                    unset($data['uid']);
                    CheckInfo::update($data,['uid'=>$admin['id']]);
                }
                Admin::update(['check_status'=>2],['id'=>$admin['id']]);
                //var_dump($data);
                $this->success('提交成功');
            }else{
                $this->error('请勿重复提交');
            }
        }
        if($admin['check_status'] == 2 || $admin['check_status'] == 5){
            $this->success('正在返回首页','index/type');
        }
        $type = input('get.type');
        $check_info = CheckInfo::getByUid($admin['id']);
        if($check_info && $type == $check_info->type){
            $info = json_decode($check_info->info,true);
            $this->assign('info',$info);
            $this->assignconfig('bankcity', $info['bankCity']);
            $this->assignconfig('bankcode', $info['bankCode']);
            $this->assignconfig('mercity', $info['merCity']);
            $this->assignconfig('merdistrict', $info['merDistrict']);
            switch ($type){
                case '3':
                    $this->assignconfig('level2', $info['merLevel2No']);
                    break;
            }
        }
        $province = Db::name('province')->where(['level'=>1])->field('id,name,code')->select();
        $bank = Db::name('bank_code')->select();
        $store = Db::name('store_type')->where(['level'=>1])->field('id,name,code')->select();
        $this->assign('bank',$bank);
        $this->assign('store',$store);
        $this->assign('province',$province);
        return $this->view->fetch($type);
    }



}
