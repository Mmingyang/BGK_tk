<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 16:41
 */

namespace app\index\controller;


use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\News;
use think\Controller;
use think\Db;

class Server extends Controller
{
    //回调
    public function call()
    {

        $app = new Application(config("wx"));
        $oauth = $app->oauth;

        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();

        session("user",$user->toArray());

        $targetUrl=session("target_url")??"/";

//        header('location:'. $targetUrl);

        return redirect($targetUrl);

    }

    //自动回复
    public function index()
    {
        $app = new Application(config('wx'));
        //halt($app);
        $server = $app->server;

        $server->setMessageHandler(function ($message) {

//            return $message->Event;
            if($message->Event=="CLICK"){
                switch ($message->EventKey){
                    case "V1001_TODAY_MUSIC":
                        $goods = Db::name("menus")->limit(5)->select();
//                        halt($goods);
                        $news = [];
                        foreach ($goods as $good) {

                            $new = new News([
                                'title' => $good['goods_name'],
                                'description' => $good['description'],
                                'url' => "https://www.feizl.com/html/67211.htm",
                                'image' => $good['goods_img'],
                            ]);
                            $news[] = $new;
                        }
                        return $news;
                        break;

                    case "tel":
                        return "地址：中国重庆\nTel:123456";
                        break;
                }

            }


            if($message->Content=="美女"){

                //接收微信服务器发来的信息(xml格式)
                $data=file_get_contents("php://input");

                //解析xml
                $data=simplexml_load_string($data);
                //取数据
                $toUsername=(string)$data->ToUserName;
                $FromUserName=(string)$data->FromUserName;
                $Content=(string)$data->Content;
                //开启ob缓存
                ob_start();

                $girls = [
                    [
                        'title' => "张天爱",
                        'des' => "张天爱，1990年10月28日出生于黑龙江省哈尔滨市，中国内地影视女演员",
                        'pic' => "https://gss0.bdstatic.com/-4o3dSag_xI4khGkpoWK1HF6hhy/baike/w%3D268%3Bg%3D0/sign=130cdff9174c510faec4e51c58624210/7c1ed21b0ef41bd53926e9c55cda81cb39db3d8d.jpg",
                        'url' => "https://baike.baidu.com/item/%E5%BC%A0%E5%A4%A9%E7%88%B1/14081783?fr=aladdin"
                    ],

                ];

                ?>

                <xml>
                    <ToUserName><![CDATA[<?=$FromUserName?>]]></ToUserName>
                    <FromUserName><![CDATA[<?=$toUsername?>]]></FromUserName>
                    <CreateTime><?=time()?></CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount><?=count($girls)?></ArticleCount>
                    <Articles>
                        <?php foreach ($girls as $girl):?>
                            <item><Title><![CDATA[<?=$girl['title']?>]]></Title>
                                <Description><![CDATA[<?= $girl['des']?>]]></Description>
                                <PicUrl><![CDATA[<?=$girl['pic']?>]]></PicUrl>
                                <Url><![CDATA[<?=$girl['url']?>]]></Url>
                            </item>
                        <?php endforeach;?>
                    </Articles>
                </xml>

                <?php

                $xml = ob_get_contents();
                file_put_contents("send.xml", $xml);

            }elseif(mb_substr($message->Content,-4)=="天气预报") {
//                //接收微信服务器发来的信息(xml格式)
                $data=file_get_contents("php://input");
//                //解析xml
                $data=simplexml_load_string($data);
//                //取数据
                $toUsername=(string)$data->ToUserName;
                $FromUserName=(string)$data->FromUserName;
                $Content=(string)$data->Content;
//                //开启ob缓存
                ob_start();

                $addr = str_replace("天气预报", "", $message->Content);
                $addr = urlencode($addr);
                $url = "https://www.apiopen.top/weatherApi?city={$addr}";
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);

                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
                $data = curl_exec($curl);
                curl_close($curl);

                $data = json_decode($data, true);
                if ($data['code'] == 200) {
                    $con = $data['data']['city'] . '当前温度:' . $data['data']['wendu'] . '℃' . '提醒:' . $data['data']['ganmao'] . $data['data']['yesterday']['high'] . $data['data']['yesterday']['low'] . '天气情况:' . $data['data']['yesterday']['type'];
                } else {
                    $con = "没查询到" . mb_substr($message->Content, 0, -2) . "天气数据";
                }

                ?>

                <xml>
                    <ToUserName><![CDATA[<?=$FromUserName?>]]></ToUserName>
                    <FromUserName><![CDATA[<?=$toUsername?>]]></FromUserName>
                    <CreateTime><?=time()?></CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[<?=$con?>]]></Content>
                </xml>

                <?php

                $xml = ob_get_contents();
                file_put_contents("send.xml", $xml);

            }elseif($message->Content=="热卖商品"){

                $goods = Db::name("menus")->limit(5)->select();
//                        halt($goods);
                $news = [];
                foreach ($goods as $good) {

                    $new = new News([
                        'title' => $good['goods_name'],
                        'description' => $good['description'],
                        'url' => "https://www.feizl.com/html/67211.htm",
                        'image' => $good['goods_img'],
                    ]);
                    $news[] = $new;
                }
                return $news;

            }elseif ($message->Content=="帮助"){

                return "联系地址：中国重庆渝北\n联系人: 海洋\n联系电话: 17623657679\n联系人微信：MI549108963";

            }elseif ($message->Content=="解除绑定"){
                //得到openID
                $openId=$message->FromUserName;

                $user=Db::name("members")->where('open_id',$openId)->find();

                //判断当前用户是否已绑定
                if($user){
                    //解除绑定
                    Db::name("members")->where('open_id',$openId)->update(["open_id"=>null]);

                    return "解除绑定成功";

                }else{

                    return "未绑定账号";
                }

            }else{

                //复读机
                return $message->Content;

            }

        });

        $response = $server->serve();

        $response->send();
    }
    //得到菜单
    public function getMenu()
    {

        $app = new Application(config("wx"));

        $menu = $app->menu;

        $all=$menu->all();

        var_dump($all);

    }

    //设置菜单
    public function setMenu()
    {
        $buttons = [
            [
                "type" => "click",
                "name" => "热卖商品",
                "key"  => "V1001_TODAY_MUSIC"
            ],

            [
                "name"  => "个人中心",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "我的订单",
                        "url"  => "http://wx.mys8178.cn/wx/user/order"
                    ],

                    [
                        "type" => "view",
                        "name" => "我的信息",
                        "url"  => "http://wx.mys8178.cn/wx/user/detail"
                    ],

                    [
                        "type" => "view",
                        "name" => "绑定账号",
                        "url"  => "http://wx.mys8178.cn/wx/user/bind"
                    ],

                    [
                        "type" => "view",
                        "name" => "不求人题库",
                        "url"  => "http://wy.mys8178.cn"
                    ],

                ],
            ],
        ];

        $app = new Application(config("wx"));

        $menu = $app->menu;

        $menu->add($buttons);

    }



}