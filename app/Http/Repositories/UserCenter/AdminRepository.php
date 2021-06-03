<?php


namespace App\Http\Repositories\UserCenter;


use App\Exceptions\ApiException;
use App\Http\Repositories\BaseRepository;
use App\Models\UserCenter\Models\AdminModel;

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

    public function addData($data)
    {
        $user = $this->admin->where('name', $data['name'])->first();

        if (!empty($user)) {
            throw new ApiException([0, $data['name'] . ' .用户已存在']);
        }


        $data['uid'] = md5($data['name'] . $data['password'] . time() . uniqid());
        $data['token'] = md5($data['name'] . $data['password'] . time());

        return $this->admin->addItem($data);
    }

    public function deleteData($data)
    {
        return $this->admin->deleteItem($data);
    }

    public function editData($data)
    {
        if (@is_real_exists($data['id'])) {
            $where['id'] = $data['id'];
        } else {
            $where['uid'] = $data['uid'];
        }

        return $this->admin->editItem($where, $data);
    }

    public function getInfo($data)
    {

        dd(config('userInfo'));

        if (is_real_exists($data['id'])) {
            $where['id'] = $data['id'];
        } else {
            $where['uid'] = $data['uid'];
        }

        return $this->admin->getInfo($where);
    }

    public function getList($data)
    {
        $where = [];

        @is_real_exists($data['id']) && $where['id'] = $data['id'];
        @is_real_exists($data['uid']) && $where['uid'] = $data['uid'];
        @is_real_exists($data['status']) && $where['status'] = $data['status'];
        @is_real_exists($data['role_id']) && $where['role_id'] = $data['role_id'];
        @is_real_exists($data['name']) && $where['name'] = ['like', '%'. $data['name'] .'%'];
        @is_real_exists($data['email']) && $where['email'] = ['like', '%'. $data['email'] .'%'];
        @is_real_exists($data['phone']) && $where['phone'] = ['like', '%'. $data['phone'] .'%'];

        return $this->admin->getAdminList($where);
    }

}
