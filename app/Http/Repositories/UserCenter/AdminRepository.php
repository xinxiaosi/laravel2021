<?php


namespace App\Http\Repositories\UserCenter;


use App\Exceptions\ApiException;
use App\Http\Repositories\BaseRepository;
use App\Models\UserCenter\Models\AdminModel;

class AdminRepository extends BaseRepository
{
    private $admin;
    public function __construct(AdminModel $adminModel)
    {
        $this->admin = $adminModel;
    }

    public function addData($data)
    {
        $where['name'] = $data['name'];
        $user = $this->admin->where($where)->first();

        if (!empty($user)) {
            throw new ApiException([0, $data['name'].' .用户已存在']);
        }


        $data['uid'] = md5($data['name'].$data['password'].time().uniqid());
        $data['token'] = md5($data['name'].$data['password'].time());

        return $this->admin->addItem($data);
    }
}
