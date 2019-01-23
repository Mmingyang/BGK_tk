<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Xin as XinModel;
use think\Db;

class Xin extends Home
{

    public function index()
    {
        //取数据
        $tobs=XinModel::where("status",1)->select();
        //显示视图传输数据
//        halt($notice);

        return view("index",compact("tobs"));
    }

    public function detail($id)
    {

        $tob = XinModel::get($id);

        return view("detail", compact("tob"));
    }


}