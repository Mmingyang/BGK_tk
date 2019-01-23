<?php

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\cms\model\Users as UsersModel;

use app\user\model\User;
use think\Db;
use think\Request;
use think\Validate;

class Users extends Admin
{
    /**
     * 用户显示列表
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();

        // 数据列表
        $data_list = UsersModel::where($map)->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['name' => '用户名']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['name', '用户名', 'text'],
                ['password', '用户密码', 'text'],
                ['right_button', '操作', 'btn']
            ])
            ->addTopButtons('add') // 批量添加顶部按钮：enable,disable
            ->addRightButtons(['edit', 'delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }


    /**
     * 用户添加
     */
    public function add()
    {
        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();

            //密码加密
//            $data['password']=password_hash($data['password'],PASSWORD_DEFAULT);'

            $data['password']=md5($data['password']);

            if ($advert=UsersModel::create($data)) {
                $this->success('添加成功',"index");
            } else {
                $this->error('添加失败');
            }
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'name', '用户名','<code>必填</code>'],
                ['text', 'password', '用户密码','<code>必填</code>'],
            ])
            ->fetch();
    }


    /**
     * 用户编辑
     */
    public function edit($id = null)
    {
        if ($id === null) $this->error('缺少参数');

        // 保存数据
        if ($this->request->isPost()) {
            // 表单数据
            $data = $this->request->post();
            //密码加密
//            $data['password']=password_hash($data['password'],PASSWORD_DEFAULT);

            $data['password']=md5($data['password']);


            if (UsersModel::update($data)) {
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        //找出当前通知的ID
        $info = UsersModel::get($id);
        // 显示编辑页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'name', '用户名'],
                ['text', 'password', '密码'],
            ])
            ->setFormData($info)
            ->fetch();
    }


    /**
     * 删除广告
     * @param array $record 行为日志
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
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
        $online_name = UsersModel::where('id', 'in', $ids)->column('id');
        return parent::setStatus($type, ['Users_'.$type, 'cms_Users', 0, UID, implode('、', $online_name)]);
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
        $advert  = UsersModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $advert . ')，新值：(' . $value . ')';
        return parent::quickEdit(['Users_edit', 'cms_Users', $id, UID, $details]);
    }


}