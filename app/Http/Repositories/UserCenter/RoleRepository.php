<?php

namespace App\Http\Repositories\UserCenter;

use App\Http\Repositories\BaseRepository;
use App\Models\UserCenter\Models\RoleAuthModel;
use App\Models\UserCenter\Models\RoleModel;

class RoleRepository extends BaseRepository
{
    public $role;

    public function __construct(RoleModel $groupModel)
    {
        $this->role = $groupModel;
    }

    public function addRole($data)
    {
        $role = $this->role->addItem($data);
        $insert = [];
        foreach ($data['auth'] as $id) {
            $insert[] = [
                'role_id' => $role->id,
                'auth_id' => $id,
                'create_time' => time(),
                'update_time' => time(),
            ];
        }

        (new RoleAuthModel())->insert($insert);

        return $role;
    }

    public function deleteRole($data)
    {
        return $this->role->deleteItem($data);
    }

    /**
     * @param $data
     * @return array
     * @throws \App\Exceptions\ApiException
     */
    public function editRole($data)
    {
        $where['id'] = $data['id'];

        return $this->role->editItem($where, $data);
    }

    public function getRoleInfo($data)
    {
        $where['id'] = $data['id'];

        return $this->role->getRoleInfo($where);
    }

    public function getRoleList($data)
    {
        $where = [];

        @is_real_exists($data['id']) && $where['id'] = $data['id'];
        @is_real_exists($data['name']) && $where['name'] = ['like', '%' . $data['name'] . '%'];

        return $this->role->getListByPage($where);
    }

}
