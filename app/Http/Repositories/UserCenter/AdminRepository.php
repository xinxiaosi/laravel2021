<?php


namespace App\Http\Repositories\UserCenter;


use App\Exceptions\ApiException;
use App\Http\Repositories\BaseRepository;
use App\Models\UserCenter\Models\AdminGroupModel;
use App\Models\UserCenter\Models\AdminModel;
use App\Models\UserCenter\Models\AdminRoleModel;

class AdminRepository extends BaseRepository
{
    public $cacheKey = "userCenter:Admin:userInfo:";
    private $admin;

    public function __construct(AdminModel $adminModel)
    {
        $this->admin = $adminModel;
    }

    public function login($data)
    {
        $where['name'] = $data['name'];
        $where['password'] = md5($data['password']);
        $where['status'] = self::TRUE;
        $where['is_delete'] = self::False;

        if (isset($data['name'])) {
            $where['name'] = $data['name'];
        } else {
            $where['email'] = $data['email'];
        }

        //jwt password加密用 password_hash('123456', PASSWORD_BCRYPT);
        if (!$token = auth('jwt')->attempt($where)) {
            return response()->json(['result' => 'failed']);
        }
        return $this->respondWithToken($token);

    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => '10'
        ]);
    }

    /**
     * @param $data
     * @return mixed
     * @throws ApiException
     */
    public function addAdmin($data)
    {
        $user = $this->admin->where('name', $data['name'])->first();

        if (!empty($user)) {
            throw new ApiException([0, $data['name'] . ' .用户已存在']);
        }

        $data['uid'] = md5($data['name'] . $data['password'] . time() . uniqid());
        $data['token'] = md5($data['name'] . $data['password'] . time());

        $admin = $this->admin->addItem($data);
        $group = $role = [];
        foreach ($data['role'] as $v) {
            $role[] = [
                'user_id' => $admin->id,
                'role_id' => $v,
            ];
        }
        (new AdminRoleModel())->insert($role);


        foreach ($data['group'] as $v) {
            $group[] = [
                'user_id' => $admin->id,
                'group_id' => $v,
            ];
        }
        (new AdminGroupModel())->insert($group);

        return $admin;
    }

    /**
     * @param $data
     * @return array
     */
    public function deleteAdmin($data)
    {
        $ids = explode(',', $data['ids']);

        $this->admin->whereIn('id', $ids)->delete();
        (new AdminGroupModel())->whereIn('user_id', $ids)->delete();
        (new AdminRoleModel())->whereIn('user_id', $ids)->delete();

        return [];
    }

    public function editAdmin($data)
    {
        if (@is_real_exists($data['id'])) {
            $where['id'] = $data['id'];
        } else {
            $where['uid'] = $data['uid'];
        }

        isset($data['password']) && $data['password'] = md5($data['password']);
        $admin = $this->admin->handleCondition($where)->first();
        $admin->update($data);
        if (!empty($data['role'])) {
            $role = [];
            $admin->role()->delete();
            foreach ($data['role'] as $v) {
                $role[] = [
                    'user_id' => $admin->id,
                    'role_id' => $v
                ];
            }
            $admin->role()->insert($role);
        }

        if (!empty($data['group'])) {
            $group = [];
            $admin->group()->delete();
            foreach ($data['group'] as $v) {
                $group[] = [
                    'user_id' => $admin->id,
                    'group_id' => $v
                ];
            }
            $admin->group()->insert($group);
        }

        return [];
    }

    public function getAdminInfo($data)
    {
        if (is_real_exists($data['id'])) {
            $where['id'] = $data['id'];
        } else {
            $where['uid'] = $data['uid'];
        }

        return $this->admin->getAdminInfo($where);
    }

    public function getAdminList($data)
    {
        $where = [];

        @is_real_exists($data['id']) && $where['id'] = $data['id'];
        @is_real_exists($data['uid']) && $where['uid'] = $data['uid'];
        @is_real_exists($data['status']) && $where['status'] = $data['status'];
        @is_real_exists($data['name']) && $where['name'] = ['like', '%'. $data['name'] .'%'];
        @is_real_exists($data['email']) && $where['email'] = ['like', '%'. $data['email'] .'%'];
        @is_real_exists($data['phone']) && $where['phone'] = ['like', '%'. $data['phone'] .'%'];

        $role = @is_real_exists($data['role_id']) ?  $data['role_id'] : [];
        $group = @is_real_exists($data['group_id']) ?  $data['group_id'] : [];

        return $this->admin->getAdminList($where, $role, $group);
    }

}
