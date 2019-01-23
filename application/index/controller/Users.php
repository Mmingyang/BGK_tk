<?php

namespace app\index\controller;
use app\cms\model\Users as UsersModel;
use think\Db;
use think\Error;
use think\Request;
use think\Session;
use think\Validate;

class Users extends Home
{
    public function login(Request $request)
    {
        if($request->isPost()) {

            // 表单数据
            $data=$this->request->post();

            $data['password']=md5($data["password"]);

            $da=Db::name("cms_users")->where("name",$data['name'])->where("password",$data['password'])->select();

            if ($da){
                Session::set("name",$data['name']);
                $this->success('登录成功',"user/index");
            } else {
                $this->error('登录失败',"user/index");
            }
        }

        return view("login");
    }

    public function logout()
    {

        $lout=session_unset();

//        halt($lout);

        if($lout==null){
            $this->success("退出成功");
        }else{
            $this->error("退出失败");
        }


    }



}