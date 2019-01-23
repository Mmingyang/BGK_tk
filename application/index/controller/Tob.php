<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Tob as TobModel;
use think\Db;

class Tob extends Home
{

    public function index()
    {
        //取数据
        $tobs=TobModel::where("status",1)->select();
        //显示视图传输数据
//        halt($notice);

        return view("index",compact("tobs"));
    }

    public function detail($id)
    {

        $tob = TobModel::get($id);

        return view("detail", compact("tob"));
    }


}