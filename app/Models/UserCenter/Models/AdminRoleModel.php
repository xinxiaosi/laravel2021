<?php


namespace App\Models\UserCenter\Models;


use App\Models\UserCenter\UserCenterModel;

class AdminRoleModel extends UserCenterModel
{
    public $table = 'admin_role';

    public $fillable = [
        'user_id', //int NOT NULL COMMENT '用户id',
        'role_id', //int NOT NULL COMMENT '角色id',
    ];

    public function auth()
    {
        return $this->belongsTo(AuthModel::class, 'auth_id', 'id');
    }

}
