<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Event as EventModel;
use think\Db;

class Event extends Home
{
    /**
     * @NAME: index
     * 列表
     */
    public function index()
    {
        //取数据
        $notices=EventModel::where("status",1)->select();
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

        $notice = EventModel::get($id);

        return view("detail", compact("notice"));
    }

    /**
     * @NAME: sc
     * 收藏
     */
    public function sc($id)
    {

//        halt($id);

        $sj=Db::name("cms_tk")->where("id",$id)->select();

//        halt($sj);

        $data=[];

        foreach ($sj as $k=>$v){
            $data=$v;
        }

//        halt($data);

        $sc=Db::name("cms_sc")->insert($data);

        if($sc==1){
            $this->success("收藏成功");
        }else{
            $this->error("收藏失败");
        }

    }

    /**
     * @NAME: section
     * 章节练习
     */
    public function section($id)
    {
        $notices = Db::name("cms_event")->where("grade",$id)->select();

//        halt($notices);

        $data=[];

        foreach ($notices as $k=>$v){
            $data=$v;
        }

//        halt($data['grade']);

        $tks = Db::name("cms_tk")->where("a_id","=",$data['grade'])->order("top_id","asc")->limit(1)->select();

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
            $ct=Db::name("cms_ctk")->insert($data);
            exit("<script>alert('很遗憾,回答错误');history.go(-2)</script>");
        }

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

    /**
     * @NAME: parse
     * 真题解析
     */
    public function parse($id)
    {
        $zts=Db::name("cms_tk")->where("id",$id)->select();

        $datas=[];

        foreach ($zts as $k=>$v){
            $datas=$v;
        }

        return view("ztjx",compact("datas"));

    }


}