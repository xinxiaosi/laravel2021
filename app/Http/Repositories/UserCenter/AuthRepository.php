<?php

namespace App\Http\Repositories\UserCenter;

use App\Http\Repositories\BaseRepository;
use App\Models\UserCenter\Models\AuthModel;

class AuthRepository extends BaseRepository
{
    public $role;

    public function __construct(AuthModel $groupModel)
    {
        $this->role = $groupModel;
    }

    public function addAuth($data)
    {
        return $this->role->addItem($data);
    }

    public function deleteAuth($data)
    {
        return $this->role->deleteItem($data);
    }

    public function editAuth($data)
    {
        $where['id'] = $data['id'];

        return $this->role->editItem($where, $data);
    }

    public function getAuthInfo($data)
    {
        $where['id'] = $data['id'];

        return $this->role->getInfo($where);
    }

    public function getAuthList($data)
    {
        $where = [];
        @is_real_exists($data['id']) && $where['id'] = $data['id'];
        @is_real_exists($data['name']) && $where['name'] = ['like', '%' . $data['name'] . '%'];
        switch ($data['type']) {
            case 1://分页
                return $this->role->getListByPage($where);
            case 2:
                return $this->role->getList($where);
            case 3:
                return $this->generateTree($this->role->getList()->toArray());
            default:
                return [];
        }
    }

}
