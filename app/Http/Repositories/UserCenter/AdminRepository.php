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
        $where['password'] = md5($data['password']);
        $where['status'] = self::TRUE;
        $where['is_delete'] = self::False;

        if (isset($data['name'])) {
            $where['name'] = $data['name'];
        } else {
            $where['email'] = $data['email'];
        }
        $userInfo = $this->admin->getFirst($where);

        // jwt token

        if (!$token = auth('jwt')->attempt($where)) {
            return response()->json(['result'=>'failed']);
        }
        dd($token);
        return $this->respondWithToken($token);

        return $userInfo;
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('jwt')->factory()->getTTL() * 6000
        ]);
    }

    public function addData($data)
    {
        $user = $this->admin->where('name', $data['name'])->first();

        if (!empty($user)) {
            throw new ApiException([0, $data['name'].' .用户已存在']);
        }


        $data['uid'] = md5($data['name'].$data['password'].time().uniqid());
        $data['token'] = md5($data['name'].$data['password'].time());

        return $this->admin->addItem($data);
    }
}
