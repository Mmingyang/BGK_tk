<?php

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\cms\model\Tk as TkModel;

use think\Db;
use think\Validate;

class Tk extends Admin
{
    /**
     * 小区通知列表
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('update_time desc');
        // 数据列表
        $data_list = TkModel::where($map)->order($order)->paginate();


        //题库归属
        $notices=Db::name("cms_notice")->select();

//        halt($notices);

        $da=[];

        foreach ($notices as $k=>$v){
            $da[$k]=$v["title"];
        }

//        halt($da);

        $services=Db::name("cms_service")->select();

        $da1=[];

        foreach ($services as $k=>$v){
            $da1[$k]=$v["title"];
        }

//        halt($da1);

        $onlines=Db::name("cms_online")->select();

        $da2=[];

        foreach ($onlines as $k=>$v){
            $da2[$k]=$v["title"];
        }

//        halt($da2);

        $shops=Db::name("cms_shops")->select();

        $da3=[];

        foreach ($shops as $k=>$v){
            $da3[$k]=$v["title"];
        }

//        halt($da3);

        $zushous=Db::name("cms_zushou")->select();

        $da4=[];

        foreach ($zushous as $k=>$v){
            $da4[$k]=$v["title"];
        }

//        halt($da4);

        $events=Db::name("cms_event")->select();

        $da5=[];

        foreach ($events as $k=>$v){
            $da5[$k]=$v["title"];
        }

//        halt($da5);

        //多个表的数据相加
        $tks=array_merge($da,$da1,$da2,$da3,$da4,$da5);

//        halt($tks);

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['title' => '标题']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['title', '标题', 'text'],
                ['answerA', '答案A', 'text'],
                ['answerB', '答案B', 'text'],
                ['answerC', '答案C', 'text'],
                ['answerD', '答案D', 'text'],
                ['true', '正确答案', 'status','',["A:info","B:info","C:info","D:info"]],
                ['a_id', '归属', 'status','',$tks],
                ['top_id', '题级', 'text'],
                ['parse', '真题解析', 'text'],
                ['right_button', '操作', 'btn'],
            ])
            ->addOrder('id')//以ID进行排序
            ->addTopButtons('add,delete') // 批量添加顶部按钮
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮

            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }


    /**
     * 新增
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            if ($advert = TkModel::create($data)) {
                $this->success('添加成功', 'index');
            } else {
                $this->error('添加失败');
            }
        }


        //题库归属
        $notices=Db::name("cms_notice")->select();

//        halt($notices);

        $da=[];

        foreach ($notices as $k=>$v){
            $da[$k]=$v["title"];
//            $da[$k]=$v["id"];
        }

//        halt($da);

        $services=Db::name("cms_service")->select();

        $da1=[];

        foreach ($services as $k=>$v){
            $da1[$k]=$v["title"];
        }

//        halt($da1);

        $onlines=Db::name("cms_online")->select();

        $da2=[];

        foreach ($onlines as $k=>$v){
            $da2[$k]=$v["title"];
        }

//        halt($da2);

        $shops=Db::name("cms_shops")->select();

        $da3=[];

        foreach ($shops as $k=>$v){
            $da3[$k]=$v["title"];
        }

//        halt($da3);

        $zushous=Db::name("cms_zushou")->select();

        $da4=[];

        foreach ($zushous as $k=>$v){
            $da4[$k]=$v["title"];
        }

//        halt($da4);

        $events=Db::name("cms_event")->select();

        $da5=[];

        foreach ($events as $k=>$v){
            $da5[$k]=$v["title"];
        }

//        halt($da5);

        //多个表的数据相加
        $tks=array_merge($da,$da1,$da2,$da3,$da4,$da5);

//        halt($tks);


        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'title', '标题'],
                ['text', 'top_id', '题级'],
                ['text', 'answerA','答案A'],
                ['text', 'answerB','答案B'],
                ['text', 'answerC','答案C'],
                ['text', 'answerD','答案D'],
                ['radio', 'true','正确答案','',['answerA','answerB','answerC','answerD'],0],
                ['text','parse','真题解析'],
                ['radio', 'status', '立即启用', '', ['否', '是'], 1],
            ])
            ->addSelect('a_id', '分配', '分配',$tks)
            ->fetch();
    }


    /**
     * 编辑
    */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            if (TkModel::update($data)) {
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }



        //题库归属
        $notices=Db::name("cms_notice")->select();

        $da=[];

        foreach ($notices as $k=>$v){
            $da[$k]=$v["title"];
        }

//        halt($da);

        $services=Db::name("cms_service")->select();

        $da1=[];

        foreach ($services as $k=>$v){
            $da1[$k]=$v["title"];
        }

//        halt($da1);

        $onlines=Db::name("cms_online")->select();

        $da2=[];

        foreach ($onlines as $k=>$v){
            $da2[$k]=$v["title"];
        }

//        halt($da2);

        $shops=Db::name("cms_shops")->select();

        $da3=[];

        foreach ($shops as $k=>$v){
            $da3[$k]=$v["title"];
        }

//        halt($da3);

        $zushous=Db::name("cms_zushou")->select();

        $da4=[];

        foreach ($zushous as $k=>$v){
            $da4[$k]=$v["title"];
        }

//        halt($da4);

        $events=Db::name("cms_event")->select();

        $da5=[];

        foreach ($events as $k=>$v){
            $da5[$k]=$v["title"];
        }

//        halt($da5);

        //多个表的数据相加
        $tks=array_merge($da,$da1,$da2,$da3,$da4,$da5);

//        halt($tks);


        //找出当前通知的ID
        $info = TkModel::get($id);
        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'title', '标题'],
                ['text', 'top_id', '题级'],
                ['text', 'answerA','答案A'],
                ['text', 'answerB','答案B'],
                ['text', 'answerC','答案C'],
                ['text', 'answerD','答案D'],
                ['radio', 'true','正确答案','',['answerA','answerB','answerC','answerD'],0],
                ['text','parse','真题解析'],
                ['radio', 'status', '立即启用', '', ['否', '是'], 1],
            ])
            ->setFormData($info)
            ->addSelect('a_id','分配', '必填*',$tks)
            ->fetch();
    }

    /**
     * 删除广告
     */
    public function delete($record = [])
    {
        return $this->setStatus('delete');
    }

    /**
     * 启用广告
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function enable($record = [])
    {
        return $this->setStatus('enable');
    }

    /**
     * 禁用广告
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function disable($record = [])
    {
        return $this->setStatus('disable');
    }

    /**
     * 设置广告状态：删除、禁用、启用
     * @param string $type 类型：delete/enable/disable
     * @param array $record
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function setStatus($type = '', $record = [])
    {
        $ids         = $this->request->isPost() ? input('post.ids/a') : input('param.ids');
        $notice_name = TkModel::where('id', 'in', $ids)->column('title');
        return parent::setStatus($type, ['tk_'.$type, 'cms_tk', 0, UID, implode('、', $notice_name)]);
    }

    /**
     * 快速编辑
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function quickEdit($record = [])
    {
        $id      = input('post.pk', '');
        $field   = input('post.name', '');
        $value   = input('post.value', '');
        $advert  = TkModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $advert . ')，新值：(' . $value . ')';
        return parent::quickEdit(['tk_edit', 'cms_tk', $id, UID, $details]);
    }


}