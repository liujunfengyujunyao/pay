<?php

namespace app\api\controller;

use app\common\controller\Api;
use think\Db;
use app\common\model\Area;
use app\common\model\Version;
use fast\Random;
use think\Config;

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Headers: Content-Type,Content-Length,Accept-Encoding,X-Requested-with, Origin');

class Register extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    //省市县
    public function address(){
        if($this->request->isPost()){
            $pid = input('post.id');
            $list  = Db::name('province')->where(['pid'=>$pid])->field('code,id,name')->select();
            if(!$list){
                $this->error('未查询到结果');
            }else{
                $this->success('请求成功',$list);
            }
        }else{
            $this->error('404');
        }
    }

    //银行总行列表
    public function bank(){
        if($this->request->isPost()){
            $list = Db::name('bank_code')->field('code,id,name')->select();
            if(!$list){
                $this->error('未查询到结果');
            }else{
                $this->success('请求成功',$list);
            }
        }else{
            $this->error('404');
        }
    }

    //商户一二级分类
    public function store(){
        if($this->request->isPost()){
            $pid = input('post.id');
            $list = Db::name('store_type')->where(['pid'=>$pid])->field('code,id,name')->select();
            if(!$list){
                $this->error('未查询到结果');
            }else{
                $this->success('请求成功',$list);
            }
        }else{
            $this->error('404');
        }
    }

}
