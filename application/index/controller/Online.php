<?php
/**
 * Created by PhpStorm.
 * User: MYS
 * Date: 2018/11/28
 * Time: 14:50
 */

namespace app\index\controller;
use app\cms\model\Online as OnlineModel;
use app\cms\model\Tk;
use think\Db;

class Online extends Home
{
    /**
     * @NAME: index
     * 列表
     */
    public function index()
    {
        //取数据
        $onlines=OnlineModel::where("status",1)->select();
        //显示视图传输数据
//        halt($notice);

        return view("index",compact("onlines"));
    }

    /**
     * @NAME: detail
     * 详情
     */
    public function detail($id)
    {

        $online = OnlineModel::get($id);

//        halt($online);

        return view("detail", compact("online"));
    }

    /**
     * @NAME: sc
     * 收藏
     */
    public function sc($id)
    {

//        halt($id);

        $sj=Db::name("cms_online")->where("id",$id)->select();

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
        //查出当前数据
        $onlines =Db::name("cms_online")->where("grade",$id)->select();

//        halt($onlines);

        $data=[];

        foreach ($onlines as $k=>$v){
            $data=$v;
        }

//        halt($data);

        $tks=Db::name("cms_tk")->where("a_id","=",$data['grade'])->order("top_id","asc")->limit(1)->select();

//        halt($tks);

        $datas=[];

        foreach ($tks as $k=>$v){
            $datas=$v;
        }

//        halt($datas);

        return view("section",compact("datas"));

    }


    /**
     * @NAME: check
     * 验证
     */
    public function check($id)
    {
//        halt($id);

        //接收数据
        $answerA=$_POST["answerA"];

//        halt($answerA);

        if($answerA==null){
            exit("<script>alert('提交数据不能为空');history.go(-1)</script>");
        }

        if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$answerA)){
            exit("<script>alert('请输入中文字符');history.go(-1)</script>");
        }

        //添加数据
        $tks=Db::name("cms_tk")->where("id",$id)->setField("answerA",$answerA);

        if($tks){
            exit("<script>alert('提交成功');history.go(-1)</script>");
        }else{
            exit("<script>alert('提交失败');history.go(-1)</script>");
        }

    }

    /**
     * @NAME: next
     * 下一页
     */
    public function next($id)
    {
//        halt($id);

        $onlines=Db::name("cms_tk")->where("a_id",$id)->count();

//        halt($onlines);

        //页数 如果没有显示为1
        $page=empty($_POST['page'])?1:$_POST['page'];

        //总条数
        $count=$onlines;

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

        return view("section",compact("datas"));

    }

    /**
     * @NAME: parse
     * 真题解析
     */
    public function parse($id)
    {
        //查询出题库
        $zts=Db::name("cms_tk")->where("id",$id)->select();

//        halt($zts);

        $datas=[];

        foreach ($zts as $k=>$v){
            $datas=$v;
        }

        return view("ztjx",compact("datas"));

    }



}