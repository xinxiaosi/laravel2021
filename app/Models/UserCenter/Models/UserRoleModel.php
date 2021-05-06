<?php


namespace App\Models\UserModels\Models;


use App\Models\UserModels\RulesModel;
use App\Models\UserModels\UserCenterModel;

class UserRoleModel extends UserCenterModel
{
    public $table = 'user_role';
    public $fillable = [
        'role',// varchar(64) NOT NULL DEFAULT '' COMMENT '角色名',
        'parent_id',// int(11) NOT NULL DEFAULT '0' COMMENT '父级ID',
        'status',// tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
        'rule_id',// varchar(256) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '权限',
        'describe',// varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
        'sort',// int(11) NOT NULL DEFAULT '0' COMMENT '排序',
        'create_user',
        'create_user_uuid',
//        'create_time',// datetime DEFAULT NULL,
//        'update_time',// datetime DEFAULT NULL,
    ];

    public function rule()
    {
        return $this->hasManyFromStr(RulesModel::class, 'id', 'rule_id');
    }
}
