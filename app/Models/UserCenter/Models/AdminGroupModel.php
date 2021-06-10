<?php

namespace App\Models\UserCenter\Models;

use App\Models\UserCenter\UserCenterModel;

class AdminGroupModel extends UserCenterModel
{
    public $table = 'admin_group';

    public $fillable = [
        'admin_id', //int NOT NULL COMMENT '用户id',
        'group_id', //int NOT NULL COMMENT '用户组id',
    ];

    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'user_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(AdminModel::class, 'group_id', 'id');
    }
}
