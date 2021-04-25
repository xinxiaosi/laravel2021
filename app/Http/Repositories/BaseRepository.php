<?php


namespace App\Http\Repositories;


use App\Exceptions\ApiException;

class BaseRepository implements Repository
{
    const TRUE = 1;
    const False = 2;
    const PAGE = 'page';
    const DATA_FORMAT = 'Y-m-d H:i:s';

    public function notExist($array = [],$data = [])
    {
        $where = [];
        foreach ($array as $k => $v){
            (!isset($data[$v])) && $where[$v] = $data[$v] = 0;
        }
        return $where;
    }

    /**
     * 处理查询条件
     * 判断字段是否存在，然后对where进行复制
     * @param array $array 判断字段
     * @param array $data 判断数据
     * @return array
     */
    public function isExist($array = [],$data = [])
    {
        $where = [];
        foreach ($array as $k => $v){
            (isset($data[$v]) && !empty($data[$v])) && $where[$v] = $data[$v];
        }
        return $where;
    }

    /**
     * 无权限控制的删除操作
     * @param $data
     * @param $model
     * @return array
     */
    public function delData($data, $model)
    {
        $ids = explode(',', $data['ids']);
        $str = '';
        $success = 0;
        $fail = 0;

        foreach ($ids as $k => $v) {
            $data = $model->find($v);

            if (empty($data)) {
                $fail ++;
                $str .= '第'.($k+1).'个操作失败原因是：数据不存在;';
                continue;
            } else {
                $success ++;
                $data->delete();
                continue;
            }

        }

        return ['message' => $success.'个操作成功;'.$fail.'操作失败;'.$str];
    }

    /**
     * 递归分类
     * @param $array
     * @param int $pid
     * @return array
     */
    function getTree($array, $pid = 0)
    {
        static $tree = [];

        foreach($array as $key => $value){
            if($value['parent_id'] == $pid){
                $tree[$value['id']] = $value;
                unset($array[$key]);
                $tree[$value['id']]['children'] = $this->getTree($array, $value['id']);
            }
        }

        return $tree;

    }

    /**
     * 算法分类
     * @param $data
     * @return array
     */
    public function generateTree($data)
    {
        //重组数组 键值key改为id 一般key值从1开始
        $items = [];
        foreach($data as $v) {
            $items[$v['id']] = $v;
        }

        $tree = [];
        foreach($items as $k => $v){
            if (isset($items[$v['parent_id']])) {
                //不是根节点的将自己的地址放到父级的child节点
                $items[$v['parent_id']] ['children'] [] = &$items[$k];
            } else {
                //根节点直接把地址放到新数组中
                $tree[] = &$items[$k];
            }
        }

        return $tree;
    }

    /**
     * json处理
     * @param $data
     * @return false|mixed|string
     */
    public function handleJsonData($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }

        return json_decode($data, true);
    }

    /**
     * 下拉框递归数据
     * @param $array
     * @param int $pid
     * @param string $name
     * @param int $level
     * @return array
     */
    public function generateTreeStr($array, $name = 'name', $pid = 0, $level = 0)
    {
        static $tree = [];

        foreach($array as $key => $value){
            if($value['parent_id'] == $pid){
                $value['level'] = $level;
                $value['name_str'] = str_repeat('--- ', $level).$value[$name];
                $tree[] = $value;
                unset($array[$key]);
                $this->generateTreeStr($array, $name, $value['id'], $level+1);
            }
        }

        return $tree;
    }

    public function throwError($error = ApiException::ERROR_DATA_NOT_EXISTS)
    {
        throw new ApiException($error);
    }
}
