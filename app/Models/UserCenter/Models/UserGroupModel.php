<?php

namespace App\Models\UserCenter\Models;

use App\Models\UserCenter\UserCenterModel;

class UserGroupModel extends UserCenterModel
{
    public $table = 'group';

    public $fillable = [
        'id', //int NOT NULL COMMENT '用户id',
        'group_id', //int NOT NULL COMMENT '用户组id',
        'create_time', //int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
        'update_time', //int NOT NULL DEFAULT '0' COMMENT '更新时间',
        'main_id', //mediumint NOT NULL DEFAULT '1' COMMENT '主id',
    ];

    public function admin()
    {
        return $this->belongsTo(AdminModel::class, 'id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(AdminModel::class, 'id', 'group_id');
    }
}
