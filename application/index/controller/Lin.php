<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Lin as LinModel;
use think\Db;

class Lin extends Home
{

    public function index()
    {
        //取数据
        $tobs=LinModel::where("status",1)->select();
        //显示视图传输数据
//        halt($notice);

        return view("index",compact("tobs"));
    }

    public function detail($id)
    {

        $tob = LinModel::get($id);

        return view("detail", compact("tob"));
    }


}