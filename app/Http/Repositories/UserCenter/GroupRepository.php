<?php

namespace App\Http\Repositories\UserCenter;

use App\Http\Repositories\BaseRepository;
use App\Models\UserCenter\Models\GroupModel;

class GroupRepository extends BaseRepository
{
    public $group;

    public function __construct(GroupModel $groupModel)
    {
        $this->group = $groupModel;
    }

    public function addGroup($data)
    {
        return $this->group->addItem($data);
    }

    public function deleteGroup($data)
    {
        return $this->group->deleteItem($data);
    }

    public function editGroup($data)
    {
        $where['id'] = $data['id'];

        return $this->group->editItem($where, $data);
    }

    public function getGroupInfo($data)
    {
        $where['id'] = $data['id'];

        return $this->group->getInfo($where);
    }

    public function getGroupList($data)
    {
        $where = [];

        @is_real_exists($data['id']) && $where['id'] = $data['id'];
        @is_real_exists($data['name']) && $where['name'] = ['like', '%' . $data['name'] . '%'];

        return $this->group->getListByPage($where);
    }

}
