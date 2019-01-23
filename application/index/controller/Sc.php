<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Sc as ScModel;
use think\Db;

class Sc extends Home
{
    /**
     * @NAME: index
     * 列表
     */
    public function index()
    {
        //取数据
        $notices=ScModel::all();
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
        $sj=Db::name("cms_sc")->where("id",$id)->select();

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
//        halt($id);

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
     * @NAME: check
     * 验证答案是否正确
     */
    public function check($id)
    {
        $tk=Db::name("cms_tk")->where("id",$id)->select();

//        halt($tk);

        $data=[];

        foreach ($tk as $k=>$v){
            $data=$v;
        }

//        halt($data['true']);

        @$daan=$_GET["true"];

        if($daan==null){
            exit("<script>alert('选项不能为空');history.go(-1)</script>");
        }

//        halt($daan);

        if($data["true"]==$daan){
            exit("<script>alert('恭喜你,回答正确');history.go(-2)</script>");
        }else{
            exit("<script>alert('很遗憾,回答错误');history.go(-2)</script>");
        }

    }

    /**
     * @NAME: parse
     * 真题解析
     */
    public function parse($id)
    {
//        halt($id);

        //查询题库的数据
        $parses=Db::name("cms_tk")->where('id',$id)->select();

//        halt($parses);

        $datas=[];

        foreach ($parses as $k=>$v){
            $datas=$v;
        }

        return view("ztjx",compact("datas"));

    }


}