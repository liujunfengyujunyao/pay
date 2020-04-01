<?php

namespace app\admin\controller\check;

use app\common\controller\Backend;
use think\Db;
/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Wait extends Backend
{
    
    /**
     * Wait模型对象
     * @var \app\admin\model\check\Wait
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\check\Wait;
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
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $status['status'] = ['eq',0];
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
                $row->visible(['id','uid','name','mobile','email','id_card','type','add_time']);
                
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    public function detail()
    {
        $check_id = request()->param('ids');//check_info的主键
        $check_info = DB::name('check_info')->where(['id'=>$check_id])->value('info');
        $type = DB::name('check_info')->where(['id'=>$check_id])->value('type');
        $info = json_decode($check_info,true);

//        halt($check_info);
        if ($this->request->isAjax()) {

            $params = request()->param();
            $info = json_decode(DB::name('check_info')->where(['id'=>$params['check_id']])->value('info'),true);
//            halt($info);
//           halt(['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_FRONT']]);
            if ($params) {
                if($params['cost'] > 0.06){
                    $this->error(__('费率过大'));
                }

                $status = $params['status'];

                $params = $this->preExcludeFields($params);//过滤

                $check = $this->model->where(['id'=>$params['check_id']])->find();

                $result = $this->model->where(['id'=>$check['id']])->update(['update_time'=>time(),'cost'=>$params['cost']]);

                if(!empty($params['del'])){//驳回
                    $sql = DB::name('admin')->where(['id'=>$check['uid']])->update(['check_status'=>5]);
                }elseif(!empty($params['update'])){//修正
                    $sql = DB::name('admin')->where(['id'=>$check['uid']])->update(['check_status'=>3]);
                }else{
                    $sql = DB::name('admin')->where(['id'=>$check['uid']])->update(['check_status'=>2]);
                    
                    //调用接口发送info
                    $data = DB::name('check_info')->where(['id'=>$params['check_id']])->find();
                    $type = $data['type'];//1个人  2个体  3企业
//                    $info = $data['info'];//信息
//                    halt(json_decode($info,true));
                    $url = $_SERVER['HTTP_HOST'] . "/api/yeepay/send_upload";
                    if($type == 1){

//                        halt(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_FRONT']]));
                        $json = [
                            'requestNo' => $info['requestNo'],
                            'notifyUrl' => $_SERVER['HTTP_HOST'] . "/api/notify/check_notify",
                            'type' => $type,
                            'legalName' => $info['legalName'],
                            'legalIdCard' => $info['legalIdCard'],
                            'merLegalPhone' => $info['merLegalPhone'],
                            'merLegalEmail' => $info['merLegalEmail'],
                            'merProvince' => $info['merProvince'],
                            'merCity' => $info['merCity'],
                            'merDistrict' => $info['merDistrict'],
                            'merAddress' => $info['merAddress'],
                            'IDCARD_FRONT' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_FRONT']]),true)['data'],
                            'IDCARD_BACK' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_BACK']]),true)['data'],
                            'HAND_IDCARD' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['HAND_IDCARD']]),true)['data'],
                            'headBankCode' => $info['headBankCode'],
                            'bankProvince' => $info['bankProvince'],
                            'bankCity' => $info['bankCity'],
                            'bankCode' => $info['bankCode'],
                            'cardNo' => $info['cardNo'],
                            'SETTLE_BANKCARD' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['SETTLE_BANKCARD']]),true)['data'],
                            'HAND_BANKCARD' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['HAND_BANKCARD']]),true)['data'],
                        ];
                    }elseif($type == 2){
                        $json = [
                            'requestNo' => $info['requestNo'],
                            'notifyUrl' => $_SERVER['HTTP_HOST'] . "/api/notify/check_notify",
                            'type' => $type,
                            'merFullName' => $info['merFullName'],
                            'merShortName' => $info['merShortName'],
                            'merCertNo' => $info['merCertNo'],
                            'legalName' => $info['legalName'],
                            'legalIdCard' => $info['legalIdCard'],
                            'merLegalPhone' => $info['merLegalPhone'],
                            'merLegalEmail' => $info['merLegalEmail'],
                            'merLevel1No' => $info['merLevel1No'],
                            'merLevel2No' => $info['merLevel2No'],
                            'merProvince' => $info['merProvince'],
                            'merCity' => $info['merCity'],
                            'merDistrict' => $info['merDistrict'],
                            'merAddress' => $info['merAddress'],
                            'cardNo' => $info['cardNo'],
                            'headBankCode' => $info['headBankCode'],
                            'bankProvince' => $info['bankProvince'],
                            'bankCity' => $info['bankCity'],
                            'bankCode' => $info['bankCode'],
                            'IDCARD_FRONT' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_FRONT']]),true)['data'],
                            'IDCARD_BACK' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_BACK']]),true)['data'],
                            'SETTLE_BANKCARD' =>json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['SETTLE_BANKCARD']]),true)['data'],
                            'BUSINESS_PLACE' =>json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['BUSINESS_PLACE']]),true)['data'],
                            'CASHIER_SCENE' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['CASHIER_SCENE']]),true)['data'],
                        ];
                    }else{
                        $json = [
                            'requestNo' => $info['requestNo'],
                            'notifyUrl' => $_SERVER['HTTP_HOST'] . "/api/notify/check_notify",
                            'type' => $type,
                            'merFullName' => $info['merFullName'],
                            'merShortName' => $info['merShortName'],
                            'merCertType' => $info['merCertType'],
                            'merCertNo' => $info['merCertNo'],
                            'legalName' => $info['legalName'],
                            'legalIdCard' => $info['legalIdCard'],
                            'merContactName' => $info['merContactName'],
                            'merLegalPhone' => $info['merLegalPhone'],
                            'merLegalEmail' => $info['merLegalEmail'],
                            'merLevel1No' => $info['merLevel1No'],
                            'merLevel2No' => $info['merLevel2No'],
                            'merProvince' => $info['merProvince'],
                            'merCity' => $info['merCity'],
                            'merDistrict' => $info['merDistrict'],
                            'merAddress' => $info['merAddress'],
                            'accountLicense' => $info['accountLicense'],
                            'cardNo' => $info['cardNo'],
                            'headBankCode' => $info['headBankCode'],
                            'bankProvince' => $info['bankProvince'],
                            'bankCity' => $info['bankCity'],
                            'bankCode' => $info['bankCode'],
                            'IDCARD_FRONT' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_FRONT']]),true)['data'],
                            'IDCARD_BACK' => json_decode(json_curl($url,[ 'path'=>$_SERVER['DOCUMENT_ROOT'] . $info['IDCARD_BACK']]),true)['data'],
                            'CORP_CODE' =>json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['CORP_CODE']]),true)['data'],
                            'UNI_CREDIT_CODE' =>json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['UNI_CREDIT_CODE']]),true)['data'],
                            'OP_BANK_CODE' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['OP_BANK_CODE']]),true)['data'],
                            'BUSINESS_PLACE' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['BUSINESS_PLACE']]),true)['data'],
                            'CASHIER_SCENE' => json_decode(json_curl($url,['path'=>$_SERVER['DOCUMENT_ROOT'] . $info['CASHIER_SCENE']]),true)['data'],
                        ];

                    }
                    DB::name('check_info')->where(['id'=>$check['id']])->update(['requestNo'=>$json['requestNo']]);//补全唯一订单号
                    $json = json_encode($json,JSON_UNESCAPED_UNICODE);
                    $update = DB::name('check_info')->where(['id'=>$params['check_id']])->update(['json'=>$json]);
                    $curl = json_curl($_SERVER['HTTP_HOST'].'/api/yeepay/reginfoadd',json_decode($json,true));

                    $status = $this->model->where(['id'=>$check['id']])->update(['status'=>$status]);
halt($curl);
                }

                if ($result !== false && $sql !== false && $update !== false || $status !== false) {
                    $this->success('操作完成','admin/check/wait');
                } else {
                    $this->error(__('网络错误'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('type',$type);
        $this->view->assign('row',$info);
        return $this->view->fetch();
    }
}
