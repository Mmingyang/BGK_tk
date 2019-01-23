<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\index\controller;

use app\common\controller\Common;
use EasyWeChat\Foundation\Application;
use think\Db;

/**
 * 前台公共控制器
 * @package app\index\controller
 */
class Home extends Common
{
    public $openId;
    /**
     * 初始化方法
     * @author 蔡伟明 <314013107@qq.com>
     */
    protected function _initialize()
    {
        // 系统开关
//        if (!config('web_site_status')) {
//            $this->error('站点已经关闭，请稍后访问~');
//        }

        $app=new Application(config("wx"));
        $oauth = $app->oauth;

        // 未登录
        if (!session('user')) {

            //得到当前URL地址
            $url = request()->url();

            session("target_url", $url);

            return $oauth->redirect()->send();
            exit;
            // 这里不一定是return，如果你的框架action不是返回内容的话你就得使用
            // $oauth->redirect()->send();
        }

        //登录
        $wxUser=session("user");
        // halt($wxUser);
        $this->openId=$wxUser['id'];
        $name=$wxUser['name'];
        $logo=$wxUser['avatar'];
        $address=$wxUser['original']['province'].$wxUser['original']['city'];
        //判断当前OPenID有没有注册过
        //查询数据
        $user=Db::name("user")->where("open_id",$this->openId)->find();
        //如果没有$user 添加此用户  注册
        if ($user===null){
            Db::name("user")->insert(
                [
                    'name'=>$name,
                    'logo'=>$logo,
                    'address'=>$address,
                    'open_id'=>$this->openId
                ]
            );
        }


    }
}
