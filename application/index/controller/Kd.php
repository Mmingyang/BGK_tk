<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Kd as KdModel;
use think\Db;

class Kd extends Home
{

    public function index()
    {
        //取数据
        $tobs=KdModel::where("status",1)->select();
        //显示视图传输数据
//        halt($notice);

        return view("index",compact("tobs"));
    }

    public function detail($id)
    {

        $tob = KdModel::get($id);

        return view("detail", compact("tob"));
    }


}