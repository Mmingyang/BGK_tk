<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2017 河源市卓锐科技有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------
// | 开源协议 ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use app\cms\model\Zushou as ZushouModel;

use think\Validate;

class Zushou extends Admin
{
    /**
     * 小区租售列表
     */
    public function index()
    {
        // 查询
        $map = $this->getMap();

        // 数据列表
        $data_list = ZushouModel::where($map)->paginate();

        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->setSearch(['title' => '标题']) // 设置搜索框
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['title', '标题', 'text.edit'],
                ['author', '发布者', 'text'],
                ['logo', '头像', 'picture'],
                ['nums', '浏览次数', 'text.edit'],
                ['status', '状态', 'switch'],
                ['right_button', '操作', 'btn']
            ])
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

            if ($advert = ZushouModel::create($data)) {
                $this->success('添加成功', 'index');
            } else {
                $this->error('添加失败');
            }
        }

        // 显示添加页面
        return ZBuilder::make('form')
            ->addFormItems([
                ['text', 'title', '标题'],
                ['text', 'author', '发布者'],
                ['text', 'nums', '浏览次数'],
                ['image', 'logo', '图片'],
                ['ueditor','content','内容'],
                ['radio', 'status', '立即启用', '', ['否', '是'], 1]
            ])
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

            if (ZushouModel::update($data)) {
                $this->success('编辑成功', 'index');
            } else {
                $this->error('编辑失败');
            }
        }
        //找出当前通知的ID
        $info = ZushouModel::get($id);
        return ZBuilder::make('form')
            ->addFormItems([
                ['hidden', 'id'],
                ['text', 'title', '标题'],
                ['text', 'author', '发布者'],
                ['text', 'nums', '浏览次数'],
                ['image', 'logo', '图片'],
                ['ueditor','content','内容'],
                ['radio', 'status', '立即启用', '', ['否', '是'], 1]
            ])
            ->setFormData($info)
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
        $zushou_name = ZushouModel::where('id', 'in', $ids)->column('name');
        return parent::setStatus($type, ['zushou_'.$type, 'cms_zushou', 0, UID, implode('、', $zushou_name)]);
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
        $advert  = ZushouModel::where('id', $id)->value($field);
        $details = '字段(' . $field . ')，原值(' . $advert . ')，新值：(' . $value . ')';
        return parent::quickEdit(['zushou_edit', 'cms_zushou', $id, UID, $details]);
    }
}