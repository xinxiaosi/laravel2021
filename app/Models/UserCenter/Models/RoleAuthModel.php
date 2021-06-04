<?php

namespace App\Models\UserCenter\Models;

use App\Models\UserCenter\UserCenterModel;

class RoleAuthModel extends UserCenterModel
{
    public $table = 'role_auth';

    public $fillable = [
        'role_id', //int NOT NULL COMMENT '用户id',
        'auth_id', //int NOT NULL COMMENT '用户组id',
//        'create_time', //int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
//        'update_time', //int NOT NULL DEFAULT '0' COMMENT '更新时间',
        'main_id', //mediumint NOT NULL DEFAULT '1' COMMENT '主id',
    ];

    public function auth()
    {
        return $this->belongsTo(AuthModel::class, 'auth_id', 'id');
    }

}
