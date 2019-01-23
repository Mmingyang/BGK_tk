<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 16:25
 */

namespace app\index\controller;


use EasyWeChat\Foundation\Application;
use think\Db;

class User extends Home
{
    public function index()
    {
        $user=Db::name("user")->where("open_id",$this->openId)->find();

        return view("index",compact("user"));
    }

    //我的订单
    public function order()
    {
        //取出当前用户的openID
        $member=session("wechat_user");

        $openId=$member['id'];

        $user=Db::name("members")->where('open_id',$openId)->find();

//        var_dump($user);
        //判断用户有没有绑定没有就跳转绑定页面
        if($user==null){

            //跳转
            return $this->redirect("bind");
        }

        //把订单信息取出来
        $orders=Db::name("orders")->where('user_id',$user['id'])->select();

        return view("order",compact("orders"));

    }

    //绑定页面
    public function bind(Request $request)
    {
        if($request->isPost()){
            //验证
            $username=$request->post('username');

            //找用户
            $user=Db::name("members")->where('username',$username)->find();
            if($user && password_verify($request->post('password'),$user['password'])){

                $member=session("wechat_user");
                $openId=$member['id'];

                //绑定账号
                $result=Db::name("members")->where('id',$user['id'])->update(['open_id'=>$openId]);

                if($result){

                    //模板
                    $userId = 'OPENID';
                    //模板ID
                    $templateId = '5QuTYPKvNL-jy1Cp2EUE0TnHNbakzD1V9ksXvaW6RoQ';
                    //跳转地址
                    $url = 'http://wx.mys8178.cn/wx/user/detail';
                    //模板参数
                    $data = array(
                        "username"  => $user['username'],

                    );
                    $app = new Application(config("wx"));

                    $notice = $app->notice;

                    $moban = $notice->uses($templateId)->withUrl($url)->andData($data)->andReceiver($openId)->send();


                    return $this->success("绑定成功",url("wx/user/order"));
                }

            }else{

                return $this->error("绑定失败，请检查账号或密码是否正确");
            }
        }

        //判断绑没绑定绑了就显示解绑页面
        //取出当前用户的openID
        $member=session("wechat_user");

        $openId=$member['id'];

        $meber=Db::name("members")->where("open_id",$openId)->find();

        if($meber){
            //已绑定显示解除绑定
            return view("unbind");
        }else{
            //未绑定显示绑定
            return view("bind");
        }

    }


    //我的信息
    public function detail()
    {
        $member=session("wechat_user");

        $openId=$member['id'];

        $user=Db::name("members")->where('open_id',$openId)->find();

        if($user==null){
            //跳转
            return $this->redirect("bind");
        }

//        dump($user);
        return view("detail",compact("user"));

    }


    //解除绑定
    public function unbind()
    {
        //取出当前用户的openID
        $member=session("wechat_user");

        //得到openID
        $openId=$member['id'];

        $user=Db::name("members")->where('open_id',$openId)->find();

        //判断当前用户是否已绑定
        if($user) {
            //解除绑定
            Db::name("members")->where('open_id', $openId)->update(["open_id" => null]);

            return $this->success("解除绑定成功","bind");
        }

    }





}