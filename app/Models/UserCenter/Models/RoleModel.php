<?php

namespace App\Models\UserCenter\Models;

use App\Models\UserCenter\UserCenterModel;

class RoleModel extends UserCenterModel
{
    public $table = 'role';

    public $fillable = [
        'parent_id', //int NOT NULL DEFAULT '0' COMMENT '父级id',
        'ids_url', //varchar(255) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '父级id路径',
        'name', //varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
        'remarks', //varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '备注',
        'status', //tinyint unsigned NOT NULL DEFAULT '1' COMMENT '状态 1=开启;2=关闭',
//        'create_time', //int unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
//        'update_time', //int NOT NULL DEFAULT '0' COMMENT '更新时间',
        'main_id', //mediumint NOT NULL DEFAULT '1' COMMENT '主id',
    ];

    public function auth()
    {
        return $this->hasMany(RoleAuthModel::class, 'role_id', 'id')
            ->join('auth', 'role_auth.auth_id', '=', 'auth.id')
            ->selectRaw('xin_role_auth.role_id,xin_auth.id,xin_auth.name,xin_auth.parent_id')
            ;
    }

    public function getRoleInfo($where)
    {
        $info = $this->handleCondition($where)
            ->with('auth')
            ->first()
            ->toArray();
        $info['auth'] = generateTree($info['auth']);

        return $info;
    }
}
