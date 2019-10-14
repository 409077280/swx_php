<?php

namespace app\api\model\bargain;

use app\common\model\bargain\Active as ActiveModel;

use app\common\library\helper;
use app\api\model\bargain\Task as TaskModel;
use app\api\model\bargain\TaskHelp as TaskHelpModel;
use app\common\service\Goods as GoodsService;

/**
 * 砍价活动模型
 * Class Active
 * @package app\api\model\bargain
 */
class Active extends ActiveModel
{
    /**
     * 隐藏的字段
     * @var array
     */
    protected $hidden = [
        'peoples',
        'is_self_cut',
        'initial_sales',
        'actual_sales',
        'sort',
        'create_time',
        'update_time',
        'wxapp_id',
        'is_delete',
    ];

    /**
     * 获取器：分享标题
     * @param $value
     * @return mixed
     */
    public function getShareTitleAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * 获取器：砍价助力语
     * @param $value
     * @return mixed
     */
    public function getPromptWordsAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    /**
     * 活动会场列表
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getHallList()
    {
        // 砍价活动列表
        $list = $this->where('start_time', '<=', time())
            ->where('end_time', '>=', time())
            ->where('status', '=', 1)
            ->where('is_delete', '=', 0)
            ->order(['sort' => 'asc'])
            ->paginate(15, false, [
                'query' => \request()->request()
            ]);
        // 设置商品数据
        $list = GoodsService::setGoodsData($list);
        // 整理正在砍价的助力信息
        $list = $this->setHelpsData($list);
        return $list;
    }

    /**
     * 整理正在砍价的助力信息
     * @param $list
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function setHelpsData($list)
    {
        $activeIds = helper::getArrayColumn($list, 'active_id');
        $data = (new TaskHelpModel)->getUnderwayByActiveIds($activeIds);
        foreach ($list as &$item) {
            $helps = [];
            foreach ($data as &$help) {
                if ($help['active_id'] == $item['active_id']) {
                    $helps[] = $help;
                    unset($help);
                }
            }
            $item['helps'] = $helps;
            $item['helps_count'] = count($helps);
        }
        return $list;
    }

    /**
     * 获取砍价活动详情
     * @param $activeId
     * @return Active|bool|null
     * @throws \think\exception\DbException
     */
    public function getDetail($activeId)
    {
        $model = static::detail($activeId);
        if (empty($model) || $model['is_delete'] == true || $model['status'] == false) {
            $this->error = '很抱歉，该砍价商品不存在或已下架';
            return false;
        }
        return $model;
    }

    /**
     * 获取用户是否正在参与改砍价活动，如果已参与则返回task_id
     * @param $activeId
     * @param bool $user
     * @return bool|int
     */
    public function getWhetherPartake($activeId, $user = false)
    {
        if ($user === false) {
            return false;
        }
        return TaskModel::getHandByUser($activeId, $user['user_id']);
    }

    /**
     * 累计活动销量(实际)
     * @return int|true
     * @throws \think\Exception
     */
    public function setIncSales()
    {
        return $this->setInc('actual_sales');
    }

}