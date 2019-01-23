<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Ctk as CtkModel;
use think\Db;

class Ctk extends Home
{
    /**
     * @NAME: index
     * 列表
     */
    public function index()
    {
        //取数据
        $notices=CtkModel::all();
        //显示视图传输数据
//        halt($notice);

        return view("index",compact("notices"));
    }

    /**
     * @NAME: detail
     * 详情
     */
    public function detail($id)
    {
        $sj=Db::name("cms_ctk")->where("id",$id)->select();

        $data=[];

        foreach ($sj as $k=>$v){
            $data=$v;
        }

//        halt($data);

        return view("detail", compact("data"));
    }


    /**
     * @NAME: section
     * 章节练习
     */
    public function section($id)
    {
        $tks = Db::name("cms_tk")->where("id",$id)->select();

//        halt($tks);

        $datas=[];

        foreach ($tks as $k=>$v){
            $datas=$v;
        }

//        halt($datas);

        return view("section", compact("datas"));

    }


    /**
     * @NAME: nextpage
     * 下一页
     */
    public function next($id)
    {
        //查询总条数
        $tks=Db::name("cms_tk")->where("a_id",$id)->count();
//        halt($tks);
        //页数 如果没有显示为1
        $page=empty($_GET['page'])?1:$_GET['page'];
        //总条数
        $count=$tks;
        //每页显示条数
        $num=1;
        //向上取整
        $pageCount=ceil($count / $num);
        $offset=($count - 1) * $num;
        $sql=Db::name("cms_tk")->where("a_id",$id)->order("top_id","ASC")->limit($offset,$num)->select();
//        halt($sql);
        $datas=[];
        foreach ($sql as $k=>$v){
            $datas=$v;
        }
//        halt($datas);
        return view("section", compact("datas"));

    }


}