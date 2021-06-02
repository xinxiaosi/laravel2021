<?php

namespace App\Models\UserCenter\Models;

use App\Models\UserCenter\UserCenterModel;

class RoleModel extends UserCenterModel
{
    public $table = 'role';

    public $fillable = [
        'name', //varchar NOT NULL DEFAULT '' COMMENT '名称',
        'remarks', //varchar NOT NULL DEFAULT '' COMMENT '备注',
        'status', //tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1=正常;2=不正常',
        'create_time', //int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
        'update_time', //int NOT NULL DEFAULT '0' COMMENT '更新时间',
        'main_id', //mediumint NOT NULL DEFAULT '1' COMMENT '主id',
    ];

}
